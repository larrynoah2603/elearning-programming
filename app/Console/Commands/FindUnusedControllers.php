// Dans app/Console/Commands/FindUnusedControllers.php
public function handle()
{
    $controllers = collect(File::allFiles(app_path('Http/Controllers')))
        ->map(function ($file) {
            return 'App\\Http\\Controllers\\' . str_replace(
                ['/', '.php'],
                ['\\', ''],
                $file->getRelativePathname()
            );
        });
    
    $usedControllers = [];
    
    // Analyser les routes
    foreach (Route::getRoutes()->getRoutes() as $route) {
        if ($route->action['controller'] ?? false) {
            $usedControllers[] = $route->action['controller'];
        }
    }
    
    $unusedControllers = $controllers->filter(function ($controller) use ($usedControllers) {
        return !in_array($controller, $usedControllers);
    });
    
    $this->info('Contrôleurs inutilisés:');
    foreach ($unusedControllers as $controller) {
        $this->line("- $controller");
    }
}