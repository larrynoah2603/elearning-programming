<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Video;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        $stats = [
            'lessons' => Lesson::active()->count(),
            'exercises' => Exercise::active()->count(),
            'videos' => Video::active()->count(),
            'categories' => Category::active()->count(),
        ];

        $featuredLessons = Lesson::active()
            ->where('access_level', 'free')
            ->orderBy('order')
            ->take(3)
            ->get();

        $featuredExercises = Exercise::active()
            ->where('access_level', 'free')
            ->orderBy('order')
            ->take(4)
            ->get();

        $categories = Category::active()
            ->orderBy('order')
            ->take(6)
            ->get();

        return view('home', compact('stats', 'featuredLessons', 'featuredExercises', 'categories'));
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission.
     */
    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Here you would typically send an email
        // Mail::to(config('mail.admin_address'))->send(new ContactFormMail($validated));

        return back()->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
    }

    /**
     * Display pricing/plans page.
     */
    public function plans()
    {
        return view('plans');
    }

    /**
     * Search functionality.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $lessons = Lesson::active()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->select('id', 'title', 'slug')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'type' => 'Leçon',
                    'url' => route('lessons.show', $item->slug),
                ];
            });

        $exercises = Exercise::active()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->select('id', 'title', 'slug')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'type' => 'Exercice',
                    'url' => route('exercises.show', $item->slug),
                ];
            });

        $videos = Video::active()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->select('id', 'title', 'slug')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'type' => 'Vidéo',
                    'url' => route('videos.show', $item->slug),
                ];
            });

        $results = $lessons->merge($exercises)->merge($videos);

        return response()->json($results);
    }
}
