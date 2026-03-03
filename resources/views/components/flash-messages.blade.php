@php
    $flashTypes = [
        'success' => [
            'icon' => 'fa-circle-check',
            'title' => 'Succès',
            'styles' => 'border-emerald-200/80 bg-emerald-50/95 text-emerald-900 shadow-emerald-100/70',
            'iconStyles' => 'bg-emerald-500 text-white shadow-emerald-300/70',
            'buttonStyles' => 'text-emerald-600 hover:bg-emerald-100/80 hover:text-emerald-800 focus:ring-emerald-500',
            'progressStyles' => 'bg-emerald-500/80',
            'duration' => 6000,
        ],
        'error' => [
            'icon' => 'fa-circle-xmark',
            'title' => 'Erreur',
            'styles' => 'border-rose-200/80 bg-rose-50/95 text-rose-900 shadow-rose-100/70',
            'iconStyles' => 'bg-rose-500 text-white shadow-rose-300/70',
            'buttonStyles' => 'text-rose-600 hover:bg-rose-100/80 hover:text-rose-800 focus:ring-rose-500',
            'progressStyles' => 'bg-rose-500/80',
            'duration' => 7000,
        ],
        'warning' => [
            'icon' => 'fa-triangle-exclamation',
            'title' => 'Attention',
            'styles' => 'border-amber-200/80 bg-amber-50/95 text-amber-900 shadow-amber-100/70',
            'iconStyles' => 'bg-amber-500 text-white shadow-amber-300/70',
            'buttonStyles' => 'text-amber-600 hover:bg-amber-100/80 hover:text-amber-800 focus:ring-amber-500',
            'progressStyles' => 'bg-amber-500/80',
            'duration' => 7000,
        ],
        'info' => [
            'icon' => 'fa-circle-info',
            'title' => 'Information',
            'styles' => 'border-sky-200/80 bg-sky-50/95 text-sky-900 shadow-sky-100/70',
            'iconStyles' => 'bg-sky-500 text-white shadow-sky-300/70',
            'buttonStyles' => 'text-sky-600 hover:bg-sky-100/80 hover:text-sky-800 focus:ring-sky-500',
            'progressStyles' => 'bg-sky-500/80',
            'duration' => 6000,
        ],
    ];
@endphp

<div class="pointer-events-none fixed inset-x-0 top-20 z-50 mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="ml-auto flex w-full max-w-xl flex-col gap-3">
        @foreach($flashTypes as $type => $config)
            @if(session($type))
                <div class="alert-auto-hide pointer-events-auto relative overflow-hidden rounded-2xl border px-4 py-4 shadow-xl backdrop-blur-sm transition-all duration-300 ease-out animate-fade-in {{ $config['styles'] }} dark:border-slate-700 dark:bg-slate-800/95 dark:text-gray-100 dark:shadow-black/25"
                     data-alert-duration="{{ $config['duration'] }}"
                     role="status"
                     aria-live="polite">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl shadow-md {{ $config['iconStyles'] }} dark:shadow-black/25">
                            <i class="fa-solid {{ $config['icon'] }} text-sm"></i>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold leading-5">{{ $config['title'] }}</p>
                            <p class="mt-1 text-sm leading-5 text-current/90">{{ session($type) }}</p>
                            <p class="mt-1 text-xs font-medium text-current/70">Disparition automatique dans <span data-alert-seconds>{{ (int) ceil($config['duration'] / 1000) }}</span>s</p>
                        </div>

                        <button type="button"
                                data-alert-close
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-transparent {{ $config['buttonStyles'] }} dark:hover:bg-slate-700/80 dark:text-gray-300"
                                aria-label="Fermer la notification">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-black/5 dark:bg-white/10">
                        <div class="alert-progress h-full origin-left animate-alert-progress {{ $config['progressStyles'] }}" style="animation-duration: {{ $config['duration'] }}ms;"></div>
                    </div>
                </div>
            @endif
        @endforeach

        @if($errors->any())
            <div class="relative rounded-2xl border border-rose-200/80 bg-rose-50/95 px-4 py-4 text-rose-900 shadow-xl shadow-rose-100/70 backdrop-blur-sm animate-fade-in dark:border-slate-700 dark:bg-slate-800/95 dark:text-gray-100 dark:shadow-black/25"
                 role="alert"
                 aria-live="assertive">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-rose-500 text-white shadow-md shadow-rose-300/70 dark:shadow-black/25">
                        <i class="fa-solid fa-list-check text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold">Veuillez corriger les erreurs suivantes :</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-current/90">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
