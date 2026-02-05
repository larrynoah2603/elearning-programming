<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    /**
     * Display a listing of lessons.
     */
    public function index(Request $request)
    {
        $query = Lesson::active()->with('user');

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
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $lessons = $query->orderBy('order')->paginate(12);
        $categories = Category::active()->orderBy('order')->get();

        return view('lessons.index', compact('lessons', 'categories'));
    }

    
 /**
 * Display the specified lesson.
 */
public function show(string $slug)
{
    $lesson = Lesson::where('slug', $slug)
        ->with(['user', 'exercises' => function ($q) {
            $q->active()->orderBy('order');
        }, 'videos' => function ($q) {
            $q->active()->orderBy('order');
        }])
        ->firstOrFail();

    // MODIFIEZ cette vérification :
    // Check access - permettre la visualisation même pour les premium
    if (!$lesson->isAccessibleBy(auth()->user())) {
        // Au lieu de rediriger, montrez la leçon en mode "prévisualisation"
        // ou avec un message d'avertissement
        return view('lessons.show', compact('lesson'))
            ->with('warning', 'Cette leçon nécessite un abonnement. Vous pouvez la prévisualiser mais le téléchargement est réservé aux membres Premium.');
    }

    return view('lessons.show', compact('lesson'));
}

    /**
     * Show the form for creating a new lesson.
     */
    public function create()
    {
        $categories = Category::active()->orderBy('order')->get();
        return view('admin.lessons.create', compact('categories'));
    }

    /**
     * Store a newly created lesson.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'level' => 'required|in:debutant,intermediaire,avance',
            'access_level' => 'required|in:free,subscribed',
            'pdf_file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Handle PDF upload
        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('lessons', 'public');
            $validated['pdf_file'] = $pdfPath;
        }

        // Set default values
        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Create lesson
        $lesson = Lesson::create($validated);

        // Attach categories
        if (!empty($validated['categories'])) {
            $lesson->categories()->attach($validated['categories']);
        }

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Leçon créée avec succès.');
    }

    /**
     * Display lessons list for admin.
     */
    public function adminIndex(Request $request)
    {
        $query = Lesson::with('user');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $lessons = $query->latest()->paginate(20);

        return view('admin.lessons.index', compact('lessons'));
    }

    /**
     * Show the form for editing the lesson.
     */
    public function edit(Lesson $lesson)
    {
        $categories = Category::active()->orderBy('order')->get();
        $lessonCategories = $lesson->categories->pluck('id')->toArray();

        return view('admin.lessons.edit', compact('lesson', 'categories', 'lessonCategories'));
    }

    /**
     * Update the lesson.
     */
    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'level' => 'required|in:debutant,intermediaire,avance',
            'access_level' => 'required|in:free,subscribed',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Handle PDF upload
        if ($request->hasFile('pdf_file')) {
            // Delete old PDF
            if ($lesson->pdf_file && Storage::disk('public')->exists($lesson->pdf_file)) {
                Storage::disk('public')->delete($lesson->pdf_file);
            }

            $pdfPath = $request->file('pdf_file')->store('lessons', 'public');
            $validated['pdf_file'] = $pdfPath;
        } else {
            unset($validated['pdf_file']);
        }

        // Update slug if title changed
        if ($lesson->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        // Update lesson
        $lesson->update($validated);

        // Sync categories
        if (isset($validated['categories'])) {
            $lesson->categories()->sync($validated['categories']);
        } else {
            $lesson->categories()->detach();
        }

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Leçon mise à jour avec succès.');
    }

    /**
     * Remove the lesson.
     */
    public function destroy(Lesson $lesson)
    {
        // Delete PDF file
        if ($lesson->pdf_file && Storage::disk('public')->exists($lesson->pdf_file)) {
            Storage::disk('public')->delete($lesson->pdf_file);
        }

        $lesson->delete();

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Leçon supprimée avec succès.');
    }

    /**
     * Toggle lesson active status.
     */
    public function toggleActive(Lesson $lesson)
    {
        $lesson->update(['is_active' => !$lesson->is_active]);

        $status = $lesson->is_active ? 'activée' : 'désactivée';

        return back()->with('success', "Leçon {$status} avec succès.");
    }

    /**
     * Download lesson PDF.
     */
    public function download(Lesson $lesson)
    {
        // Check access
        if (!$lesson->isAccessibleBy(auth()->user())) {
            return redirect()->route('subscription.plans')
                ->with('info', 'Cette leçon nécessite un abonnement. Découvrez nos offres !');
        }

        if (!$lesson->pdf_file || !Storage::disk('public')->exists($lesson->pdf_file)) {
            return back()->with('error', 'Le fichier PDF n\'est pas disponible.');
        }

        return Storage::disk('public')->download($lesson->pdf_file, $lesson->slug . '.pdf');
    }
}
