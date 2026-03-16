@extends('layouts.app')

@section('title', 'Félicitations 🎉 - Formation Complétée')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-50 via-secondary-50 to-indigo-50 pt-20 pb-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Conteneur principal avec animation -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            
            <!-- Sections confettis/celebrations -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="confetti" style="left: 10%; animation-delay: 0s;"></div>
                <div class="confetti" style="left: 20%; animation-delay: 0.2s;"></div>
                <div class="confetti" style="left: 30%; animation-delay: 0.4s;"></div>
                <div class="confetti" style="left: 40%; animation-delay: 0.6s;"></div>
                <div class="confetti" style="left: 50%; animation-delay: 0.8s;"></div>
                <div class="confetti" style="left: 60%; animation-delay: 1s;"></div>
                <div class="confetti" style="left: 70%; animation-delay: 1.2s;"></div>
                <div class="confetti" style="left: 80%; animation-delay: 1.4s;"></div>
                <div class="confetti" style="left: 90%; animation-delay: 1.6s;"></div>
            </div>
            
            <!-- Contenu -->
            <div class="relative z-10 px-6 py-20 sm:px-12 sm:py-24 text-center">
                
                <!-- Icône de succès animée -->
                <div class="mb-8 inline-block">
                    <div class="relative h-24 w-24 mx-auto">
                        <div class="absolute inset-0 bg-gradient-to-br from-success-400 to-success-600 rounded-full animate-pulse"></div>
                        <div class="relative bg-gradient-to-br from-success-500 to-success-600 h-24 w-24 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-5xl drop-shadow-md"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Titre principal -->
                <h1 class="text-5xl sm:text-6xl font-bold text-gray-900 mb-4 tracking-tight">
                    Félicitations !
                </h1>
                
                <!-- Sous-titre -->
                <p class="text-xl sm:text-2xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    Vous avez <span class="font-bold text-primary-600">brillamment complété</span> la formation
                    <span class="text-success-600">{{ $formation->title }}</span>
                </p>
                
                <!-- Stats de complétion -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 my-12 bg-gradient-to-r from-primary-50 to-secondary-50 rounded-xl p-8">
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-primary-600 mb-2">
                            {{ $stats['quiz_score'] ?? 92 }}%
                        </div>
                        <p class="text-sm text-gray-600">Note moyenne<br>aux quizzes</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-secondary-600 mb-2">
                            {{ $stats['exercises_completed'] ?? 18 }}/18
                        </div>
                        <p class="text-sm text-gray-600">Exercices<br>complétés</p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-success-600 mb-2">
                            100%
                        </div>
                        <p class="text-sm text-gray-600">Progression<br>complète</p>
                    </div>
                </div>
                
                <!-- Message de validation du projet -->
                @if($projectApproved)
                    <div class="bg-success-50 border-l-4 border-success-500 p-4 mb-8 rounded-r-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-star text-success-600 text-2xl"></i>
                            </div>
                            <div class="ml-4 text-left">
                                <h3 class="text-lg font-medium text-success-800 mb-1">
                                    Projet Final Validé ✓
                                </h3>
                                <p class="text-success-700 text-sm">
                                    Votre projet final a reçu une excellente évaluation.
                                    Vous dépassez les standards d'excellence de CodeLearn.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Section du certificat -->
                <div class="bg-gradient-to-br from-primary-500 to-secondary-600 rounded-xl p-8 text-white mb-10 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-certificate text-4xl mr-4"></i>
                            <div class="text-left">
                                <h2 class="text-2xl font-bold">Votre Certificat est Prêt</h2>
                                <p class="text-white/80 text-sm">Numéro: {{ $certificate->certificate_number }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white/10 rounded-lg p-6 mb-6 backdrop-blur-sm border border-white/20">
                        <p class="text-sm text-white/90 leading-relaxed">
                            <strong>Ce certificat</strong> authentifie que vous avez complété avec succès tous les éléments 
                            requis de la formation "{{ $formation->title }}". Il est valide à vie et peut être partagé 
                            sur les réseaux professionnels (LinkedIn, GitHub, etc.).
                        </p>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('certificates.download', $certificate->id) }}" 
                           class="inline-flex items-center justify-center bg-white text-primary-600 hover:bg-gray-50 font-semibold px-8 py-3 rounded-lg transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <i class="fas fa-download mr-2"></i>
                            Télécharger le PDF
                        </a>
                        <a href="{{ route('certificates.share', $certificate->id) }}" 
                           class="inline-flex items-center justify-center bg-white/20 text-white hover:bg-white/30 font-semibold px-8 py-3 rounded-lg transition-all duration-300 border border-white/50 hover:border-white">
                            <i class="fas fa-share-alt mr-2"></i>
                            Partager
                        </a>
                    </div>
                </div>
                
                <!-- Section QR code pour partage -->
                <div class="bg-gray-50 rounded-xl p-6 mb-10">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Partager votre succès
                    </h3>
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="flex-1">
                            <p class="text-gray-600 text-sm mb-4">
                                Scannez ce code QR pour vérifier et partager votre certificat :
                            </p>
                            <div class="bg-white p-4 rounded-lg inline-block border-2 border-gray-200">
                                <img src="{{ $certificate->metadata['qr_code_url'] ?? '#' }}" 
                                     alt="QR Code du certificat" 
                                     class="w-32 h-32">
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 mb-3">Partagez sur :</h4>
                            <div class="flex gap-3 flex-wrap justify-center sm:justify-start">
                                <a href="https://www.linkedin.com/feed/" 
                                   target="_blank"
                                   class="inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet" 
                                   target="_blank"
                                   class="inline-flex items-center justify-center w-12 h-12 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://github.com" 
                                   target="_blank"
                                   class="inline-flex items-center justify-center w-12 h-12 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors">
                                    <i class="fab fa-github"></i>
                                </a>
                                <a href="mailto:?subject=Je viens de compléter une formation CodeLearn&body=Découvrez mon certificat..." 
                                   class="inline-flex items-center justify-center w-12 h-12 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Prochaines étapes -->
                <div class="bg-indigo-50 border-l-4 border-indigo-500 p-6 rounded-r-lg mb-10">
                    <h3 class="text-lg font-semibold text-indigo-900 mb-3">
                        <i class="fas fa-lightbulb mr-2"></i> Prochaines étapes ?
                    </h3>
                    <ul class="text-indigo-800 text-sm space-y-2 text-left max-w-2xl mx-auto">
                        <li class="flex items-start">
                            <i class="fas fa-check text-indigo-600 mr-3 mt-1 flex-shrink-0"></i>
                            <span><strong>Explorez d'autres formations :</strong> Continuez votre apprentissage avec nos autres cursus</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-indigo-600 mr-3 mt-1 flex-shrink-0"></i>
                            <span><strong>Mentorat :</strong> Accédez à des sessions de mentorat 1-on-1 (Premium)</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-indigo-600 mr-3 mt-1 flex-shrink-0"></i>
                            <span><strong>Communauté :</strong> Rejoignez notre forum pour partager et apprendre des autres</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Boutons d'action finaux -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('formations.index') }}" 
                       class="inline-flex items-center justify-center bg-primary-600 text-white hover:bg-primary-700 font-semibold px-8 py-3 rounded-lg transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Découvrir d'autres formations
                    </a>
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center justify-center bg-gray-200 text-gray-900 hover:bg-gray-300 font-semibold px-8 py-3 rounded-lg transition-all duration-300">
                        <i class="fas fa-home mr-2"></i>
                        Retourner au tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles pour les confettis -->
<style>
    @keyframes confetti-fall {
        0% {
            transform: translateY(-10vh) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }
    
    .confetti {
        position: fixed;
        width: 10px;
        height: 10px;
        top: -10px;
        background: linear-gradient(45deg, #667eea, #764ba2, #f093fb, #4facfe);
        border-radius: 50%;
        animation: confetti-fall 3s linear infinite;
        pointer-events: none;
        z-index: -1;
    }
    
    .confetti:nth-child(2) {
        width: 15px;
        height: 8px;
        background: linear-gradient(45deg, #fa709a, #fee140);
    }
    
    .confetti:nth-child(3) {
        width: 8px;
        height: 15px;
        background: linear-gradient(45deg, #30cfd0, #330867);
    }
    
    .confetti:nth-child(4) {
        width: 12px;
        height: 12px;
        background: linear-gradient(45deg, #a8edea, #fed6e3);
    }
    
    .confetti:nth-child(5) {
        width: 20px;
        height: 8px;
        background: linear-gradient(45deg, #ff9a56, #ff6a88);
    }
</style>

@endsection
