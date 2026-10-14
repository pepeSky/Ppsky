<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockFrontendRecordDeletion
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('DELETE') && $request->is('admin', 'admin/*', 'app', 'app/*')) {
            abort(403, 'La eliminación de registros debe solicitarse a la administración de comunicación, con los respaldos y resguardos legales correspondientes.');
        }
        return $next($request);
    }
}
