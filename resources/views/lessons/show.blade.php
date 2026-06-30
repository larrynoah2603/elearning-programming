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

                    <div class="mt-4 rounded-xl border border-secondary-100 bg-secondary-50/70 p-4 dark:border-slate-700 dark:bg-slate-800/70">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <p id="ai-read-status" class="text-xs text-gray-600 dark:text-slate-300" aria-live="polite">
                                Le bouton lit automatiquement le texte détecté dans le PDF avec une voix IA du navigateur.
                            </p>
                            <span id="ai-read-progress" class="hidden rounded-full bg-white px-3 py-1 text-xs font-semibold text-secondary-700 shadow-sm dark:bg-slate-900 dark:text-secondary-200">
                                Ligne 0/0
                            </span>
                        </div>
                        <div id="ai-read-lines" class="mt-3 hidden max-h-48 space-y-2 overflow-y-auto pr-1 text-sm" aria-live="polite" aria-label="Suivi de la lecture ligne par ligne"></div>
                    </div>
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

<button
    type="button"
    id="ai-floating-pause-btn"
    class="fixed bottom-6 right-6 z-50 hidden items-center gap-2 rounded-full bg-primary-600 px-5 py-3 text-sm font-bold text-white shadow-2xl shadow-primary-600/30 transition hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:shadow-black/40"
    aria-pressed="false"
>
    <i class="fas fa-pause" aria-hidden="true"></i>
    <span>Pause</span>
</button>
@endsection

