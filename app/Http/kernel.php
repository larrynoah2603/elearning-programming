protected $middleware = [
    // ... autres middlewares
    \App\Http\Middleware\IncreaseUploadLimit::class,
];
// app/Http/Kernel.php
protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'admin' => \App\Http\Middleware\AdminMiddleware::class, // ← Vérifiez cette ligne
    'subscribed' => \App\Http\Middleware\SubscribedMiddleware::class,
    // ...
];