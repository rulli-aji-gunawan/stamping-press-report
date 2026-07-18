<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MasterDataViewMiddleware
{
    /**
     * Allow: admin, foreman, staff (any is_admin value).
     * Deny:  manager (redirect to dashboard with error popup).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->canViewMasterData()) {
            return $next($request);
        }

        return redirect('/dashboard')->with(
            'error_popup',
            'Akses Ditolak: Role Anda tidak memiliki izin untuk mengakses halaman Master Data.'
        );
    }
}
