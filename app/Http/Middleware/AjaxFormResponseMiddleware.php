<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxFormResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (($request->ajax() || $request->wantsJson()) && $response instanceof \Illuminate\Http\RedirectResponse) {
            $session = $request->session();
            $redirectUrl = $response->headers->get('Location') ?? $response->getTargetUrl();

            $successMessage = $session->get('success');
            $errorMessage = $session->get('error');

            // --- CRITICAL FIX MULAI ---
            // Laravel tidak cukup hanya di-forget. Kita harus membersihkan metadata flash.
            $session->forget(['success', 'error']);
            
            $newFlash = array_values(array_diff($session->get('_flash.new', []), ['success', 'error']));
            $oldFlash = array_values(array_diff($session->get('_flash.old', []), ['success', 'error']));
            
            $session->put('_flash.new', $newFlash);
            $session->put('_flash.old', $oldFlash);
            
            $session->save();
            // --- CRITICAL FIX SELESAI ---

            return response()->json([
                'success' => $successMessage ? true : ($errorMessage ? false : true),
                'message' => $successMessage ?: $errorMessage ?: 'Success',
                'redirect' => $redirectUrl
            ]);
        }

        return $response;
    }
}
