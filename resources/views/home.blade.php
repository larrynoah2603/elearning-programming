@extends('layouts.app')

@section('title', 'Accueil - CodeLearn')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-secondary-800 text-white overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 relative">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="animate-fade-in">
                <div class="inline-flex items-center px-4 py-2 bg-white/10 rounded-full text-sm font-medium mb-6 backdrop-blur-sm">
                    <i class="fas fa-rocket mr-2"></i>
                    Apprenez à coder gratuitement
                </div>
                <h1 class="text-4xl lg:text-6xl font-bold leading-tight mb-6">
                    Maîtrisez la <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-400">programmation</span> étape par étape
                </h1>
                <p class="text-lg lg:text-xl text-primary-100 mb-8 leading-relaxed">
                    Des cours interactifs, des exercices pratiques et des vidéos explicatives pour apprendre Python, JavaScript, Java et bien plus encore.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('exercises.index') }}" class="btn btn-primary bg-white text-primary-700 hover:bg-gray-100 px-8 py-4 text-lg">
                        <i class="fas fa-play mr-2"></i> Commencer gratuitement
                    </a>
                    <a href="{{ route('subscription.plans') }}" class="btn border-2 border-white text-white hover:bg-white/10 px-8 py-4 text-lg">
                        <i class="fas fa-crown mr-2"></i> Voir les offres Premium
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-8 mt-12 pt-8 border-t border-white/20">
                    <div>
                        <div class="text-3xl font-bold">{{ $stats['lessons'] ?? '50+' }}</div>
                        <div class="text-primary-200 text-sm">Leçons</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold">{{ $stats['exercises'] ?? '200+' }}</div>
                        <div class="text-primary-200 text-sm">Exercices</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold">{{ $stats['videos'] ?? '100+' }}</div>
                        <div class="text-primary-200 text-sm">Vidéos</div>
                    </div>
                </div>
            </div>
            
            <div class="hidden lg:block relative">
                <div class="relative z-10 bg-gray-900 rounded-xl shadow-2xl overflow-hidden border border-gray-700">
                    <div class="flex items-center px-4 py-3 bg-gray-800 border-b border-gray-700">
                        <div class="flex space-x-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <div class="ml-4 text-sm text-gray-400">main.py</div>
                    </div>
                    <div class="p-6 font-mono text-sm">
                        <div class="text-purple-400">def <span class="text-yellow-300">calculer_moyenne</span>(notes):</div>
                        <div class="pl-4 text-gray-300">"""Calcule la moyenne d'une liste de notes"""</div>
                        <div class="pl-4 text-gray-300">total = <span class="text-orange-400">sum</span>(notes)</div>
                        <div class="pl-4 text-gray-300">moyenne = total / <span class="text-orange-400">len</span>(notes)</div>
                        <div class="pl-4 text-purple-400">return <span class="text-green-400">round</span>(moyenne, <span class="text-orange-400">2</span>)</div>
                        <div class="mt-4 text-gray-300">notes = [<span class="text-orange-400">15</span>, <span class="text-orange-400">18</span>, <span class="text-orange-400">12</span>, <span class="text-orange-400">16</span>]</div>
                        <div class="text-gray-300">resultat = <span class="text-yellow-300">calculer_moyenne</span>(notes)</div>
                        <div class="text-purple-400">print</div>
                        <div class="text-gray-300">(<span class="text-green-400">f"Moyenne: <span class="text-blue-400">{resultat}</span>/20"</span>)</div>
                        <div class="mt-4 text-green-400"># Output: Moyenne: 15.25/20</div>
                    </div>
                </div>
                
                <!-- Floating elements -->
                <div class="absolute -top-4 -right-4 bg-white rounded-lg shadow-lg p-4 animate-bounce" style="animation-duration: 3s;">
                    <i class="fab fa-python text-4xl text-yellow-500"></i>
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white rounded-lg shadow-lg p-4 animate-bounce" style="animation-duration: 4s;">
                    <i class="fab fa-js text-4xl text-yellow-400"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Pourquoi choisir CodeLearn ?</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Une plateforme complète pour apprendre la programmation, du débutant à l'expert.</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="card-hover bg-gray-50 rounded-xl p-8">
                <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-book-open text-2xl text-primary-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Leçons PDF</h3>
                <p class="text-gray-600">Des cours complets et structurés en PDF, téléchargeables pour un apprentissage hors ligne.</p>
            </div>
            
            <div class="card-hover bg-gray-50 rounded-xl p-8">
                <div class="w-14 h-14 bg-secondary-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-laptop-code text-2xl text-secondary-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Exercices Pratiques</h3>
                <p class="text-gray-600">Des exercices de difficulté croissante pour mettre en pratique vos connaissances.</p>
            </div>
            
            <div class="card-hover bg-gray-50 rounded-xl p-8">
                <div class="w-14 h-14 bg-success-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-video text-2xl text-success-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Vidéos Explicatives</h3>
                <p class="text-gray-600">Des vidéos pédagogiques pour visualiser les concepts et suivre les tutoriels pas à pas.</p>
            </div>
            
            <div class="card-hover bg-gray-50 rounded-xl p-8">
                <div class="w-14 h-14 bg-warning-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-trophy text-2xl text-warning-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Système de Points</h3>
                <p class="text-gray-600">Gagnez des points en complétant les exercices et grimpez dans le classement.</p>
            </div>
            
            <div class="card-hover bg-gray-50 rounded-xl p-8">
                <div class="w-14 h-14 bg-danger-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-certificate text-2xl text-danger-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Certificats</h3>
                <p class="text-gray-600">Obtenez des certificats de réussite pour valoriser vos compétences.</p>
            </div>
            
            <div class="card-hover bg-gray-50 rounded-xl p-8">
                <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-users text-2xl text-primary-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Communauté</h3>
                <p class="text-gray-600">Rejoignez une communauté d'apprenants et échangez avec d'autres étudiants.</p>
            </div>
        </div>
    </div>
