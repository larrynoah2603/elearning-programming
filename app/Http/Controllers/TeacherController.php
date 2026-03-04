<?php

namespace App\Http\Controllers;

use App\Models\ClassAssignment;
use App\Models\ClassGroup;
use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TeacherController extends Controller
{
    public function dashboard(Request $request)
    {
        $teacher = $request->user();

        $groups = ClassGroup::query()
            ->with(['students', 'assignments'])
            ->where('teacher_id', $teacher->id)
            ->latest()
            ->get();

        $assignments = ClassAssignment::query()
            ->whereIn('class_group_id', $groups->pluck('id'))
            ->orderBy('due_at')
            ->get();

        $stats = [
            'groups_count' => $groups->count(),
            'students_count' => $groups->sum(fn (ClassGroup $group) => $group->students->count()),
            'assignments_count' => $assignments->count(),
        ];

        $completionRate = $this->computeCompletionRate($groups, $assignments);
        $lateStudents = $this->buildLateStudents($groups, $assignments);
        $blockingExercises = $this->buildBlockingExercises($assignments);

        $candidateStudents = User::query()
            ->where('role', '!=', 'admin')
            ->where('id', '!=', $teacher->id)
            ->when($teacher->school_name, fn ($q) => $q->where('school_name', $teacher->school_name))
            ->when($teacher->class_name, fn ($q) => $q->where('class_name', $teacher->class_name))
            ->orderBy('name')
            ->get();

        $exercises = Exercise::query()->active()->orderBy('title')->get();
        $lessons = Lesson::query()->active()->orderBy('title')->get();

        return view('teacher.dashboard', compact(
            'groups',
            'assignments',
            'stats',
            'completionRate',
            'lateStudents',
            'blockingExercises',
            'candidateStudents',
            'exercises',
            'lessons'
        ));
    }

    public function storeGroup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:2000',
            'school_name' => 'nullable|string|max:120',
            'class_name' => 'nullable|string|max:120',
        ]);

        $validated['teacher_id'] = $request->user()->id;
        $validated['school_name'] = $validated['school_name'] ?? $request->user()->school_name;
        $validated['class_name'] = $validated['class_name'] ?? $request->user()->class_name;

        ClassGroup::create($validated);

        return back()->with('success', 'Groupe créé avec succès.');
    }

    public function attachStudent(Request $request, ClassGroup $group)
    {
        $this->authorizeGroup($request, $group);

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $group->students()->syncWithoutDetaching([$validated['student_id']]);

        return back()->with('success', 'Élève ajouté au groupe.');
    }

    public function storeAssignment(Request $request, ClassGroup $group)
    {
        $this->authorizeGroup($request, $group);

        $validated = $request->validate([
            'content_type' => 'required|in:exercise,lesson',
            'content_id' => 'required|integer',
            'title' => 'required|string|max:180',
            'instructions' => 'nullable|string|max:4000',
            'due_at' => 'nullable|date',
        ]);

        if ($validated['content_type'] === 'exercise') {
            abort_unless(Exercise::query()->whereKey($validated['content_id'])->exists(), 422, 'Exercice invalide.');
        }

        if ($validated['content_type'] === 'lesson') {
            abort_unless(Lesson::query()->whereKey($validated['content_id'])->exists(), 422, 'Leçon invalide.');
        }

        $group->assignments()->create($validated);

        return back()->with('success', 'Devoir assigné avec succès.');
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $teacher = $request->user();

        $groups = ClassGroup::query()
            ->with(['students', 'assignments'])
            ->where('teacher_id', $teacher->id)
            ->get();

        $assignments = ClassAssignment::query()
            ->whereIn('class_group_id', $groups->pluck('id'))
            ->get();

        $response = new StreamedResponse(function () use ($groups, $assignments) {
            $handle = fopen('php://output', 'wb');

            fputcsv($handle, ['Groupe', 'Élève', 'Devoir', 'Type', 'Échéance', 'Statut']);

            foreach ($groups as $group) {
                foreach ($group->students as $student) {
                    foreach ($group->assignments as $assignment) {
                        fputcsv($handle, [
                            $group->name,
                            $student->name,
                            $assignment->title,
                            $assignment->content_type,
                            optional($assignment->due_at)?->format('Y-m-d H:i'),
                            $this->isAssignmentCompletedForStudent($assignment, $student->id) ? 'Terminé' : 'En attente',
                        ]);
                    }
                }
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="rapport-enseignant.csv"');

        return $response;
    }

    private function computeCompletionRate($groups, $assignments): int
    {
        $totalPairs = 0;
        $completed = 0;

        foreach ($groups as $group) {
            foreach ($group->students as $student) {
                foreach ($group->assignments as $assignment) {
                    $totalPairs++;
                    if ($this->isAssignmentCompletedForStudent($assignment, $student->id)) {
                        $completed++;
                    }
                }
            }
        }

        if ($totalPairs === 0) {
            return 0;
        }

        return (int) round(($completed / $totalPairs) * 100);
    }

    private function buildLateStudents($groups, $assignments): array
    {
        $late = [];

        foreach ($groups as $group) {
            foreach ($group->students as $student) {
                $pendingOverdue = $group->assignments
                    ->filter(fn (ClassAssignment $assignment) => $assignment->due_at && $assignment->due_at->isPast())
                    ->reject(fn (ClassAssignment $assignment) => $this->isAssignmentCompletedForStudent($assignment, $student->id));

                if ($pendingOverdue->isNotEmpty()) {
                    $late[] = [
                        'student_name' => $student->name,
                        'group_name' => $group->name,
                        'late_count' => $pendingOverdue->count(),
                    ];
                }
            }
        }

        usort($late, fn ($a, $b) => $b['late_count'] <=> $a['late_count']);

        return array_slice($late, 0, 10);
    }

    private function buildBlockingExercises($assignments): array
    {
        $exerciseAssignments = $assignments->where('content_type', 'exercise');

        $rows = $exerciseAssignments->map(function (ClassAssignment $assignment) {
            $successful = ExerciseSubmission::query()
                ->where('exercise_id', $assignment->content_id)
                ->where('status', 'reussi')
                ->count();

            $attempted = ExerciseSubmission::query()
                ->where('exercise_id', $assignment->content_id)
                ->distinct('user_id')
                ->count('user_id');

            $successRate = $attempted > 0 ? (int) round(($successful / $attempted) * 100) : 0;

            return [
                'title' => $assignment->title,
                'attempted' => $attempted,
                'success_rate' => $successRate,
            ];
        })->sortBy('success_rate')->values();

        return $rows->take(5)->all();
    }

    private function isAssignmentCompletedForStudent(ClassAssignment $assignment, int $studentId): bool
    {
        if ($assignment->content_type === 'exercise') {
            return ExerciseSubmission::query()
                ->where('user_id', $studentId)
                ->where('exercise_id', $assignment->content_id)
                ->where('status', 'reussi')
                ->exists();
        }

        if ($assignment->content_type === 'lesson') {
            return ExerciseSubmission::query()
                ->where('user_id', $studentId)
                ->where('status', 'reussi')
                ->whereHas('exercise', fn ($q) => $q->where('lesson_id', $assignment->content_id))
                ->exists();
        }

        return false;
    }

    private function authorizeGroup(Request $request, ClassGroup $group): void
    {
        abort_unless($group->teacher_id === $request->user()->id || $request->user()->isAdmin(), 403);
    }
}
