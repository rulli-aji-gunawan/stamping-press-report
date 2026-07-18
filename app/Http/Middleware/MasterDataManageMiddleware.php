<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MasterDataManageMiddleware
{
    /**
     * Allow write (CRUD) on non-user master data.
     * Allowed : is_admin = 1 (any role), OR role = 'staff' (any is_admin).
     * Denied  : foreman, manager → redirect to dashboard with error popup.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->canManageNonUserMasterData()) {
            return $next($request);
        }

        return redirect('/dashboard')->with(
            'error_popup',
            'Akses Ditolak: Role Anda tidak memiliki izin untuk mengubah data Master Data.'
        );
    }
}