@push('scripts')
    <script>
        (() => {
            const button = document.getElementById('ai-read-btn');
            const buttonLabel = document.getElementById('ai-read-btn-label');
            const status = document.getElementById('ai-read-status');
            const progress = document.getElementById('ai-read-progress');
            const linesContainer = document.getElementById('ai-read-lines');
            const floatingPauseButton = document.getElementById('ai-floating-pause-btn');

            if (!button || !buttonLabel || !status || !progress || !linesContainer || !floatingPauseButton) {
                return;
            }

            const synth = window.speechSynthesis;

            if (!synth) {
                button.disabled = true;
                button.classList.add('opacity-60', 'cursor-not-allowed');
                status.textContent = 'La lecture vocale IA n\'est pas prise en charge par ce navigateur.';

                return;
            }

            const pdfUrl = button.dataset.pdfUrl;
            const fallbackText = @json(trim($lesson->title . '. ' . $lesson->description));
            let extractedLines = null;
            let currentLineIndex = 0;
            let isReading = false;
            let isStopping = false;

            const loadPdfJs = () => new Promise((resolve, reject) => {
                if (window.pdfjsLib) {
                    resolve(window.pdfjsLib);
                    return;
                }

                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
                script.crossOrigin = 'anonymous';
                script.referrerPolicy = 'no-referrer';

                script.onload = () => {
                    if (!window.pdfjsLib) {
                        reject(new Error('pdfjs_not_loaded'));
                        return;
                    }

                    window.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                    resolve(window.pdfjsLib);
                };

                script.onerror = () => reject(new Error('pdfjs_load_error'));

                document.head.appendChild(script);
            });

            const setStatus = (message) => {
                status.textContent = message;
            };

            const splitIntoReadableLines = (text) => text
                .replace(/\s+/g, ' ')
                .split(/(?<=[.!?])\s+|\s+-\s+/)
                .map((line) => line.trim())
                .filter(Boolean)
                .slice(0, 80);

            const updateFloatingPauseButton = () => {
                const isPaused = synth.paused;
                floatingPauseButton.setAttribute('aria-pressed', isPaused ? 'true' : 'false');
                floatingPauseButton.querySelector('i').className = `fas ${isPaused ? 'fa-play' : 'fa-pause'}`;
                floatingPauseButton.querySelector('span').textContent = isPaused ? 'Reprendre' : 'Pause';
            };

            const setReadingUi = (active) => {
                isReading = active;
                buttonLabel.textContent = active ? 'Arrêter la lecture' : 'Lecture IA (bêta)';
                floatingPauseButton.classList.toggle('hidden', !active);
                floatingPauseButton.classList.toggle('flex', active);
                updateFloatingPauseButton();
            };

            const renderLines = (lines) => {
                linesContainer.innerHTML = '';
                lines.forEach((line, index) => {
                    const item = document.createElement('p');
                    item.id = `ai-read-line-${index}`;
                    item.className = 'rounded-lg border border-transparent bg-white/70 px-3 py-2 text-gray-600 transition dark:bg-slate-900/60 dark:text-slate-300';
                    item.textContent = line;
                    linesContainer.appendChild(item);
                });

                linesContainer.classList.remove('hidden');
                progress.classList.remove('hidden');
            };

            const highlightLine = (index) => {
                currentLineIndex = index;
                progress.textContent = `Ligne ${Math.min(index + 1, extractedLines.length)}/${extractedLines.length}`;

                linesContainer.querySelectorAll('p').forEach((item, itemIndex) => {
                    const isCurrent = itemIndex === index;
                    item.classList.toggle('border-primary-200', isCurrent);
                    item.classList.toggle('bg-primary-50', isCurrent);
                    item.classList.toggle('text-primary-900', isCurrent);
                    item.classList.toggle('font-semibold', isCurrent);
                    item.classList.toggle('shadow-sm', isCurrent);
                    item.classList.toggle('dark:border-primary-500/50', isCurrent);
                    item.classList.toggle('dark:bg-primary-500/10', isCurrent);
                    item.classList.toggle('dark:text-primary-100', isCurrent);
                });

                document.getElementById(`ai-read-line-${index}`)?.scrollIntoView({
                    block: 'nearest',
                    behavior: 'smooth',
                });
            };

            const resetTracking = () => {
                currentLineIndex = 0;
                progress.classList.add('hidden');
                linesContainer.classList.add('hidden');
                linesContainer.innerHTML = '';
            };

            const stopReading = () => {
                isStopping = true;
                synth.cancel();
                setReadingUi(false);
                resetTracking();
                setStatus('Lecture arrêtée. Cliquez pour relancer la lecture du PDF.');
            };

            const chooseVoice = () => {
                const voices = synth.getVoices();

                return voices.find((voice) => voice.lang.toLowerCase().startsWith('fr'))
                    || voices.find((voice) => voice.lang.toLowerCase().startsWith('en'))
                    || null;
            };

            const speakLine = (index) => {
                if (!extractedLines || index >= extractedLines.length) {
                    setReadingUi(false);
                    setStatus('Lecture terminée.');
                    return;
                }

                highlightLine(index);
                const utterance = new SpeechSynthesisUtterance(extractedLines[index]);
                utterance.lang = 'fr-FR';
                utterance.rate = 1;
                utterance.pitch = 1;

                const voice = chooseVoice();
                if (voice) {
                    utterance.voice = voice;
                }

                utterance.onstart = () => {
                    setReadingUi(true);
                    setStatus('Lecture en cours...');
                };

                utterance.onend = () => {
                    if (isStopping) {
                        isStopping = false;
                        return;
                    }

                    speakLine(index + 1);
                };

                utterance.onerror = () => {
                    setReadingUi(false);
                    setStatus('Une erreur est survenue pendant la lecture vocale.');
                };

                synth.speak(utterance);
            };

            const extractPdfLines = async () => {
                const pdfjs = await loadPdfJs();
                const loadingTask = pdfjs.getDocument({
                    url: pdfUrl,
                    withCredentials: true,
                });

                const pdf = await loadingTask.promise;
                const maxPages = Math.min(pdf.numPages, 12);
                const lines = [];

                for (let pageNumber = 1; pageNumber <= maxPages; pageNumber++) {
                    const page = await pdf.getPage(pageNumber);
                    const textContent = await page.getTextContent();
                    const pageText = textContent.items
                        .map((item) => item.str || '')
                        .join(' ')
                        .replace(/\s+/g, ' ')
                        .trim();

                    if (pageText.length > 0) {
                        lines.push(...splitIntoReadableLines(pageText));
                    }
                }

                if (lines.length === 0) {
                    throw new Error('empty_text');
                }

                return lines.slice(0, 80);
            };

            button.addEventListener('click', async () => {
                if (isReading || synth.speaking || synth.paused) {
                    stopReading();
                    return;
                }

                button.disabled = true;
                setStatus('Analyse du PDF en cours...');

                try {
                    if (!extractedLines) {
                        try {
                            extractedLines = await extractPdfLines();
                        } catch (error) {
                            console.error(error);
                            extractedLines = splitIntoReadableLines(fallbackText);
                            setStatus('Lecture du résumé en mode compatibilité (texte PDF non accessible).');
                        }
                    }

                    isStopping = false;
                    renderLines(extractedLines);
                    speakLine(0);
                } catch (error) {
                    console.error(error);
                    setStatus('Impossible de démarrer la lecture vocale.');
                } finally {
                    button.disabled = false;
                }
            });

            floatingPauseButton.addEventListener('click', () => {
                if (!isReading) {
                    return;
                }

                if (synth.paused) {
                    synth.resume();
                    setStatus('Lecture reprise.');
                } else {
                    synth.pause();
                    setStatus(`Lecture en pause à la ligne ${currentLineIndex + 1}.`);
                }

                updateFloatingPauseButton();
            });

            window.addEventListener('beforeunload', () => {
                if (synth.speaking || synth.paused) {
                    synth.cancel();
                }
            });
        })();
    </script>
@endpush
