<?php

namespace App\Http\Middleware;

use Closure;

class Admin {
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next) {
    if ($request->user()->is_admin) {
      return $next($request);
    }
    return response(['message' => 'Admin access only'], 401);
  }
}
