<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Video;
use App\Models\VideoProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    /**
     * Display a listing of videos.
     */
    public function index(Request $request)
    {
        $query = Video::active();

        // Filter by level
        if ($request->has('level') && $request->level !== 'all') {
            $query->byLevel($request->level);
        }

        // Filter by access level based on user
        if (!auth()->check() || !auth()->user()->isSubscribed()) {
            $query->where('access_level', 'free');
        } elseif ($request->has('access') && $request->access !== 'all') {
            $query->where('access_level', $request->access);
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $videos = $query->orderBy('order', 'asc')->orderBy('created_at', 'desc')->paginate(12);

        return view('videos.index', compact('videos'));
    }

    /**
     * Display the specified video.
     */
    public function show(string $slug)
    {
        $video = Video::where('slug', $slug)
            ->with(['user', 'lesson'])
            ->firstOrFail();

        // Check access
        if (!$video->isAccessibleBy(auth()->user())) {
            return redirect()->route('subscription.plans')
                ->with('info', 'Cette vidéo nécessite un abonnement. Découvrez nos offres !');
        }

        // Increment views
        $video->incrementViews();

        // Get user's progress if exists
        $progress = null;
        if (auth()->check()) {
            $progress = $video->getUserProgress(auth()->user());
        }

        // Get related videos - CORRIGÉ : s'assurer que les URLs sont correctes
        $relatedVideos = Video::active()
            ->where('id', '!=', $video->id)
            ->where('level', $video->level)
            ->take(3)
            ->get()
            ->each(function ($relatedVideo) {
                // Force le chargement des accesseurs
                $relatedVideo->append(['video_url', 'thumbnail_url', 'duration_display']);
            });

        return view('videos.show', compact('video', 'progress', 'relatedVideos'));
    }

    /**
     * Update video progress.
     */
    public function updateProgress(Request $request, Video $video)
    {
        // Check access
        if (!$video->isAccessibleBy(auth()->user())) {
            return response()->json(['error' => 'Subscription required'], 403);
        }

        $validated = $request->validate([
            'current_time' => 'required|integer|min:0',
            'watched_duration' => 'required|integer|min:0',
        ]);

        // Ensure values don't exceed video duration
        if ($video->duration > 0) {
            $validated['current_time'] = min($validated['current_time'], $video->duration);
            $validated['watched_duration'] = min($validated['watched_duration'], $video->duration);
        }

        $user = auth()->user();

        $progress = VideoProgress::firstOrNew([
            'user_id' => $user->id,
            'video_id' => $video->id,
        ]);

        $progress->updateProgress($validated['current_time'], $validated['watched_duration']);

        return response()->json([
            'success' => true,
            'progress' => $progress->fresh(),
            'progress_percentage' => $progress->progress_percentage,
            'is_completed' => $progress->is_completed,
        ]);
    }

    /**
     * Mark video as completed.
     */
    public function markCompleted(Video $video)
    {
        // Check access
        if (!$video->isAccessibleBy(auth()->user())) {
            return response()->json(['error' => 'Subscription required'], 403);
        }

        $user = auth()->user();

        $progress = VideoProgress::firstOrNew([
            'user_id' => $user->id,
            'video_id' => $video->id,
        ]);

        $progress->markAsCompleted();

        return response()->json([
            'success' => true,
            'message' => 'Vidéo marquée comme terminée.',
        ]);
    }

    /**
     * Show the form for creating a new video.
     */
    public function create()
    {
        $lessons = Lesson::active()->orderBy('order')->get();
        return view('admin.videos.create', compact('lessons'));
    }

    /**
     * Store a newly created video.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'level' => 'required|in:debutant,intermediaire,avance',
            'access_level' => 'required|in:free,subscribed',
            'video_file' => 'required|file|mimes:mp4,webm,ogg|max:512000', // Max 500MB
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
            'duration' => 'nullable|integer|min:1',
            'lesson_id' => 'nullable|exists:lessons,id',
            'order' => 'nullable|integer|min:0',
        ]);

        // Handle video upload
        $videoPath = $request->file('video_file')->store('videos', 'public');
        $validated['video_file'] = $videoPath;

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('videos/thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        // Set default values
        $validated['user_id'] = auth()->id();
        $validated['slug'] = $this->generateUniqueSlug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? 0;

        // Auto-detect duration if not provided and file exists
        if (empty($validated['duration']) && isset($videoPath)) {
            $fullPath = Storage::disk('public')->path($videoPath);
            $validated['duration'] = $this->getVideoDuration($fullPath);
        }

        $video = Video::create($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Vidéo créée avec succès.');
    }

    /**
     * Display videos list for admin.
     */
    public function adminIndex(Request $request)
    {
        $query = Video::with('user');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by level
        if ($request->has('level') && !empty($request->level)) {
            $query->where('level', $request->level);
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $videos = $query->latest()->paginate(20);

        return view('admin.videos.index', compact('videos'));
    }

    /**
     * Show the form for editing the video.
     */
    public function edit(Video $video)
    {
        $lessons = Lesson::active()->orderBy('order')->get();
        return view('admin.videos.edit', compact('video', 'lessons'));
    }

    /**
     * Update the video.
     */
    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'level' => 'required|in:debutant,intermediaire,avance',
            'access_level' => 'required|in:free,subscribed',
            'video_file' => 'nullable|file|mimes:mp4,webm,ogg|max:512000', // Corrigé: 512000 au lieu de 524288
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'duration' => 'nullable|integer|min:1',
            'lesson_id' => 'nullable|exists:lessons,id',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle video upload
        if ($request->hasFile('video_file')) {
            // Delete old video
            if ($video->video_file && Storage::disk('public')->exists($video->video_file)) {
                Storage::disk('public')->delete($video->video_file);
            }

            $videoPath = $request->file('video_file')->store('videos', 'public');
            $validated['video_file'] = $videoPath;
            
            // Auto-detect duration for new video
            $fullPath = Storage::disk('public')->path($videoPath);
            $validated['duration'] = $this->getVideoDuration($fullPath);
        } else {
            unset($validated['video_file']);
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
                Storage::disk('public')->delete($video->thumbnail);
            }

            $thumbnailPath = $request->file('thumbnail')->store('videos/thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        } else {
            unset($validated['thumbnail']);
        }

        // Update slug if title changed
        if ($video->title !== $validated['title']) {
            $validated['slug'] = $this->generateUniqueSlug($validated['title'], $video->id);
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? $video->order ?? 0;

        $video->update($validated);

        return redirect()->route('admin.videos.index')
            ->with('success', 'Vidéo mise à jour avec succès.');
    }

    /**
     * Remove the video.
     */
    public function destroy(Video $video)
    {
        // Delete video file
        if ($video->video_file && Storage::disk('public')->exists($video->video_file)) {
            Storage::disk('public')->delete($video->video_file);
        }

        // Delete thumbnail
        if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        // Delete associated progress records
        VideoProgress::where('video_id', $video->id)->delete();

        $video->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', 'Vidéo supprimée avec succès.');
    }

    /**
     * Toggle video active status.
     */
    public function toggleActive(Video $video)
    {
        $video->update(['is_active' => !$video->is_active]);

        $status = $video->is_active ? 'activée' : 'désactivée';

        return back()->with('success', "Vidéo {$status} avec succès.");
    }

    /**
     * Generate unique slug for video.
     */
    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        $query = Video::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
            $query = Video::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    /**
     * Get video duration using ffprobe if available.
     */
    private function getVideoDuration(string $path): ?int
    {
        if (!file_exists($path)) {
            return null;
        }

        // Try ffprobe first
        $ffprobePath = trim(shell_exec('which ffprobe') ?: '');
        if (!empty($ffprobePath)) {
            $command = sprintf(
                '%s -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 %s 2>/dev/null',
                escapeshellarg($ffprobePath),
                escapeshellarg($path)
            );
            $output = shell_exec($command);
            if ($output !== null && is_numeric(trim($output))) {
                return (int) round(floatval(trim($output)));
            }
        }

        // Fallback: try to get duration from video metadata
        // Vérifiez d'abord si getID3 est installé
        if (class_exists('getID3')) {
            $getID3 = new \getID3();
            $fileInfo = $getID3->analyze($path);
            if (isset($fileInfo['playtime_seconds'])) {
                return (int) round($fileInfo['playtime_seconds']);
            }
        }

        return null;
    }
}