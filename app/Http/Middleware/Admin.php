<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('Admin Middleware Entry Point', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ajax' => $request->ajax(),
        ]);

        if (Auth::check()) {
            $user = Auth::user();

            // 1. SuperAdmin Bypass
            if ($user->hasRole('SuperAdmin')) {
                Log::info('Admin Middleware: SuperAdmin Bypass', ['user' => $user->email]);
                return $next($request);
            }

            $route = $request->route();
            if ($route) {
                $action = $route->getActionName();
                
                // Extract controller and method
                // Expected action format: "App\Http\Controllers\Admin\SliderController@index"
                $controllerAction = str_replace('App\Http\Controllers\\', '', $action);
                
                if (strpos($controllerAction, '@') !== false) {
                    list($controller, $method) = explode('@', $controllerAction);
                    
                    Log::info('Admin Middleware Processing', [
                        'controller' => $controller,
                        'method' => $method,
                        'user' => $user->email
                    ]);

                    // 2. Permission Check
                    $permissions = $user->getAllPermissions();
                    $authorized = false;

                    foreach ($permissions as $permission) {
                        // Check if the permission's group matches the controller
                        if ($permission->permissiongroup && $permission->permissiongroup->controller === $controller) {
                            // Check if the current method is in the allowed methods array
                            if (is_array($permission->methods) && in_array($method, $permission->methods)) {
                                $authorized = true;
                                break;
                            }
                        }
                    }

                    if (!$authorized) {
                        Log::warning('Admin Middleware Access Denied', [
                            'user' => $user->email,
                            'controller' => $controller,
                            'method' => $method,
                            'allowed_controllers' => $permissions->map(fn($p) => $p->permissiongroup->controller ?? 'N/A')->unique()->toArray()
                        ]);
                        
                        if ($request->ajax()) {
                            return response()->json(['status' => 'failed', 'message' => 'Unauthorized access.'], 403);
                        }
                        
                        return abort(403, 'Unauthorized access.');
                    }
                }
            }
            return $next($request);
        }

        Log::warning('Admin Middleware: User not authenticated');
        
        if ($request->ajax()) {
            return response()->json(['status' => 'failed', 'message' => 'Unauthenticated.'], 401);
        }

        return redirect()->route('admin.login');
    }
}
