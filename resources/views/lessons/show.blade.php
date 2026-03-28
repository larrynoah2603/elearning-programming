@extends('layouts.app')

@section('title', $lesson->title . ' - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-primary-600">Accueil</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <a href="{{ route('lessons.index') }}" class="hover:text-primary-600">Leçons</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900">{{ $lesson->title }}</span>
        </nav>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Lesson Header -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="badge badge-{{ $lesson->level_badge_color }}">
                                    {{ $lesson->level_display }}
                                </span>
                                @if($lesson->access_level == 'subscribed')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-crown mr-1"></i> Premium
                                    </span>
                                @endif
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $lesson->title }}</h1>
                        </div>
                    </div>
                    
                    <p class="text-gray-600">{{ $lesson->description }}</p>

                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <a href="{{ route('lessons.download', $lesson) }}" class="btn btn-primary">
                            <i class="fas fa-download mr-2"></i> Télécharger le PDF
                        </a>
                        <button
                            type="button"
                            id="ai-read-btn"
                            data-pdf-url="{{ route('lessons.preview', $lesson) }}"
                            class="btn bg-secondary-100 text-secondary-800 hover:bg-secondary-200 focus:ring-2 focus:ring-secondary-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600"
                        >
                            <i class="fas fa-volume-high mr-2"></i>
                            <span id="ai-read-btn-label">Lecture IA (bêta)</span>
                        </button>
                        @if($lesson->page_count)
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-file-alt mr-1"></i> {{ $lesson->page_count }} pages
                            </span>
                        @endif
                    </div>

                    <p id="ai-read-status" class="mt-3 text-xs text-gray-500 dark:text-slate-400" aria-live="polite">
                        Le bouton lit automatiquement le texte détecté dans le PDF avec une voix IA du navigateur.
                    </p>
                </div>

                <!-- PDF Viewer -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-book-open mr-2 text-primary-500"></i> Contenu de la leçon
                    </h2>
                    <div class="bg-gray-100 rounded-lg overflow-hidden" style="height: 75vh; min-height: 520px;">
                        <iframe
                            src="{{ route('lessons.preview', $lesson) }}"
                            class="w-full h-full"
                            title="Lecture du PDF {{ $lesson->title }}"
                            loading="lazy">
                        </iframe>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">
                        Si la prévisualisation ne s'affiche pas, utilisez le bouton de téléchargement ci-dessus.
                    </p>
                </div>

                <!-- Exercises -->
                @if($lesson->exercises->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">
                            <i class="fas fa-laptop-code mr-2 text-primary-500"></i> Exercices associés
                        </h2>
                        <div class="space-y-3">
                            @foreach($lesson->exercises as $exercise)
                                <a href="{{ route('exercises.show', $exercise->slug) }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $exercise->title }}</div>
                                        <div class="text-sm text-gray-500 mt-1">
                                            <span class="badge badge-{{ $exercise->difficulty_badge_color }} text-xs">
                                                {{ $exercise->difficulty_display }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-primary-600">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Lesson Info -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Informations</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Niveau</span>
                            <span class="font-medium">{{ $lesson->level_display }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Accès</span>
                            <span class="font-medium">{{ $lesson->access_level_display }}</span>
                        </div>
                        @if($lesson->page_count)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Pages</span>
                                <span class="font-medium">{{ $lesson->page_count }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-500">Exercices</span>
                            <span class="font-medium">{{ $lesson->exercises_count }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Vidéos</span>
                            <span class="font-medium">{{ $lesson->videos_count }}</span>
                        </div>
                    </div>
                </div>

                <!-- Videos -->
                @if($lesson->videos->count() > 0 && auth()->user()?->isSubscribed())
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-4">
                            <i class="fas fa-video mr-2 text-secondary-500"></i> Vidéos
                        </h3>
                        <div class="space-y-3">
                            @foreach($lesson->videos as $video)
                                <a href="{{ route('videos.show', $video->slug) }}" class="block">
                                    <div class="relative aspect-video bg-gray-900 rounded-lg overflow-hidden mb-2">
                                        @if($video->thumbnail)
                                            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                                        @endif
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                                                <i class="fas fa-play text-white"></i>
                                            </div>
                                        </div>
                                        @if($video->duration)
                                            <div class="absolute bottom-2 right-2 bg-black/70 text-white text-xs px-2 py-1 rounded">
                                                {{ $video->duration_display }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="font-medium text-gray-900 text-sm">{{ $video->title }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Author -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Auteur</h3>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($lesson->user->name, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <div class="font-medium text-gray-900">{{ $lesson->user->name }}</div>
                            <div class="text-sm text-gray-500">Formateur</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js" integrity="sha512-Q0zjQfduV91vP5+qY8f4v8f7Q27eVcHSt5lI+8qJ9z4fL7i6lJej8P35h8aEtf1NRb8dPYvP/Jk4fksMvD5fXw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        (() => {
            const button = document.getElementById('ai-read-btn');
            const buttonLabel = document.getElementById('ai-read-btn-label');
            const status = document.getElementById('ai-read-status');

            if (!button || !buttonLabel || !status) {
                return;
            }

            const synth = window.speechSynthesis;

            if (!synth || !window.pdfjsLib) {
                button.disabled = true;
                button.classList.add('opacity-60', 'cursor-not-allowed');
                status.textContent = 'La lecture vocale IA n\'est pas prise en charge par ce navigateur.';

                return;
            }

            const pdfjs = window.pdfjsLib;
            pdfjs.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

            const pdfUrl = button.dataset.pdfUrl;
            let extractedText = null;

            const setStatus = (message) => {
                status.textContent = message;
            };

            const stopReading = () => {
                synth.cancel();
                buttonLabel.textContent = 'Lecture IA (bêta)';
                setStatus('Lecture arrêtée. Cliquez pour relancer la lecture du PDF.');
            };

            const chooseVoice = () => {
                const voices = synth.getVoices();

                return voices.find((voice) => voice.lang.toLowerCase().startsWith('fr'))
                    || voices.find((voice) => voice.lang.toLowerCase().startsWith('en'))
                    || null;
            };

            const readText = (text) => {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'fr-FR';
                utterance.rate = 1;
                utterance.pitch = 1;

                const voice = chooseVoice();
                if (voice) {
                    utterance.voice = voice;
                }

                utterance.onstart = () => {
                    buttonLabel.textContent = 'Arrêter la lecture';
                    setStatus('Lecture en cours...');
                };

                utterance.onend = () => {
                    buttonLabel.textContent = 'Lecture IA (bêta)';
                    setStatus('Lecture terminée.');
                };

                utterance.onerror = () => {
                    buttonLabel.textContent = 'Lecture IA (bêta)';
                    setStatus('Une erreur est survenue pendant la lecture vocale.');
                };

                synth.speak(utterance);
            };

            const extractPdfText = async () => {
                const loadingTask = pdfjs.getDocument({
                    url: pdfUrl,
                    withCredentials: true,
                });

                const pdf = await loadingTask.promise;
                const maxPages = Math.min(pdf.numPages, 12);
                const chunks = [];

                for (let pageNumber = 1; pageNumber <= maxPages; pageNumber++) {
                    const page = await pdf.getPage(pageNumber);
                    const textContent = await page.getTextContent();
                    const pageText = textContent.items
                        .map((item) => item.str || '')
                        .join(' ')
                        .replace(/\s+/g, ' ')
                        .trim();

                    if (pageText.length > 0) {
                        chunks.push(pageText);
                    }
                }

                const joined = chunks.join(' ').trim();

                if (!joined) {
                    throw new Error('empty_text');
                }

                return joined.slice(0, 14000);
            };

            button.addEventListener('click', async () => {
                if (synth.speaking) {
                    stopReading();
                    return;
                }

                button.disabled = true;
                setStatus('Analyse du PDF en cours...');

                try {
                    if (!extractedText) {
                        extractedText = await extractPdfText();
                    }

                    readText(extractedText);
                } catch (error) {
                    console.error(error);
                    setStatus('Impossible d\'extraire le texte du PDF. Téléchargez le fichier pour une lecture manuelle.');
                } finally {
                    button.disabled = false;
                }
            });

            window.addEventListener('beforeunload', () => {
                if (synth.speaking) {
                    synth.cancel();
                }
            });
        })();
    </script>
@endpush
