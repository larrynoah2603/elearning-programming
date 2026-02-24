<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

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
    protected $description = 'Liste les contrôleurs non utilisés dans les routes';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $controllers = collect(File::allFiles(app_path('Http/Controllers')))
            ->map(function ($file) {
                return 'App\\Http\\Controllers\\'.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    $file->getRelativePathname()
                );
            });

        $usedControllers = collect(Route::getRoutes()->getRoutes())
            ->map(function ($route) {
                $controllerAction = $route->action['controller'] ?? null;

                if (!$controllerAction) {
                    return null;
                }

                return explode('@', $controllerAction)[0];
            })
            ->filter()
            ->unique()
            ->values();

        $unusedControllers = $controllers
            ->filter(fn (string $controller) => !$usedControllers->contains($controller))
            ->sort()
            ->values();

        $this->info('Contrôleurs inutilisés :');

        if ($unusedControllers->isEmpty()) {
            $this->line('Aucun contrôleur inutilisé trouvé.');

            return self::SUCCESS;
        }

        foreach ($unusedControllers as $controller) {
            $this->line('- '.$controller);
        }

        return self::SUCCESS;
    }
}
