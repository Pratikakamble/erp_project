<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    	$user = Auth::user();
    	 if ($user) {
            // Check role and redirect accordingly
            if ($user->role === 'salesperson' && $request->is('dashboard')) {
                return redirect()->route('sales-orders.index');
            }else if($user->role != 'admin'){
            	 abort(403, 'Unauthorized');
            }
        }
        return $next($request);
    }
}
