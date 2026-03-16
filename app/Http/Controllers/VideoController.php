<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Video;
use App\Models\VideoProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
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
     * Stream video file from storage (fix lecture sans lien symbolique public/storage).
     */
    public function stream(string $video)
    {
        $video = Video::query()
            ->where('id', $video)
            ->orWhere('slug', $video)
            ->firstOrFail();

        if (!$video->isAccessibleBy(auth()->user())) {
            abort(403);
        }

        if (!$video->video_file) {
            abort(404);
        }

        if (filter_var($video->video_file, FILTER_VALIDATE_URL)) {
            return redirect()->away($video->video_file);
        }

        $normalized = str_replace('\\', '/', trim($video->video_file));
        $candidates = [
            ltrim($normalized, '/'),
            ltrim(Str::replaceFirst('storage/', '', $normalized), '/'),
            ltrim(Str::replaceFirst('public/', '', $normalized), '/'),
        ];

        $resolvedPublicPath = collect($candidates)
            ->unique()
            ->first(fn (string $path) => Storage::disk('public')->exists($path));

        if ($resolvedPublicPath) {
            $absolutePath = Storage::disk('public')->path($resolvedPublicPath);
            $mimeType = Storage::disk('public')->mimeType($resolvedPublicPath) ?? 'video/mp4';

            return $this->streamVideoResponse($absolutePath, $mimeType);
        }

        $resolvedLocalPath = collect($candidates)
            ->unique()
            ->first(fn (string $path) => Storage::disk('local')->exists($path));

        if ($resolvedLocalPath) {
            $absolutePath = Storage::disk('local')->path($resolvedLocalPath);
            $mimeType = Storage::disk('local')->mimeType($resolvedLocalPath) ?? 'video/mp4';

            return $this->streamVideoResponse($absolutePath, $mimeType);
        }

        $absoluteCandidates = [
            $normalized,
            public_path(ltrim($normalized, '/')),
            public_path('storage/'.ltrim(Str::replaceFirst('storage/', '', $normalized), '/')),
            storage_path('app/public/'.ltrim(Str::replaceFirst('storage/', '', $normalized), '/')),
            storage_path('app/'.ltrim(Str::replaceFirst('public/', '', $normalized), '/')),
        ];

        $absolutePath = collect($absoluteCandidates)
            ->first(fn (string $path) => is_file($path));

        if (!$absolutePath) {
            abort(404);
        }

        return $this->streamVideoResponse($absolutePath, mime_content_type($absolutePath) ?: 'video/mp4');
    }

    /**
     * Return an HTTP response that supports byte range requests.
     */
    private function streamVideoResponse(string $absolutePath, string $mimeType)
    {
        $size = filesize($absolutePath);
        $start = 0;
        $end = $size - 1;
        $status = 200;

        $range = request()->header('Range');
        if ($range && preg_match('/bytes=(\d*)-(\d*)/i', $range, $matches)) {
            $rangeStart = $matches[1] === '' ? null : (int) $matches[1];
            $rangeEnd = $matches[2] === '' ? null : (int) $matches[2];

            if ($rangeStart === null && $rangeEnd !== null) {
                // Suffix-byte-range-spec: bytes=-500 (last 500 bytes)
                $suffixLength = min($rangeEnd, $size);
                $start = max(0, $size - $suffixLength);
                $end = $size - 1;
            } else {
                if ($rangeStart !== null) {
                    $start = $rangeStart;
                }

                if ($rangeEnd !== null) {
                    $end = min($rangeEnd, $end);
                }
            }

            if ($start > $end || $start >= $size) {
                return response('', 416, [
                    'Content-Range' => "bytes */{$size}",
                    'Accept-Ranges' => 'bytes',
                ]);
            }

            $status = 206;
        }

        $length = $end - $start + 1;

        $headers = [
            'Content-Type' => $mimeType,
            'Accept-Ranges' => 'bytes',
            'Content-Length' => (string) $length,
            'Cache-Control' => 'public, max-age=3600',
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
        ];

        if ($status === 206) {
            $headers['Content-Range'] = "bytes {$start}-{$end}/{$size}";
        }

        return response()->stream(function () use ($absolutePath, $start, $end) {
            $handle = fopen($absolutePath, 'rb');

            if (!$handle) {
                return;
            }

            try {
                fseek($handle, $start);
                $remaining = $end - $start + 1;
                $chunkSize = 1024 * 1024;

                while ($remaining > 0 && !feof($handle)) {
                    $readLength = min($chunkSize, $remaining);
                    $buffer = fread($handle, $readLength);

                    if ($buffer === false) {
                        break;
                    }

                    echo $buffer;
                    $remaining -= strlen($buffer);

                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();
                }
            } finally {
                fclose($handle);
            }
        }, $status, $headers);
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
            'video_file' => 'required|file|mimes:mp4,webm,ogg,avi,mov,wmv,flv,mkv|max:512000', // Max 500MB - formats étendus si conversion possible
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
            'duration' => 'nullable|integer|min:1',
            'lesson_id' => 'nullable|exists:lessons,id',
            'order' => 'nullable|integer|min:0',
        ]);

        // Handle video upload + normalize to web-compatible MP4 when possible
        $videoPath = $request->file('video_file')->store('videos', 'public');
        $originalPath = $videoPath;
        $videoPath = $this->normalizeVideoForWeb($videoPath);
        
        // Vérifier si la conversion a eu lieu
        $ffmpegAvailable = trim(shell_exec('which ffmpeg') ?: '') !== '';
        $wasConverted = $videoPath !== $originalPath;
        
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

        $message = 'Vidéo créée avec succès.';
        if (!$ffmpegAvailable) {
            $message .= ' Attention : FFmpeg n\'est pas installé. La vidéo pourrait ne pas être lisible dans tous les navigateurs. Installez FFmpeg pour une compatibilité optimale.';
        } elseif (!$wasConverted) {
            $message .= ' Note : La vidéo n\'a pas été convertie (déjà au bon format ou erreur de conversion).';
        }

        return redirect()->route('admin.videos.index')
            ->with('success', $message);
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
            $videoPath = $this->normalizeVideoForWeb($videoPath);
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
     * Reconversion des vidéos existantes (admin seulement)
     */
    public function reconvertVideos()
    {
        $this->authorize('admin');

        $videos = Video::all();
        $converted = 0;
        $errors = [];

        foreach ($videos as $video) {
            if (!$video->video_file) continue;

            $originalPath = $video->video_file;
            $newPath = $this->normalizeVideoForWeb($originalPath);

            if ($newPath !== $originalPath) {
                $video->update(['video_file' => $newPath]);
                $converted++;
            }
        }

        return redirect()->back()->with('success', "$converted vidéos reconverties avec succès.");
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
     * Convert uploaded video to a browser-friendly MP4 (H.264/AAC) when ffmpeg is available.
     */
    private function normalizeVideoForWeb(string $publicPath): string
    {
        $sourceAbsolutePath = Storage::disk('public')->path($publicPath);

        if (!is_file($sourceAbsolutePath)) {
            return $publicPath;
        }

        $ffmpegPath = trim(shell_exec('which ffmpeg') ?: '');
        if ($ffmpegPath === '') {
            \Log::warning('FFmpeg not found. Video conversion skipped for: ' . $publicPath);
            return $publicPath;
        }

        $pathInfo = pathinfo($publicPath);
        $targetRelativePath = trim(($pathInfo['dirname'] ?? 'videos').'/'.$pathInfo['filename'].'-web.mp4', '/');
        $targetAbsolutePath = Storage::disk('public')->path($targetRelativePath);

        // Ensure output directory exists
        File::ensureDirectoryExists(dirname($targetAbsolutePath));

        $command = sprintf(
            '%s -y -i %s -c:v libx264 -preset veryfast -crf 23 -c:a aac -b:a 128k -movflags +faststart %s 2>&1',
            escapeshellarg($ffmpegPath),
            escapeshellarg($sourceAbsolutePath),
            escapeshellarg($targetAbsolutePath)
        );

        shell_exec($command);

        if (!is_file($targetAbsolutePath) || filesize($targetAbsolutePath) <= 0) {
            return $publicPath;
        }

        // Replace original file with converted version to keep one storage path
        Storage::disk('public')->delete($publicPath);

        return $targetRelativePath;
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
