<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::active()
            ->withCount(['lessons' => function ($q) {
                $q->active();
            }])
            ->orderBy('order')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Display the specified category.
     */
/**
 * Display the specified category.
 */
public function show(Request $request, string $slug)
{
    $category = Category::where('slug', $slug)
        ->firstOrFail();

    // Récupérer les leçons de cette catégorie avec filtrage
    $query = $category->lessons()
        ->active()
        ->withCount(['exercises', 'videos'])
        ->orderBy('order');

    // Filtre par recherche
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Filtre par niveau
    if ($request->has('level') && $request->level !== 'all') {
        $query->where('level', $request->level);
    }

    $lessons = $query->get();

    return view('categories.show', compact('category', 'lessons'));
}

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Display categories list for admin.
     */
    public function adminIndex()
    {
        $categories = Category::withCount('lessons')
            ->orderBy('order')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for editing the category.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the category.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Update slug if name changed
        if ($category->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Remove the category.
     */
    public function destroy(Category $category)
    {
        // Detach all lessons before deleting
        $category->lessons()->detach();
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }

    /**
     * Toggle category active status.
     */
    public function toggleActive(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'activée' : 'désactivée';

        return back()->with('success', "Catégorie {$status} avec succès.");
    }
}
