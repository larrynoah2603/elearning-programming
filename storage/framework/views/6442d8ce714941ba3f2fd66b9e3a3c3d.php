<?php $__env->startSection('title', $video->title . ' - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-gray-500 mb-6">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-primary-600">Accueil</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <a href="<?php echo e(route('videos.index')); ?>" class="hover:text-primary-600">Vidéos</a>
            <i class="fas fa-chevron-right mx-2 text-xs"></i>
            <span class="text-gray-900"><?php echo e($video->title); ?></span>
        </nav>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Video Player -->
                <div class="bg-black rounded-xl overflow-hidden mb-6">
                    <video id="video-player" 
                        class="w-full aspect-video" 
                        controls 
                        poster="<?php echo e($video->thumbnail_url); ?>"
                        data-video-id="<?php echo e($video->id); ?>"
                        <?php if($progress): ?> data-current-time="<?php echo e($progress->current_time); ?>" <?php endif; ?>>
                        <source src="<?php echo e(url('/elearning-programming/storage/app/public/' . $video->video_file)); ?>" type="video/mp4">
                        Votre navigateur ne supporte pas la lecture de vidéos.
                    </video>
                </div>

                <!-- Video Info -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="badge badge-<?php echo e($video->level_badge_color); ?>">
                                    <?php echo e($video->level_display); ?>

                                </span>
                                <?php if($video->access_level == 'subscribed'): ?>
                                    <span class="badge badge-warning">
                                        <i class="fas fa-crown mr-1"></i> Premium
                                    </span>
                                <?php endif; ?>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900"><?php echo e($video->title); ?></h1>
                        </div>
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <span class="mr-4"><i class="fas fa-eye mr-1"></i> <?php echo e($video->views_count); ?> vues</span>
                        <?php if($video->duration): ?>
                            <span class="mr-4"><i class="fas fa-clock mr-1"></i> <?php echo e($video->duration_display); ?></span>
                        <?php endif; ?>
                    </div>

                    <p class="text-gray-600"><?php echo e($video->description); ?></p>
                </div>

                <!-- Progress -->
                <?php if($progress): ?>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Votre progression</h3>
                        <div class="flex items-center">
                            <div class="flex-1 mr-4">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary-500 h-2 rounded-full transition-all duration-300" 
                                         style="width: <?php echo e($progress->progress_percentage); ?>%"></div>
                                </div>
                            </div>
                            <span class="text-sm font-medium text-gray-700"><?php echo e($progress->progress_percentage); ?>%</span>
                        </div>
                        <?php if($progress->is_completed): ?>
                            <div class="mt-4 p-4 bg-success-50 border-l-4 border-success-500 rounded-lg">
                                <p class="text-success-700">
                                    <i class="fas fa-check-circle mr-2"></i> Vidéo terminée !
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Video Info -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Informations</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Niveau</span>
                            <span class="font-medium"><?php echo e($video->level_display); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Durée</span>
                            <span class="font-medium"><?php echo e($video->duration_display ?? 'N/A'); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Vues</span>
                            <span class="font-medium"><?php echo e($video->views_count); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Related Videos -->
                <?php if($relatedVideos->count() > 0): ?>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Vidéos similaires</h3>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $relatedVideos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('videos.show', $related->slug)); ?>" class="block">
                                    <div class="flex space-x-3">
                                        <div class="w-24 h-16 bg-gray-900 rounded-lg overflow-hidden flex-shrink-0">
                                            <?php if($related->thumbnail): ?>
                                                <img src="<?php echo e($related->thumbnail_url); ?>" alt="<?php echo e($related->title); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center">
                                                    <i class="fas fa-play text-white/50"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900 text-sm line-clamp-2"><?php echo e($related->title); ?></div>
                                            <?php if($related->duration): ?>
                                                <div class="text-xs text-gray-500 mt-1"><?php echo e($related->duration_display); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Author -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Auteur</h3>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            <?php echo e(substr($video->user->name, 0, 1)); ?>

                        </div>
                        <div class="ml-3">
                            <div class="font-medium text-gray-900"><?php echo e($video->user->name); ?></div>
                            <div class="text-sm text-gray-500">Formateur</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const videoPlayer = document.getElementById('video-player');
        
        if (videoPlayer) {
            let lastUpdate = 0;
            const videoId = videoPlayer.dataset.videoId;
            const savedTime = parseFloat(videoPlayer.dataset.currentTime) || 0;
            
            // Restore saved progress
            if (savedTime > 0) {
                videoPlayer.currentTime = savedTime;
            }
            
            // Update progress every 5 seconds
            videoPlayer.addEventListener('timeupdate', function() {
                const now = Date.now();
                if (now - lastUpdate > 5000) {
                    updateProgress(videoPlayer.currentTime, videoPlayer.currentTime);
                    lastUpdate = now;
                }
            });
            
            // Mark as completed when video ends
            videoPlayer.addEventListener('ended', function() {
                markCompleted();
            });
            
            async function updateProgress(currentTime, watchedDuration) {
                try {
                    await fetch('<?php echo e(route('videos.progress', $video)); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            current_time: Math.floor(currentTime),
                            watched_duration: Math.floor(watchedDuration)
                        })
                    });
                } catch (error) {
                    console.error('Error updating progress:', error);
                }
            }
            
            async function markCompleted() {
                try {
                    await fetch('<?php echo e(route('videos.complete', $video)); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                } catch (error) {
                    console.error('Error marking as completed:', error);
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/videos/show.blade.php ENDPATH**/ ?>