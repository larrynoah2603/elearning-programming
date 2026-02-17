<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class FindUnusedControllers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:find-unused-controllers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Liste les contrôleurs présents dans app/Http/Controllers mais non référencés dans les routes';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $controllers = collect(File::allFiles(app_path('Http/Controllers')))
            ->map(function ($file) {
                return 'App\\Http\\Controllers\\' . str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    $file->getRelativePathname()
                );
            })
            ->values();

        $usedControllers = collect(Route::getRoutes()->getRoutes())
            ->map(fn ($route) => $route->action['controller'] ?? null)
            ->filter()
            ->map(function ($controllerAction) {
                return Str::before($controllerAction, '@');
            })
            ->unique()
            ->values();

        $unusedControllers = $controllers
            ->diff($usedControllers)
            ->sort()
            ->values();

        if ($unusedControllers->isEmpty()) {
            $this->info('Aucun contrôleur inutilisé trouvé.');
            return self::SUCCESS;
        }

        $this->warn('Contrôleurs potentiellement inutilisés :');

        foreach ($unusedControllers as $controller) {
            $this->line("- {$controller}");
        }

        return self::SUCCESS;
    }
}
