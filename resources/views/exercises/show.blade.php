@extends('layouts.app')

@section('title', $exercise->title . ' - CodeLearn')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-primary-600">Accueil</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <a href="{{ route('exercises.index') }}" class="hover:text-primary-600">Exercices</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900">{{ $exercise->title }}</span>
        </nav>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Exercise Header -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="badge badge-{{ $exercise->difficulty_badge_color }}">
                                    {{ $exercise->difficulty_display }}
                                </span>
                                <span class="badge badge-info">
                                    <i class="fab fa-{{ $exercise->programming_language }} mr-1"></i>
                                    {{ $exercise->programming_language_display }}
                                </span>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $exercise->title }}</h1>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-primary-600">{{ $exercise->points }} pts</div>
                            @if($exercise->estimated_time)
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i> {{ $exercise->estimated_time_display }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <p class="text-gray-600">{{ $exercise->description }}</p>
                </div>

                <!-- Instructions -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-list-ul mr-2 text-primary-500"></i> Instructions
                    </h2>
                    <div class="prose max-w-none text-gray-600">
                        {!! nl2br(e($exercise->instructions)) !!}
                    </div>

                    @if($exercise->hints)
                        <div class="mt-4">
                            <button
                                type="button"
                                onclick="document.getElementById('hints').classList.toggle('hidden')"
                                class="btn bg-warning-100 text-warning-700 hover:bg-warning-200"
                            >
                                <i class="fas fa-lightbulb mr-2"></i> Afficher l'indice
                            </button>
                        </div>

                        <div id="hints" class="hidden mt-4 p-4 bg-warning-50 border-l-4 border-warning-500 rounded-lg">
                            <h4 class="font-bold text-warning-800 mb-2">
                                <i class="fas fa-lightbulb mr-2"></i> Indice
                            </h4>
                            <p class="text-warning-700">{{ $exercise->hints }}</p>
                        </div>
                    @endif
                </div>

                <!-- Code Editor -->
                @if(auth()->check())
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">
                            <i class="fas fa-code mr-2 text-primary-500"></i> Votre solution
                        </h2>
                        
                        <form id="exercise-form" class="exercise-form">
                            @csrf
                            <div class="mb-4">
                                <textarea id="code-editor" name="code" rows="15" 
                                    class="w-full font-mono text-sm p-4 bg-gray-900 text-gray-100 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none"
                                    placeholder="Écrivez votre code ici...">{{ $submission?->submitted_code ?? $exercise->starter_code }}</textarea>
                            </div>

                            <div id="submission-feedback" class="hidden mb-4 rounded-lg p-3 text-sm"></div>
                            
                            <div class="flex justify-between items-center">
                                <button type="button" id="save-progress" class="btn bg-gray-100 text-gray-700 hover:bg-gray-200">
                                    <i class="fas fa-save mr-2"></i> Sauvegarder
                                </button>
                                <div class="space-x-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane mr-2"></i> Soumettre
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Submission Status -->
                        <div id="submission-report" class="mt-6 @if(!$submission) hidden @endif p-4 bg-{{ $submission?->status_badge_color ?? 'secondary' }}-50 border-l-4 border-{{ $submission?->status_badge_color ?? 'secondary' }}-500 rounded-lg">
                            <h4 id="submission-status" class="font-bold text-{{ $submission?->status_badge_color ?? 'secondary' }}-800 mb-2">
                                Statut : {{ $submission?->status_display ?? 'En attente' }}
                            </h4>

                            <p id="submission-score" class="text-{{ $submission?->status_badge_color ?? 'secondary' }}-700 @if($submission?->score === null) hidden @endif">
                                Score : {{ $submission?->score }}/100
                            </p>

                            <p id="submission-human-review" class="text-warning-700 mt-2 @if(!($submission?->ai_requires_human_review)) hidden @endif">
                                <strong>Note :</strong> Cette correction nécessite une validation par l'administration.
                            </p>

                            <p id="submission-feedback-text" class="text-{{ $submission?->status_badge_color ?? 'secondary' }}-700 mt-2 @if(!$submission?->feedback) hidden @endif">
                                <strong>Feedback :</strong> <span id="submission-feedback-content">{{ $submission?->feedback }}</span>
                            </p>

                            <p id="submission-unit-tests" class="text-xs text-gray-600 mt-2 @if(($submission?->unit_tests_total ?? 0) === 0) hidden @endif">
                                Tests unitaires : <span id="submission-unit-tests-content">{{ (int) ($submission?->unit_tests_passed ?? 0) }}/{{ (int) ($submission?->unit_tests_total ?? 0) }}</span>
                            </p>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                        <i class="fas fa-lock text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Connectez-vous pour soumettre</h3>
                        <p class="text-gray-600 mb-4">Vous devez être connecté pour soumettre votre solution.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                        </a>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Exercise Info -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Informations</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Langage</span>
                            <span class="font-medium">{{ $exercise->programming_language_display }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Difficulté</span>
                            <span class="font-medium">{{ $exercise->difficulty_display }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Points</span>
                            <span class="font-medium">{{ $exercise->points }}</span>
                        </div>
                        @if($exercise->estimated_time)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Temps estimé</span>
                                <span class="font-medium">{{ $exercise->estimated_time_display }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-500">Réussites</span>
                            <span class="font-medium">{{ $exercise->completion_count }}</span>
                        </div>
                    </div>
                </div>

                <!-- Related Exercises -->
                @if($relatedExercises->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Exercices similaires</h3>
                        <div class="space-y-3">
                            @foreach($relatedExercises as $related)
                                <a href="{{ route('exercises.show', $related->slug) }}" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="font-medium text-gray-900 text-sm">{{ $related->title }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <span class="badge badge-{{ $related->difficulty_badge_color }} text-xs">
                                            {{ $related->difficulty_display }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Solution (if completed) -->
                @if($submission?->isSuccessful() && $exercise->solution_code)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-4">
                            <i class="fas fa-key mr-2 text-success-500"></i> Solution
                        </h3>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>{{ $exercise->solution_code }}</code></pre>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-save progress
    let autoSaveInterval;
    let isSubmitting = false;
    const codeEditor = document.getElementById('code-editor');
    
    const feedbackBox = document.getElementById('submission-feedback');
    const reportBox = document.getElementById('submission-report');
    const reportStatus = document.getElementById('submission-status');
    const reportScore = document.getElementById('submission-score');
    const reportHumanReview = document.getElementById('submission-human-review');
    const reportFeedbackText = document.getElementById('submission-feedback-text');
    const reportFeedbackContent = document.getElementById('submission-feedback-content');
    const reportUnitTests = document.getElementById('submission-unit-tests');
    const reportUnitTestsContent = document.getElementById('submission-unit-tests-content');
    const submissionStatusUrl = '{{ route('exercises.submission-status', ['exercise' => $exercise->id]) }}';
    let submissionPollingInterval = null;
    let submissionPollingAttempts = 0;
    const maxSubmissionPollingAttempts = 40;

    function renderSubmissionReport(report) {
        if (!reportBox || !report) {
            return;
        }

        reportBox.classList.remove('hidden');

        if (reportStatus) {
            reportStatus.textContent = `Statut : ${report.status ?? 'Soumis'}`;
        }

        if (reportScore) {
            if (report.score === null || report.score === undefined) {
                reportScore.classList.add('hidden');
            } else {
                reportScore.classList.remove('hidden');
                reportScore.textContent = `Score : ${report.score}/100`;
            }
        }

        if (reportFeedbackText && reportFeedbackContent) {
            if (report.feedback) {
                reportFeedbackText.classList.remove('hidden');
                reportFeedbackContent.textContent = report.feedback;
            } else {
                reportFeedbackText.classList.add('hidden');
                reportFeedbackContent.textContent = '';
            }
        }

        if (reportHumanReview) {
            if (report.requires_human_review) {
                reportHumanReview.classList.remove('hidden');
            } else {
                reportHumanReview.classList.add('hidden');
            }
        }
    }


    function stopSubmissionPolling() {
        if (submissionPollingInterval) {
            clearInterval(submissionPollingInterval);
            submissionPollingInterval = null;
        }
    }

    async function fetchSubmissionStatus() {
        try {
            const response = await fetch(submissionStatusUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            });

            const data = await parseResponse(response);

            if (!response.ok || !data.success || !data.has_submission) {
                return;
            }

            renderSubmissionReport(data.report);

            if (data.is_final) {
                stopSubmissionPolling();
                showFeedback('Correction terminée : score et feedback mis à jour.', 'success');
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    }

    function startSubmissionPolling() {
        stopSubmissionPolling();
        submissionPollingAttempts = 0;

        fetchSubmissionStatus();

        submissionPollingInterval = setInterval(async () => {
            submissionPollingAttempts += 1;
            await fetchSubmissionStatus();

            if (submissionPollingAttempts >= maxSubmissionPollingAttempts) {
                stopSubmissionPolling();
            }
        }, 3000);
    }

    function showFeedback(message, type = 'success') {
        if (!feedbackBox) {
            return;
        }

        feedbackBox.className = 'mb-4 rounded-lg p-3 text-sm';
        feedbackBox.classList.remove('hidden');

        if (type === 'success') {
            feedbackBox.classList.add('bg-success-50', 'text-success-700', 'border', 'border-success-200');
        } else {
            feedbackBox.classList.add('bg-danger-50', 'text-danger-700', 'border', 'border-danger-200');
        }

        feedbackBox.textContent = message;
    }

    async function parseResponse(response) {
        const contentType = response.headers.get('content-type') || '';

        if (contentType.includes('application/json')) {
            return response.json();
        }

        return { message: await response.text() };
    }

    if (codeEditor) {
        // Auto-save every 30 seconds
        autoSaveInterval = setInterval(() => {
            saveProgress();
        }, 30000);

        // Save on button click
        document.getElementById('save-progress')?.addEventListener('click', () => {
            saveProgress();
        });

        // Submit form
        document.getElementById('exercise-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const code = codeEditor.value;
            const submitBtn = e.target.querySelector('button[type="submit"]');

            if (!code.trim()) {
                showFeedback('Veuillez écrire du code avant de soumettre.', 'error');
                return;
            }

            if (submitBtn) {
                submitBtn.disabled = true;
            }
            isSubmitting = true;
            if (autoSaveInterval) {
                clearInterval(autoSaveInterval);
            }
            
            try {
                const response = await fetch('{{ route('exercises.submit', ['exercise' => $exercise->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ code })
                });

                const data = await parseResponse(response);

                if (!response.ok) {
                    throw new Error(data.message || 'Erreur lors de la soumission.');
                }

                if (data.success) {
                    showFeedback(data.message, 'success');
                    renderSubmissionReport(data.report);
                    startSubmissionPolling();
                } else {
                    showFeedback(data.message || 'Une erreur est survenue.', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showFeedback(error.message || 'Une erreur est survenue lors de la soumission.', 'error');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
                isSubmitting = false;
            }
        });

        const existingStatus = reportStatus?.textContent || '';
        if (existingStatus.includes('Soumis')) {
            startSubmissionPolling();
        }
    }

    async function saveProgress() {
        if (!codeEditor || isSubmitting) {
            return;
        }

        const code = codeEditor.value;
        
        try {
            const response = await fetch('{{ route('exercises.progress', ['exercise' => $exercise->id]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ code })
            });

            const data = await parseResponse(response);

            if (!response.ok) {
                return;
            }

            if (data.success) {
                // Show temporary success message
                const btn = document.getElementById('save-progress');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check mr-2"></i> Sauvegardé';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                }, 2000);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
</script>
@endpush
