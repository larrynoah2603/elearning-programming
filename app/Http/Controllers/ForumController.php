<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ForumController extends Controller
{
    public function index(Request $request): View
    {
        $lessonId = $request->integer('lesson_id') ?: null;
        $exerciseId = $request->integer('exercise_id') ?: null;

        $threads = ForumThread::query()
            ->with(['user', 'lesson', 'exercise', 'replies.user'])
            ->when($lessonId, fn ($q) => $q->where('lesson_id', $lessonId))
            ->when($exerciseId, fn ($q) => $q->where('exercise_id', $exerciseId))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $lessons = Lesson::query()->active()->orderBy('title')->get(['id', 'title']);
        $exercises = Exercise::query()->active()->orderBy('title')->get(['id', 'title']);

        return view('forum.index', compact('threads', 'lessons', 'exercises', 'lessonId', 'exerciseId'));
    }

    public function storeThread(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
            'lesson_id' => 'nullable|exists:lessons,id',
            'exercise_id' => 'nullable|exists:exercises,id',
        ]);

        ForumThread::create([
            'user_id' => $request->user()->id,
            'lesson_id' => $validated['lesson_id'] ?? null,
            'exercise_id' => $validated['exercise_id'] ?? null,
            'title' => $validated['title'],
            'body' => $validated['body'],
        ]);

        return back()->with('success', 'Sujet publié sur le forum.');
    }

    public function storeReply(Request $request, ForumThread $thread): RedirectResponse
    {
        $validated = $request->validate([
            'body' => 'required|string|max:3000',
        ]);

        ForumReply::create([
            'thread_id' => $thread->id,
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        return back()->with('success', 'Réponse ajoutée.');
    }

    public function resolve(Request $request, ForumThread $thread): RedirectResponse
    {
        if ($thread->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            abort(403);
        }

        $thread->update(['is_resolved' => !$thread->is_resolved]);

        return back()->with('success', 'Statut du sujet mis à jour.');
    }
}
