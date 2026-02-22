

<?php $__env->startSection('title', 'Statistiques - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Statistiques</h1>

    <div class="grid md:grid-cols-4 gap-4">
      <div class="bg-white p-4 rounded-xl shadow-sm"><p class="text-sm text-gray-500">Soumissions</p><p class="text-2xl font-bold"><?php echo e($submissionStats['total']); ?></p></div>
      <div class="bg-white p-4 rounded-xl shadow-sm"><p class="text-sm text-gray-500">Taux de réussite</p><p class="text-2xl font-bold"><?php echo e(number_format($submissionStats['success_rate'], 1)); ?>%</p></div>
      <div class="bg-white p-4 rounded-xl shadow-sm"><p class="text-sm text-gray-500">Vues vidéos</p><p class="text-2xl font-bold"><?php echo e($videoStats['total_views']); ?></p></div>
      <div class="bg-white p-4 rounded-xl shadow-sm"><p class="text-sm text-gray-500">Utilisateurs</p><p class="text-2xl font-bold"><?php echo e($userStats['by_role']->sum()); ?></p></div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
      <h2 class="font-semibold mb-3">Top vidéos</h2>
      <ul class="space-y-2">
      <?php $__empty_1 = true; $__currentLoopData = $videoStats['most_viewed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <li class="flex items-center justify-between text-sm"><span><?php echo e($video->title); ?></span><span class="text-gray-500"><?php echo e($video->views); ?> vues</span></li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <li class="text-sm text-gray-500">Aucune vidéo.</li>
      <?php endif; ?>
      </ul>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/admin/statistics.blade.php ENDPATH**/ ?>