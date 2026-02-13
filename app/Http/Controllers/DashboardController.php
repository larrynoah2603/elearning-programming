<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\ExerciseSubmission;
use App\Models\Lesson;
use App\Models\Video;
use App\Models\VideoProgress;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Stats for all users
        $stats = [
            'completed_exercises' => $user->completed_exercises_count,
            'total_points' => $user->total_points,
            'watched_videos' => $user->watched_videos_count,
        ];

        // Recent submissions
        $recentSubmissions = ExerciseSubmission::with('exercise')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Recent video progress
        $recentVideoProgress = VideoProgress::with('video')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Available content based on user role
        if ($user->isSubscribed()) {
            $availableLessons = Lesson::active()
                ->orderBy('order')
                ->take(6)
                ->get();

            $availableExercises = Exercise::active()
                ->orderBy('order')
                ->take(6)
                ->get();

            $availableVideos = Video::active()
                ->orderBy('order')
                ->take(6)
                ->get();
        } else {
            $availableLessons = Lesson::active()
                ->where('access_level', 'free')
                ->orderBy('order')
                ->take(6)
                ->get();

            $availableExercises = Exercise::active()
                ->where('access_level', 'free')
                ->orderBy('order')
                ->take(6)
                ->get();

            $availableVideos = collect(); // Free users don't have access to videos
        }

        // Progress data for charts
        $exerciseProgress = [
            'simple' => Exercise::simple()->whereHas('submissions', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'reussi');
            })->count(),
            'complexe' => Exercise::complex()->whereHas('submissions', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'reussi');
            })->count(),
        ];

        return view('dashboard', compact(
            'stats',
            'recentSubmissions',
            'recentVideoProgress',
            'availableLessons',
            'availableExercises',
            'availableVideos',
            'exerciseProgress'
        ));
    }

    /**
     * Display user profile.
     */
    public function profile()
    {
        $user = auth()->user();

        $allSubmissions = ExerciseSubmission::with('exercise')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $allVideoProgress = VideoProgress::with('video')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('profile', compact('user', 'allSubmissions', 'allVideoProgress'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('success', 'Votre profil a été mis à jour avec succès.');
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => bcrypt($validated['password']),
        ]);

        return back()->with('success', 'Votre mot de passe a été changé avec succès.');
    }
}
