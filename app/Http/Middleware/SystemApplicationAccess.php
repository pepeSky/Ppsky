<?php
namespace App\Http\Middleware;
use App\Models\System;
use App\Services\MeWo\ActorAccess;
use Closure;
use Illuminate\Http\Request;
class SystemApplicationAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Switching to another authorised system must remain possible after a local revocation.
        if ($request->user() && $request->is('admin','admin/*','app','app/*') && !$request->is('admin/system/active','app/system/active')) {
            $systems=$request->user()->systems()->with('identity')->orderBy('systems.id')->get();
            $system=$systems->firstWhere('id',(int)$request->session()->get('active_system_id')) ?? $systems->first();
            if ($system) { abort_unless(app(ActorAccess::class)->canEnter($request->user(),$system),403,'Acceso no autorizado en el sistema activo.'); }
        }
        return $next($request);
    }
}
