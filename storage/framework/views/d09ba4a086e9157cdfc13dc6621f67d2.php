

<?php $__env->startSection('title', $exercise->title . ' - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-gray-500 mb-6">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-primary-600">Accueil</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <a href="<?php echo e(route('exercises.index')); ?>" class="hover:text-primary-600">Exercices</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900"><?php echo e($exercise->title); ?></span>
        </nav>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Exercise Header -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="badge badge-<?php echo e($exercise->difficulty_badge_color); ?>">
                                    <?php echo e($exercise->difficulty_display); ?>

                                </span>
                                <span class="badge badge-info">
                                    <i class="fab fa-<?php echo e($exercise->programming_language); ?> mr-1"></i>
                                    <?php echo e($exercise->programming_language_display); ?>

                                </span>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900"><?php echo e($exercise->title); ?></h1>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-primary-600"><?php echo e($exercise->points); ?> pts</div>
                            <?php if($exercise->estimated_time): ?>
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i> <?php echo e($exercise->estimated_time_display); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <p class="text-gray-600"><?php echo e($exercise->description); ?></p>
                </div>

                <!-- Instructions -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">
                        <i class="fas fa-list-ul mr-2 text-primary-500"></i> Instructions
                    </h2>
                    <div class="prose max-w-none text-gray-600">
                        <?php echo nl2br(e($exercise->instructions)); ?>

                    </div>

                    <?php if($exercise->hints): ?>
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
                            <p class="text-warning-700"><?php echo e($exercise->hints); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Code Editor -->
                <?php if(auth()->check()): ?>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">
                            <i class="fas fa-code mr-2 text-primary-500"></i> Votre solution
                        </h2>
                        
                        <form id="exercise-form" class="exercise-form">
                            <?php echo csrf_field(); ?>
                            <div class="mb-4">
                                <textarea id="code-editor" name="code" rows="15" 
                                    class="w-full font-mono text-sm p-4 bg-gray-900 text-gray-100 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none"
                                    placeholder="Écrivez votre code ici..."><?php echo e($submission?->submitted_code ?? $exercise->starter_code); ?></textarea>
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
                        <div id="submission-report" class="mt-6 <?php if(!$submission): ?> hidden <?php endif; ?> p-4 bg-<?php echo e($submission?->status_badge_color ?? 'secondary'); ?>-50 border-l-4 border-<?php echo e($submission?->status_badge_color ?? 'secondary'); ?>-500 rounded-lg">
                            <h4 id="submission-status" class="font-bold text-<?php echo e($submission?->status_badge_color ?? 'secondary'); ?>-800 mb-2">
                                Statut : <?php echo e($submission?->status_display ?? 'En attente'); ?>

                            </h4>

                            <p id="submission-score" class="text-<?php echo e($submission?->status_badge_color ?? 'secondary'); ?>-700 <?php if($submission?->score === null): ?> hidden <?php endif; ?>">
                                Score : <?php echo e($submission?->score); ?>/100
                            </p>

                            <p id="submission-human-review" class="text-warning-700 mt-2 <?php if(!($submission?->ai_requires_human_review)): ?> hidden <?php endif; ?>">
                                <strong>Note :</strong> Cette correction automatique nécessite une validation humaine par l'administration.
                            </p>

                            <p id="submission-feedback-text" class="text-<?php echo e($submission?->status_badge_color ?? 'secondary'); ?>-700 mt-2 <?php if(!$submission?->feedback): ?> hidden <?php endif; ?>">
                                <strong>Feedback :</strong> <span id="submission-feedback-content"><?php echo e($submission?->feedback); ?></span>
                            </p>

                            <p id="submission-ai-model" class="text-xs text-gray-500 mt-2 <?php if(!$submission?->ai_model): ?> hidden <?php endif; ?>">
                                Modèle IA : <span><?php echo e($submission?->ai_model); ?></span>
                            </p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                        <i class="fas fa-lock text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Connectez-vous pour soumettre</h3>
                        <p class="text-gray-600 mb-4">Vous devez être connecté pour soumettre votre solution.</p>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Exercise Info -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Informations</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Langage</span>
                            <span class="font-medium"><?php echo e($exercise->programming_language_display); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Difficulté</span>
                            <span class="font-medium"><?php echo e($exercise->difficulty_display); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Points</span>
                            <span class="font-medium"><?php echo e($exercise->points); ?></span>
                        </div>
                        <?php if($exercise->estimated_time): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Temps estimé</span>
                                <span class="font-medium"><?php echo e($exercise->estimated_time_display); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Réussites</span>
                            <span class="font-medium"><?php echo e($exercise->completion_count); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Related Exercises -->
                <?php if($relatedExercises->count() > 0): ?>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Exercices similaires</h3>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $relatedExercises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('exercises.show', $related->slug)); ?>" class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="font-medium text-gray-900 text-sm"><?php echo e($related->title); ?></div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <span class="badge badge-<?php echo e($related->difficulty_badge_color); ?> text-xs">
                                            <?php echo e($related->difficulty_display); ?>

                                        </span>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Solution (if completed) -->
                <?php if($submission?->isSuccessful() && $exercise->solution_code): ?>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-4">
                            <i class="fas fa-key mr-2 text-success-500"></i> Solution
                        </h3>
                        <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code><?php echo e($exercise->solution_code); ?></code></pre>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
    const reportAiModel = document.getElementById('submission-ai-model');

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

        const modelTarget = reportAiModel?.querySelector('span');
        if (reportAiModel && modelTarget) {
            if (report.ai_model) {
                reportAiModel.classList.remove('hidden');
                modelTarget.textContent = report.ai_model;
            } else {
                reportAiModel.classList.add('hidden');
            }
        }
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
                const response = await fetch('<?php echo e(route('exercises.submit', ['exercise' => $exercise->id])); ?>', {
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
    }

    async function saveProgress() {
        if (!codeEditor || isSubmitting) {
            return;
        }

        const code = codeEditor.value;
        
        try {
            const response = await fetch('<?php echo e(route('exercises.progress', ['exercise' => $exercise->id])); ?>', {
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/exercises/show.blade.php ENDPATH**/ ?>