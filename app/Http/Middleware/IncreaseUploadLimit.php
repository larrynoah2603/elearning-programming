<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IncreaseUploadLimit
{
    public function handle(Request $request, Closure $next)
    {
        // Augmenter les limites pour les routes admin/videos
        if ($request->is('admin/videos*') && in_array($request->method(), ['POST', 'PUT'])) {
            // Modifier les limites PHP
            ini_set('upload_max_filesize', '1024M');
            ini_set('post_max_size', '1024M');
            ini_set('max_execution_time', '600');
            ini_set('max_input_time', '600');
        }

        return $next($request);
    }
}