<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Return a JSON success response.
     */
    protected function successResponse($data = null, string $message = 'Success', int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Return a JSON error response.
     */
    protected function errorResponse(string $message = 'Error', int $code = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Return redirect with success message.
     */
    protected function redirectSuccess(string $route, string $message)
    {
        return redirect()->route($route)->with('success', $message);
    }

    /**
     * Return redirect with error message.
     */
    protected function redirectError(string $route, string $message)
    {
        return redirect()->route($route)->with('error', $message);
    }

    /**
     * Return back with input and errors.
     */
    protected function backWithErrors($errors, string $message = 'Veuillez corriger les erreurs ci-dessous.')
    {
        return back()->withInput()->withErrors($errors)->with('error', $message);
    }
}