</section>

<!-- Free Content Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12">
            <div>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Contenu Gratuit</h2>
                <p class="text-lg text-gray-600">Commencez votre apprentissage avec nos ressources gratuites.</p>
            </div>
            <a href="{{ route('exercises.index') }}" class="mt-4 lg:mt-0 text-primary-600 hover:text-primary-700 font-medium flex items-center">
                Voir tout le contenu <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        
        <!-- Featured Exercises -->
        <div class="mb-12">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Exercices Populaires</h3>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($featuredExercises ?? [] as $exercise)
                    <div class="card-hover bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="badge badge-{{ $exercise->difficulty_badge_color }}">
                                    {{ $exercise->difficulty_display }}
                                </span>
                                <i class="fab fa-{{ $exercise->programming_language }} text-2xl text-gray-400"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2">{{ $exercise->title }}</h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($exercise->description, 100) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i> {{ $exercise->points }} pts
                                </span>
                                <a href="{{ route('exercises.show', $exercise->slug) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    Commencer <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-code text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Aucun exercice disponible pour le moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Featured Lessons -->
        <div>
            <h3 class="text-xl font-bold text-gray-900 mb-6">Leçons Recommandées</h3>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($featuredLessons ?? [] as $lesson)
                    <div class="card-hover bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="h-40 bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center">
                            <i class="fas fa-file-pdf text-5xl text-white/50"></i>
                        </div>
                        <div class="p-6">
                            <span class="badge badge-{{ $lesson->level_badge_color }} mb-3">
                                {{ $lesson->level_display }}
                            </span>
                            <h4 class="font-bold text-gray-900 mb-2">{{ $lesson->title }}</h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($lesson->description, 100) }}</p>
                            <a href="{{ route('lessons.show', $lesson->slug) }}" class="btn btn-primary w-full">
                                <i class="fas fa-book-reader mr-2"></i> Lire la leçon
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-book text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Aucune leçon disponible pour le moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<!-- Premium CTA Section -->
<section class="py-20 bg-gradient-to-br from-secondary-600 to-primary-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-3xl mx-auto">
            <i class="fas fa-crown text-6xl text-yellow-400 mb-6"></i>
            <h2 class="text-3xl lg:text-5xl font-bold mb-6">Passez à la version Premium</h2>
            <p class="text-xl text-white/90 mb-8">
                Accédez à tout le contenu : exercices complexes, leçons PDF, vidéos exclusives et bien plus encore.
            </p>
            
            <div class="grid sm:grid-cols-3 gap-6 mb-10">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                    <div class="text-4xl font-bold mb-2">500+</div>
                    <div class="text-white/80">Exercices</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                    <div class="text-4xl font-bold mb-2">100+</div>
                    <div class="text-white/80">Vidéos</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                    <div class="text-4xl font-bold mb-2">50+</div>
                    <div class="text-white/80">Leçons PDF</div>
                </div>
            </div>
            
            <a href="{{ route('subscription.plans') }}" class="btn bg-white text-primary-700 hover:bg-gray-100 px-10 py-4 text-lg">
                <i class="fas fa-crown mr-2"></i> Découvrir les offres
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Explorez par Catégorie</h2>
            <p class="text-lg text-gray-600">Choisissez votre langage de programmation et commencez à apprendre.</p>
        </div>
        
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($categories ?? [] as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="card-hover group bg-gray-50 rounded-xl p-6 flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center group-hover:shadow-md transition-shadow">
                        <i class="fas {{ $category->icon ?? 'fa-code' }} text-3xl text-primary-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 group-hover:text-primary-600 transition-colors">{{ $category->name }}</h3>
                        <p class="text-gray-500 text-sm">{{ $category->lessons_count }} leçons</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Aucune catégorie disponible pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Ce que disent nos élèves</h2>
            <p class="text-lg text-gray-600">Découvrez les témoignages de nos apprenants.</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-sm p-8">
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                    </div>
                </div>
                <p class="text-gray-600 mb-6">"CodeLearn m'a permis de découvrir la programmation de manière ludique. Les exercices sont bien structurés et les vidéos très pédagogiques."</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary-500 rounded-full flex items-center justify-center text-white font-bold">ML</div>
                    <div class="ml-4">
                        <div class="font-bold text-gray-900">Marie L.</div>
                        <div class="text-gray-500 text-sm">Lycéenne, Terminale S</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-8">
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                    </div>
                </div>
                <p class="text-gray-600 mb-6">"Grâce à la version Premium, j'ai pu accéder à des exercices plus complexes qui m'ont vraiment fait progresser. Je recommande !"</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-secondary-500 rounded-full flex items-center justify-center text-white font-bold">TD</div>
                    <div class="ml-4">
                        <div class="font-bold text-gray-900">Thomas D.</div>
                        <div class="text-gray-500 text-sm">Étudiant en informatique</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-8">
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                    </div>
                </div>
                <p class="text-gray-600 mb-6">"En tant que professeur, j'utilise CodeLearn pour compléter mes cours. La plateforme est parfaitement adaptée au programme de lycée."</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-success-500 rounded-full flex items-center justify-center text-white font-bold">PM</div>
                    <div class="ml-4">
                        <div class="font-bold text-gray-900">Pierre M.</div>
                        <div class="text-gray-500 text-sm">Professeur de NSI</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
