<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'free_users' => User::where('role', 'free')->count(),
            'subscribed_users' => User::where('role', 'subscribed')
                ->where(function ($q) {
                    $q->whereNull('subscription_expires_at')
                      ->orWhere('subscription_expires_at', '>', now());
                })->count(),
            'expired_subscriptions' => User::where('role', 'subscribed')
                ->where('subscription_expires_at', '<', now())
                ->count(),
            'total_lessons' => Lesson::count(),
            'active_lessons' => Lesson::where('is_active', true)->count(),
            'total_exercises' => Exercise::count(),
            'active_exercises' => Exercise::where('is_active', true)->count(),
            'total_videos' => Video::count(),
            'active_videos' => Video::where('is_active', true)->count(),
            'pending_submissions' => ExerciseSubmission::where('status', 'soumis')->count(),
            'total_categories' => Category::count(),
        ];

        // Recent users
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Recent submissions
        $recentSubmissions = ExerciseSubmission::with(['user', 'exercise'])
            ->latest()
            ->take(5)
            ->get();

        // Pending submissions
        $pendingSubmissions = ExerciseSubmission::with(['user', 'exercise'])
            ->where('status', 'soumis')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentSubmissions',
            'pendingSubmissions'
        ));
    }

    /**
     * Display users list.
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display user details.
     */
    public function showUser(User $user)
    {
        $user->load(['exerciseSubmissions.exercise', 'videoProgress.video']);

        $stats = [
            'completed_exercises' => $user->completed_exercises_count,
            'total_points' => $user->total_points,
            'watched_videos' => $user->watched_videos_count,
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Edit user.
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user.
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,free,subscribed',
            'subscription_expires_at' => 'nullable|date',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Delete user.
     */
    public function deleteUser(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Upgrade user to subscribed.
     */
    public function upgradeUser(User $user, Request $request)
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:1',
        ]);

        $user->upgradeToSubscribed($validated['days']);

        return back()->with('success', "L'utilisateur a été upgradé vers un abonnement de {$validated['days']} jours.");
    }

    /**
     * Downgrade user to free.
     */
    public function downgradeUser(User $user)
    {
        $user->downgradeToFree();

        return back()->with('success', "L'utilisateur a été downgrade vers un compte gratuit.");
    }

    /**
     * Display pending submissions.
     */
    public function pendingSubmissions()
    {
        $submissions = ExerciseSubmission::with(['user', 'exercise'])
            ->where('status', 'soumis')
            ->latest()
            ->paginate(20);

        return view('admin.submissions.pending', compact('submissions'));
    }

    /**
     * Display submission correction form.
     */
    public function correctSubmission(ExerciseSubmission $submission)
    {
        $submission->load(['user', 'exercise']);

        return view('admin.submissions.correct', compact('submission'));
    }

    /**
     * Submit correction.
     */
    public function submitCorrection(Request $request, ExerciseSubmission $submission)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:5000',
        ]);

        $submission->markAsCorrected(
            $validated['score'],
            $validated['feedback'],
            auth()->id()
        );

        return redirect()->route('admin.submissions.pending')
            ->with('success', 'Correction soumise avec succès.');
    }

    /**
     * Display all submissions.
     */
    public function allSubmissions()
    {
        $submissions = ExerciseSubmission::with(['user', 'exercise'])
            ->latest()
            ->paginate(20);

        return view('admin.submissions.index', compact('submissions'));
    }

    /**
     * Display statistics.
     */
    public function statistics()
    {
        // User statistics
        $userStats = [
            'by_role' => User::selectRaw('role, COUNT(*) as count')
                ->groupBy('role')
                ->pluck('count', 'role'),
            'by_month' => User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->take(12)
                ->pluck('count', 'month'),
        ];

        // Exercise statistics
        $exerciseStats = [
            'by_difficulty' => Exercise::selectRaw('difficulty, COUNT(*) as count')
                ->groupBy('difficulty')
                ->pluck('count', 'difficulty'),
            'by_access' => Exercise::selectRaw('access_level, COUNT(*) as count')
                ->groupBy('access_level')
                ->pluck('count', 'access_level'),
            'by_language' => Exercise::selectRaw('programming_language, COUNT(*) as count')
                ->groupBy('programming_language')
                ->pluck('count', 'programming_language'),
        ];

        // Submission statistics
        $submissionStats = [
            'by_status' => ExerciseSubmission::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status'),
            'total' => ExerciseSubmission::count(),
            'success_rate' => ExerciseSubmission::where('status', 'reussi')->count() / max(ExerciseSubmission::count(), 1) * 100,
        ];

        // Video statistics
        $videoStats = [
            'total_views' => Video::sum('views'),
            'most_viewed' => Video::orderBy('views', 'desc')
                ->take(5)
                ->get(),
        ];

        return view('admin.statistics', compact(
            'userStats',
            'exerciseStats',
            'submissionStats',
            'videoStats'
        ));
    }
}
