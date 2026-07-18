<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WriteAccessMiddleware
{
    /**
     * Allow: admin, foreman, staff, any user with is_admin=1.
     * Deny:  manager (read-only role).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->canWrite()) {
            return $next($request);
        }

        return redirect()->back()->with(
            'error_popup',
            'Akses Ditolak: Role Anda (Manager) hanya dapat melihat data, tidak dapat mengubah atau menghapus.'
        );
    }
}
