<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ShouldntReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class RegistroVinculadoException extends Exception implements ShouldntReport
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
            ], 409);
        }

        return back()->with('error', $this->getMessage());
    }
}
