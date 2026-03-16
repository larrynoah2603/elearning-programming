

<?php $__env->startSection('title', 'Quiz - ' . $quiz->title); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="<?php echo e(route('formations.show', $formation->slug)); ?>"
                   class="text-primary-600 hover:text-primary-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour à la formation
                </a>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo e($quiz->title); ?></h1>
            <p class="text-gray-600 mb-4"><?php echo e($quiz->description ?? 'Répondez aux questions suivantes pour valider vos connaissances.'); ?></p>

            <div class="flex flex-wrap gap-4 text-sm">
                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full">
                    <?php echo e($quiz->questions->count()); ?> question<?php echo e($quiz->questions->count() > 1 ? 's' : ''); ?>

                </span>
                <span class="bg-orange-50 text-orange-700 px-3 py-1 rounded-full">
                    Score minimum: <?php echo e($quiz->passing_score); ?>%
                </span>
                <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-full">
                    Tentative <?php echo e($attemptCount + 1); ?>/<?php echo e($quiz->max_attempts); ?>

                </span>
                <?php if($quiz->duration_minutes): ?>
                    <span class="bg-gray-50 text-gray-700 px-3 py-1 rounded-full">
                        ⏱️ <?php echo e($quiz->duration_minutes); ?> minutes
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quiz Form -->
        <form id="quiz-form" action="<?php echo e(route('quiz.submit', $quiz)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="space-y-8">
                <?php $__currentLoopData = $quiz->questions->sortBy('order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="question-item border border-gray-200 rounded-lg p-6" data-question-id="<?php echo e($question->id); ?>">
                        <div class="flex items-start gap-3 mb-4">
                            <span class="flex-shrink-0 w-8 h-8 bg-primary-100 text-primary-800 rounded-full flex items-center justify-center font-semibold text-sm">
                                <?php echo e($index + 1); ?>

                            </span>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo e($question->text); ?></h3>
                                <?php if($question->points): ?>
                                    <span class="text-sm text-gray-500 bg-gray-50 px-2 py-1 rounded">
                                        <?php echo e($question->points); ?> point<?php echo e($question->points > 1 ? 's' : ''); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="ml-11">
                            <?php switch($question->type):
                                case ('multiple_choice'): ?>
                                    <div class="space-y-3">
                                        <?php $__currentLoopData = $question->answers->sortBy('order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="radio"
                                                       name="answers[question_<?php echo e($question->id); ?>]"
                                                       value="<?php echo e($answer->id); ?>"
                                                       class="w-4 h-4 text-primary-600 border-gray-300 focus:ring-primary-500"
                                                       required>
                                                <span class="text-gray-700"><?php echo e($answer->text); ?></span>
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <?php break; ?>

                                <?php case ('true_false'): ?>
                                    <div class="space-y-3">
                                        <?php $__currentLoopData = $question->answers->sortBy('order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="radio"
                                                       name="answers[question_<?php echo e($question->id); ?>]"
                                                       value="<?php echo e($answer->id); ?>"
                                                       class="w-4 h-4 text-primary-600 border-gray-300 focus:ring-primary-500"
                                                       required>
                                                <span class="text-gray-700"><?php echo e($answer->text); ?></span>
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <?php break; ?>

                                <?php case ('essay'): ?>
                                    <div class="space-y-3">
                                        <textarea name="answers[question_<?php echo e($question->id); ?>]"
                                                  rows="4"
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                                  placeholder="Tapez votre réponse ici..."
                                                  required></textarea>
                                        <p class="text-sm text-gray-500">
                                            Cette question sera corrigée manuellement. Soyez précis et détaillé dans votre réponse.
                                        </p>
                                    </div>
                                    <?php break; ?>
                            <?php endswitch; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Assurez-vous d'avoir répondu à toutes les questions avant de soumettre.
                    </div>
                    <button type="submit"
                            id="submit-quiz"
                            class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Soumettre le quiz
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('quiz-form');
    const submitButton = document.getElementById('submit-quiz');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Vérifier que toutes les questions sont répondues
        const questions = document.querySelectorAll('.question-item');
        let allAnswered = true;

        questions.forEach(question => {
            const inputs = question.querySelectorAll('input[type="radio"], textarea');
            let questionAnswered = false;

            inputs.forEach(input => {
                if ((input.type === 'radio' && input.checked) ||
                    (input.type === 'textarea' && input.value.trim() !== '')) {
                    questionAnswered = true;
                }
            });

            if (!questionAnswered) {
                allAnswered = false;
                question.classList.add('border-red-300', 'bg-red-50');
            } else {
                question.classList.remove('border-red-300', 'bg-red-50');
            }
        });

        if (!allAnswered) {
            alert('Veuillez répondre à toutes les questions avant de soumettre le quiz.');
            return;
        }

        // Désactiver le bouton et afficher le chargement
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <span class="inline-flex items-center gap-2">
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Soumission en cours...
            </span>
        `;

        // Soumettre le formulaire
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.formation_complete) {
                    // Rediriger vers la page de félicitations
                    window.location.href = data.redirect_url;
                } else {
                    // Afficher les résultats et rediriger
                    alert(`Quiz soumis ! Score: ${data.score}%. ${data.passed ? 'Réussi !' : 'Échec. Vous pouvez retenter.'}`);
                    window.location.href = data.redirect_url;
                }
            } else {
                alert(data.error || 'Une erreur est survenue.');
                submitButton.disabled = false;
                submitButton.innerHTML = `
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Soumettre le quiz
                    </span>
                `;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la soumission.');
            submitButton.disabled = false;
            submitButton.innerHTML = `
                <span class="inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Soumettre le quiz
                </span>
            `;
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/formations/quiz/show.blade.php ENDPATH**/ ?>