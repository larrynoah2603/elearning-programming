<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('audit:routes', function () {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'name' => $route->getName(),
            'uri' => $route->uri(),
            'methods' => implode('|', array_diff($route->methods(), ['HEAD'])),
        ];
    });

    $duplicates = $routes
        ->filter(fn ($route) => filled($route['name']))
        ->groupBy('name')
        ->filter(fn ($group) => $group->count() > 1);

    if ($duplicates->isEmpty()) {
        $this->info('✅ Aucun nom de route dupliqué détecté.');
        return;
    }

    $this->warn('⚠️ Noms de routes dupliqués détectés :');

    foreach ($duplicates as $name => $group) {
        $this->line("- {$name}");
        foreach ($group as $route) {
            $this->line("  • [{$route['methods']}] {$route['uri']}");
        }
    }

    $this->newLine();
    $this->error('Corrigez ces collisions pour éviter des URLs générées incohérentes.');
})->purpose('Audit des collisions de noms de routes');
