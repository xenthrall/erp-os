<?php

namespace App\Http\Middleware;

use App\Models\HR\Employee;
use App\Models\Warranties\Customer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnUserType
{
    public function handle(Request $request, Closure $next)
    {
        // Si no está autenticado, no hacemos nada
        if (! Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Solo actuar en rutas customer/* o erp/*
        if (
            ! $request->is('customer/*') && ! $request->is('erp/*') && ! $request->routeIs('error.cuenta')
        ) {
            return $next($request);
        }

        $isEmployee = $user->userable instanceof Employee;
        $isCustomer = $user->userable instanceof Customer;

        // 1. Usuario huérfano (sin perfil válido)
        if (! $isEmployee && ! $isCustomer) {

            // Evitar loop con logout o la propia ruta de error
            if (! $request->routeIs('error.cuenta') && ! $request->routeIs('logout')) {
                return redirect()->route('error.cuenta');
            }

            return $next($request);
        }

        // 2. Usuario válido intentando entrar a la vista de error
        if ($request->routeIs('error.cuenta')) {
            return redirect()->to($user->getDashboardUrl());
        }

        
        # 3. Employee intentando entrar a customer/*
        if ($isEmployee && $request->is('customer/*')) {

            return redirect()->to($user->getDashboardUrl());
        }

        // 4. Customer intentando entrar a erp/*
        if ($isCustomer && $request->is('erp/*')) {
            return redirect()->to($user->getDashboardUrl());
        }

        return $next($request);
    }
}
