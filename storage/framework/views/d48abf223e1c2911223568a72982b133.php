

<?php $__env->startSection('title', 'Accueil - CodeLearn'); ?>

<?php $__env->startSection('content'); ?>
<section class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-secondary-800 text-white overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,...')] opacity-20 animate-[pulse_10s_ease-in-out_infinite]"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 relative">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="animate-fade-in">
                <div class="inline-flex items-center px-4 py-2 bg-white/10 rounded-full text-sm font-medium mb-6 backdrop-blur-sm border border-white/20 hover:bg-white/20 transition-colors duration-300 cursor-default">
                    <i class="fas fa-rocket mr-2 animate-bounce"></i>
                    Apprenez à coder gratuitement
                </div>
                <h1 class="text-4xl lg:text-6xl font-bold leading-tight mb-6 tracking-tight">
                    Maîtrisez la <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-400 drop-shadow-sm">programmation</span> étape par étape
                </h1>
                <p class="text-lg lg:text-xl text-primary-100 mb-8 leading-relaxed max-w-xl">
                    Des cours interactifs, des exercices pratiques et des vidéos explicatives pour apprendre Python, JavaScript, Java et bien plus encore.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="<?php echo e(route('exercises.index')); ?>" class="group btn btn-primary bg-white text-primary-700 hover:bg-gray-50 hover:shadow-lg hover:shadow-white/20 hover:-translate-y-1 transition-all duration-300 px-8 py-4 text-lg rounded-xl font-semibold flex items-center justify-center">
                        <i class="fas fa-play mr-2 group-hover:scale-110 group-hover:text-primary-600 transition-transform"></i> Commencer gratuitement
                    </a>
                    <a href="<?php echo e(route('subscription.plans')); ?>" class="group btn border-2 border-white/50 text-white hover:border-white hover:bg-white/10 hover:-translate-y-1 transition-all duration-300 px-8 py-4 text-lg rounded-xl font-semibold flex items-center justify-center">
                        <i class="fas fa-crown mr-2 text-yellow-400 group-hover:animate-pulse"></i> Voir les offres Premium
                    </a>
                    <a href="<?php echo e(route('formations.index')); ?>" class="group btn border-2 border-white/50 text-white hover:border-white hover:bg-white/10 hover:-translate-y-1 transition-all duration-300 px-8 py-4 text-lg rounded-xl font-semibold flex items-center justify-center">
                        <i class="fas fa-graduation-cap mr-2 text-white group-hover:animate-bounce"></i> Formations payantes
                    </a>
                </div>
                
                <div class="grid grid-cols-4 gap-8 mt-12 pt-8 border-t border-white/20">
                    <div class="hover:scale-105 transition-transform duration-300 cursor-default">
                        <div class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-br from-white to-white/70"><?php echo e($stats['lessons'] ?? '50+'); ?></div>
                        <div class="text-primary-200 text-sm font-medium tracking-wide uppercase mt-1">Leçons</div>
                    </div>
                    <div class="hover:scale-105 transition-transform duration-300 cursor-default">
                        <div class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-br from-white to-white/70"><?php echo e($stats['exercises'] ?? '200+'); ?></div>
                        <div class="text-primary-200 text-sm font-medium tracking-wide uppercase mt-1">Exercices</div>
                    </div>
                    <div class="hover:scale-105 transition-transform duration-300 cursor-default">
                        <div class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-br from-white to-white/70"><?php echo e($stats['videos'] ?? '100+'); ?></div>
                        <div class="text-primary-200 text-sm font-medium tracking-wide uppercase mt-1">Vidéos</div>
                    </div>
                    <div class="hover:scale-105 transition-transform duration-300 cursor-default">
                        <div class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-br from-white to-white/70"><?php echo e($stats['formations'] ?? '0'); ?></div>
                        <div class="text-primary-200 text-sm font-medium tracking-wide uppercase mt-1">Formations</div>
                    </div>
                </div>
            </div>
            
            <div class="hidden lg:block relative perspective-1000 w-full max-w-lg mx-auto">
    <style>
        .typing-container > div {
            overflow: hidden;
            white-space: nowrap;
            width: 0;
            border-right: 2px solid transparent;
        }
        
        /* Quand le bloc devient actif, on lance l'animation en cascade */
        .is-active .typing-container > div {
            animation: typeLine 0.8s steps(40, end) forwards;
        }
        
        /* Délais en cascade pour chaque ligne */
        .is-active .typing-container > div:nth-child(1) { animation-delay: 0s; }
        .is-active .typing-container > div:nth-child(2) { animation-delay: 0.8s; }
        .is-active .typing-container > div:nth-child(3) { animation-delay: 1.4s; }
        .is-active .typing-container > div:nth-child(4) { animation-delay: 2.0s; }
        .is-active .typing-container > div:nth-child(5) { animation-delay: 2.6s; }
        .is-active .typing-container > div:nth-child(6) { animation-delay: 3.2s; }

        @keyframes typeLine {
            0% { width: 0; border-right-color: #a6accd; }
            99% { border-right-color: #a6accd; }
            100% { width: 100%; border-right-color: transparent; }
        }

        /* Curseur clignotant final */
        .is-active .typing-container::after {
            content: '';
            display: inline-block;
            width: 8px;
            height: 16px;
            background-color: #a6accd;
            animation: blink 1s step-end infinite;
            margin-top: 10px;
            animation-delay: 4s;
            opacity: 0;
        }
        @keyframes blink { 
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
    </style>

    <div class="relative z-10 bg-[#1e1e2e] rounded-xl shadow-2xl overflow-hidden border border-gray-700/50 hover:shadow-primary-500/20 hover:-translate-y-2 transition-all duration-500 min-h-[320px] flex flex-col">
        <div class="flex items-center px-4 py-3 bg-[#181825] border-b border-gray-700/50">
            <div class="flex space-x-2">
                <div class="w-3 h-3 rounded-full bg-red-500 hover:bg-red-400 transition-colors"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-500 hover:bg-yellow-400 transition-colors"></div>
                <div class="w-3 h-3 rounded-full bg-green-500 hover:bg-green-400 transition-colors"></div>
            </div>
            <div class="ml-4 flex items-center text-xs font-mono text-gray-400">
                <i id="editor-icon" class="fab fa-python text-yellow-400 mr-2 transition-colors duration-300"></i>
                <span id="editor-filename" class="transition-opacity duration-300">main.py</span>
            </div>
        </div>

        <div id="code-slider" class="flex-grow relative">
            
            <div class="code-slide is-active absolute inset-0 p-6" data-filename="main.py" data-icon="fa-python" data-color="text-yellow-400">
                <div class="typing-container font-mono text-sm leading-relaxed">
                    <div class="text-[#cba6f7]">def <span class="text-[#f9e2af]">calc_moyenne</span>(notes):</div>
                    <div class="pl-4 text-[#6c7086] italic"># Calcule la moyenne</div>
                    <div class="pl-4 text-[#cdd6f4]">total = <span class="text-[#89b4fa]">sum</span>(notes)</div>
                    <div class="pl-4 text-[#cba6f7]">return <span class="text-[#89b4fa]">round</span>(total / <span class="text-[#89b4fa]">len</span>(notes), <span class="text-[#fab387]">2</span>)</div>
                    <div class="text-[#cdd6f4]"><br></div>
                    <div class="text-[#89b4fa]">print<span class="text-[#cdd6f4]">(<span class="text-[#a6e3a1]">f"Moyenne: <span class="text-[#89dceb]">{calc_moyenne([15, 18])}</span>"</span>)</span></div>
                </div>
            </div>

            <div class="code-slide hidden absolute inset-0 p-6" data-filename="index.js" data-icon="fa-js" data-color="text-yellow-300">
                <div class="typing-container font-mono text-sm leading-relaxed">
                    <div class="text-[#cba6f7]">const <span class="text-[#f9e2af]">calcMoy</span> <span class="text-[#89dceb]">=</span> (notes) <span class="text-[#cba6f7]">=></span> {</div>
                    <div class="pl-4 text-[#cdd6f4]">const tot <span class="text-[#89dceb]">=</span> notes.<span class="text-[#89b4fa]">reduce</span>((a, b) <span class="text-[#cba6f7]">=></span> a <span class="text-[#89dceb]">+</span> b);</div>
                    <div class="pl-4 text-[#cba6f7]">return <span class="text-[#89b4fa]">Number</span>((tot <span class="text-[#89dceb]">/</span> notes.length).<span class="text-[#89b4fa]">toFixed</span>(<span class="text-[#fab387]">2</span>));</div>
                    <div class="text-[#cdd6f4]">};</div>
                    <div class="text-[#cdd6f4]"><br></div>
                    <div class="text-[#89b4fa]">console<span class="text-[#cdd6f4]">.</span><span class="text-[#89b4fa]">log</span><span class="text-[#cdd6f4]">(<span class="text-[#a6e3a1]">\`Result: <span class="text-[#89dceb]">\${calcMoy([15, 18])}\`</span></span>)</span></div>
                </div>
            </div>

            <div class="code-slide hidden absolute inset-0 p-6" data-filename="Utils.php" data-icon="fa-php" data-color="text-indigo-400">
                <div class="typing-container font-mono text-sm leading-relaxed">
                    <div class="text-[#f38ba8]">&lt;?php</div>
                    <div class="text-[#cba6f7]">function <span class="text-[#f9e2af]">calcMoy</span>(<span class="text-[#f38ba8]">array</span> <span class="text-[#cdd6f4]">$n</span>): <span class="text-[#f38ba8]">float</span> {</div>
                    <div class="pl-4 text-[#cba6f7]">return <span class="text-[#89b4fa]">round</span>(<span class="text-[#89b4fa]">array_sum</span>($n) <span class="text-[#89dceb]">/</span> <span class="text-[#89b4fa]">count</span>($n), <span class="text-[#fab387]">2</span>);</div>
                    <div class="text-[#cdd6f4]">}</div>
                    <div class="text-[#cdd6f4]"><br></div>
                    <div class="text-[#89b4fa]">echo <span class="text-[#a6e3a1]">"Moy: "</span> <span class="text-[#89dceb]">.</span> <span class="text-[#f9e2af]">calcMoy</span>([<span class="text-[#fab387]">15</span>, <span class="text-[#fab387]">18</span>]);</div>
                </div>
            </div>

        </div>
    </div>
    
    <div class="absolute -top-6 -right-6 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl shadow-xl p-4 animate-[bounce_4s_infinite]">
        <i class="fas fa-code text-4xl text-white drop-shadow-md"></i>
    </div>
</div>
        </div>
    </div>
</section>

<section class="py-24 bg-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Pourquoi choisir CodeLearn ?</h2>
            <div class="w-24 h-1 bg-primary-500 mx-auto rounded-full mb-6"></div>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Une plateforme complète pour apprendre la programmation, du débutant à l'expert.</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="group bg-gray-50 rounded-2xl p-8 hover:bg-white hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-transparent hover:border-gray-100">
                <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-book-open text-2xl text-primary-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors">Leçons PDF</h3>
                <p class="text-gray-600 leading-relaxed">Des cours complets et structurés en PDF, téléchargeables pour un apprentissage hors ligne.</p>
            </div>
            
            <div class="group bg-gray-50 rounded-2xl p-8 hover:bg-white hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-transparent hover:border-gray-100">
                <div class="w-16 h-16 bg-secondary-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-laptop-code text-2xl text-secondary-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-secondary-600 transition-colors">Exercices Pratiques</h3>
                <p class="text-gray-600 leading-relaxed">Des exercices de difficulté croissante pour mettre en pratique vos connaissances.</p>
            </div>
            
            <div class="group bg-gray-50 rounded-2xl p-8 hover:bg-white hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-transparent hover:border-gray-100">
                <div class="w-16 h-16 bg-success-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-video text-2xl text-success-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-success-600 transition-colors">Vidéos Explicatives</h3>
                <p class="text-gray-600 leading-relaxed">Des vidéos pédagogiques pour visualiser les concepts et suivre les tutoriels pas à pas.</p>
            </div>
            
            <div class="group bg-gray-50 rounded-2xl p-8 hover:bg-white hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-transparent hover:border-gray-100">
                <div class="w-16 h-16 bg-warning-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-trophy text-2xl text-warning-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-warning-600 transition-colors">Système de Points</h3>
                <p class="text-gray-600 leading-relaxed">Gagnez des points en complétant les exercices et grimpez dans le classement.</p>
            </div>
            
            <div class="group bg-gray-50 rounded-2xl p-8 hover:bg-white hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-transparent hover:border-gray-100">
                <div class="w-16 h-16 bg-danger-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-certificate text-2xl text-danger-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-danger-600 transition-colors">Certificats</h3>
                <p class="text-gray-600 leading-relaxed">Obtenez des certificats de réussite pour valoriser vos compétences.</p>
            </div>
            
            <div class="group bg-gray-50 rounded-2xl p-8 hover:bg-white hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-transparent hover:border-gray-100">
                <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-graduation-cap text-2xl text-primary-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors">Formations modulaires</h3>
                <p class="text-gray-600 leading-relaxed">Des parcours complets vendus à l’unité, indépendants de l’abonnement. Payez, suivez et maîtrisez des compétences spécifiques.</p>
                <a href="<?php echo e(route('formations.index')); ?>" class="text-primary-600 hover:text-primary-700 inline-block mt-3 font-semibold">Voir les formations <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            
            <div class="group bg-gray-50 rounded-2xl p-8 hover:bg-white hover:shadow-xl hover:-translate-y-2 transition-all duration-300 border border-transparent hover:border-gray-100">
                <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300 shadow-sm">
                    <i class="fas fa-users text-2xl text-primary-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors">Communauté</h3>
                <p class="text-gray-600 leading-relaxed">Rejoignez une communauté d'apprenants et échangez avec d'autres étudiants.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-24 bg-gray-50 border-y border-gray-200/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-2">Contenu Gratuit</h2>
                <p class="text-lg text-gray-600">Commencez votre apprentissage avec nos ressources.</p>
            </div>
            <a href="<?php echo e(route('exercises.index')); ?>" class="group mt-4 md:mt-0 text-primary-600 hover:text-primary-700 font-semibold flex items-center transition-colors">
                Voir tout le contenu <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <div class="mb-16">
            <h3 class="text-2xl font-bold text-gray-800 mb-8 flex items-center">
                <i class="fas fa-fire text-orange-500 mr-3"></i> Exercices Populaires
            </h3>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $featuredExercises ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exercise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 hover:border-primary-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden flex flex-col">
                        <div class="p-6 flex-grow">
                            <div class="flex items-center justify-between mb-4">
                                <span class="badge badge-<?php echo e($exercise->difficulty_badge_color); ?> px-3 py-1 rounded-full text-xs font-bold tracking-wide">
                                    <?php echo e($exercise->difficulty_display); ?>

                                </span>
                                <i class="fab fa-<?php echo e($exercise->programming_language); ?> text-3xl text-gray-300 group-hover:text-<?php echo e($exercise->programming_language); ?>-500 transition-colors duration-300"></i>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2 text-lg group-hover:text-primary-600 transition-colors"><?php echo e($exercise->title); ?></h4>
                            <p class="text-gray-500 text-sm mb-6 line-clamp-2"><?php echo e(Str::limit($exercise->description, 100)); ?></p>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-50 bg-gray-50/50 flex items-center justify-between mt-auto">
                            <span class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-star text-yellow-400 mr-1.5"></i> <?php echo e($exercise->points); ?> pts
                            </span>
                            <a href="<?php echo e(route('exercises.show', $exercise->slug)); ?>" class="text-primary-600 hover:text-primary-800 text-sm font-bold flex items-center">
                                Commencer <i class="fas fa-chevron-right ml-1 text-xs transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full bg-white rounded-2xl border border-dashed border-gray-300 p-12 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-code text-3xl text-gray-400"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Aucun exercice disponible</h4>
                        <p class="text-gray-500">Revenez bientôt pour découvrir de nouveaux défis.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div>
            <h3 class="text-2xl font-bold text-gray-800 mb-8 flex items-center">
                <i class="fas fa-bookmark text-primary-500 mr-3"></i> Leçons Recommandées
            </h3>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $__empty_1 = true; $__currentLoopData = $featuredLessons ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                        <div class="h-48 bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-colors duration-300"></div>
                            <i class="fas fa-file-pdf text-6xl text-white/50 group-hover:scale-110 group-hover:text-white/70 transition-all duration-500"></i>
                        </div>
                        <div class="p-6">
                            <span class="inline-block bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full mb-4">
                                <?php echo e($lesson->level_display); ?>

                            </span>
                            <h4 class="font-bold text-xl text-gray-900 mb-3 group-hover:text-primary-600 transition-colors"><?php echo e($lesson->title); ?></h4>
                            <p class="text-gray-500 text-sm mb-6 line-clamp-2 leading-relaxed"><?php echo e(Str::limit($lesson->description, 100)); ?></p>
                            <a href="<?php echo e(route('lessons.show', $lesson->slug)); ?>" class="block w-full text-center bg-primary-50 text-primary-700 hover:bg-primary-600 hover:text-white font-semibold py-3 rounded-xl transition-colors duration-300">
                                <i class="fas fa-book-reader mr-2"></i> Lire la leçon
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                     <div class="col-span-full bg-white rounded-2xl border border-dashed border-gray-300 p-12 text-center">
                        <i class="fas fa-book-open text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Aucune leçon disponible pour le moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-secondary-800 via-secondary-700 to-primary-900"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,...')] opacity-10"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div class="max-w-3xl mx-auto">
            <div class="inline-block mb-6 p-4 rounded-full bg-yellow-400/20 backdrop-blur-md border border-yellow-400/30">
                <i class="fas fa-crown text-5xl text-yellow-400 drop-shadow-[0_0_15px_rgba(250,204,21,0.5)] animate-pulse"></i>
            </div>
            <h2 class="text-4xl lg:text-5xl font-extrabold text-white mb-6 tracking-tight">Passez à la vitesse supérieure</h2>
            <p class="text-xl text-white/80 mb-10 leading-relaxed font-light">
                Débloquez votre plein potentiel avec l'accès illimité aux exercices complexes, leçons détaillées et corrections vidéo exclusives.
            </p>
            
            <div class="grid sm:grid-cols-3 gap-6 mb-12">
                <div class="bg-white/10 hover:bg-white/20 transition-colors duration-300 backdrop-blur-md rounded-2xl p-6 border border-white/10">
                    <div class="text-4xl font-black text-white mb-2">500+</div>
                    <div class="text-primary-200 font-medium">Exercices Pros</div>
                </div>
                <div class="bg-white/10 hover:bg-white/20 transition-colors duration-300 backdrop-blur-md rounded-2xl p-6 border border-white/10">
                    <div class="text-4xl font-black text-white mb-2">100+</div>
                    <div class="text-primary-200 font-medium">Heures de Vidéo</div>
                </div>
                <div class="bg-white/10 hover:bg-white/20 transition-colors duration-300 backdrop-blur-md rounded-2xl p-6 border border-white/10">
                    <div class="text-4xl font-black text-white mb-2">24/7</div>
                    <div class="text-primary-200 font-medium">Support Mentor</div>
                </div>
            </div>
            
            <a href="<?php echo e(route('subscription.plans')); ?>" class="group inline-flex items-center justify-center bg-gradient-to-r from-yellow-400 to-yellow-500 text-secondary-900 hover:from-yellow-300 hover:to-yellow-400 hover:shadow-[0_0_30px_rgba(250,204,21,0.4)] hover:-translate-y-1 transition-all duration-300 px-10 py-5 text-lg rounded-xl font-bold">
                <i class="fas fa-rocket mr-3 group-hover:-translate-y-1 group-hover:translate-x-1 transition-transform"></i> Découvrir l'offre Premium
            </a>
        </div>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4">Explorez par Catégorie</h2>
            <div class="w-24 h-1 bg-secondary-500 mx-auto rounded-full mb-6"></div>
            <p class="text-lg text-gray-600">Choisissez votre langage de prédilection et lancez-vous.</p>
        </div>
        
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('categories.show', $category->slug)); ?>" class="group bg-white border border-gray-100 shadow-sm hover:shadow-xl hover:border-primary-200 rounded-2xl p-6 flex items-center space-x-5 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-16 h-16 bg-gray-50 group-hover:bg-primary-50 rounded-2xl flex items-center justify-center transition-colors duration-300">
                        <i class="fas <?php echo e($category->icon ?? 'fa-code'); ?> text-3xl text-gray-400 group-hover:text-primary-600 group-hover:scale-110 transition-all duration-300"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 group-hover:text-primary-600 transition-colors"><?php echo e($category->name); ?></h3>
                        <p class="text-gray-500 text-sm mt-1 flex items-center">
                            <i class="fas fa-layer-group text-xs mr-2 opacity-50"></i> <?php echo e($category->lessons_count); ?> leçons
                        </p>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-24 bg-gray-50 border-t border-gray-200/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4">La communauté en parle</h2>
            <p class="text-lg text-gray-600">Rejoignez des milliers d'élèves satisfaits.</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300 p-8 relative overflow-hidden">
                <i class="fas fa-quote-right absolute -bottom-4 -right-4 text-8xl text-gray-50 opacity-50"></i>
                <div class="relative z-10">
                    <div class="flex text-yellow-400 mb-6 text-sm">
                        <?php for($i = 0; $i < 5; $i++): ?> <i class="fas fa-star mr-1"></i> <?php endfor; ?>
                    </div>
                    <p class="text-gray-700 mb-8 italic leading-relaxed">"CodeLearn m'a permis de découvrir la programmation de manière ludique. Les exercices sont bien structurés et les vidéos très pédagogiques."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">ML</div>
                        <div class="ml-4">
                            <div class="font-bold text-gray-900">Marie L.</div>
                            <div class="text-primary-600 text-sm font-medium">Lycéenne</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300 p-8 relative overflow-hidden">
                <i class="fas fa-quote-right absolute -bottom-4 -right-4 text-8xl text-gray-50 opacity-50"></i>
                <div class="relative z-10">
                    <div class="flex text-yellow-400 mb-6 text-sm">
                        <?php for($i = 0; $i < 5; $i++): ?> <i class="fas fa-star mr-1"></i> <?php endfor; ?>
                    </div>
                    <p class="text-gray-700 mb-8 italic leading-relaxed">"Grâce à la version Premium, j'ai pu accéder à des exercices plus complexes qui m'ont vraiment fait progresser. Je recommande sans hésiter !"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-secondary-400 to-secondary-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">TD</div>
                        <div class="ml-4">
                            <div class="font-bold text-gray-900">Thomas D.</div>
                            <div class="text-secondary-600 text-sm font-medium">Étudiant Dev</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300 p-8 relative overflow-hidden">
                <i class="fas fa-quote-right absolute -bottom-4 -right-4 text-8xl text-gray-50 opacity-50"></i>
                <div class="relative z-10">
                    <div class="flex text-yellow-400 mb-6 text-sm">
                        <?php for($i = 0; $i < 5; $i++): ?> <i class="fas fa-star mr-1"></i> <?php endfor; ?>
                    </div>
                    <p class="text-gray-700 mb-8 italic leading-relaxed">"En tant que professeur, j'utilise CodeLearn pour compléter mes cours. La plateforme est parfaitement adaptée aux nouveaux programmes."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-success-400 to-success-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">PM</div>
                        <div class="ml-4">
                            <div class="font-bold text-gray-900">Pierre M.</div>
                            <div class="text-success-600 text-sm font-medium">Professeur</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const slides = document.querySelectorAll('.code-slide');
        const filenameEl = document.getElementById('editor-filename');
        const iconEl = document.getElementById('editor-icon');
        let currentIndex = 0;

        // On change de langage toutes les 7 secondes
        setInterval(() => {
            // Cacher le slide actuel
            slides[currentIndex].classList.remove('is-active');
            slides[currentIndex].classList.add('hidden');

            // Passer au slide suivant
            currentIndex = (currentIndex + 1) % slides.length;
            const nextSlide = slides[currentIndex];

            // Récupérer les métadonnées (nom du fichier, icône, couleur)
            const newFilename = nextSlide.getAttribute('data-filename');
            const newIcon = nextSlide.getAttribute('data-icon');
            const newColor = nextSlide.getAttribute('data-color');

            // Animation de transition douce pour l'entête
            filenameEl.style.opacity = 0;
            setTimeout(() => {
                filenameEl.textContent = newFilename;
                iconEl.className = `fab ${newIcon} ${newColor} mr-2 transition-colors duration-300`;
                filenameEl.style.opacity = 1;
            }, 300);

            // Afficher le nouveau slide (ce qui relance l'animation CSS automatiquement)
            nextSlide.classList.remove('hidden');
            // Petit délai pour forcer le navigateur à relancer les keyframes CSS
            requestAnimationFrame(() => {
                nextSlide.classList.add('is-active');
            });

        }, 7000); // 7000ms = 7 secondes pour avoir le temps de lire
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\elearning-programming\resources\views/home.blade.php ENDPATH**/ ?>