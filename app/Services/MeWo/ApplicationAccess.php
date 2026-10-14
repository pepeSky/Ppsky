<?php

namespace App\Services\MeWo;

use App\Models\Application;
use App\Models\System;
use Illuminate\Http\Request;

class ApplicationAccess
{
    public function system(Request $request): System
    {
        abort_unless($request->user(), 401);
        if (! $request->hasSession()) {
            $request->setLaravelSession(app('session.store'));
        }
        $activeId = (int) $request->session()->get('active_system_id');
        return app(BinnacleAccess::class)->system($request, $activeId ?: null) ?? abort(404, 'Seleccione un sistema en el dashboard.');
    }

    public function check(Request $request, Application $application): System
    {
        $system = $this->system($request);
        abort_unless($application->user_id === $request->user()->id, 404);
        abort_if($application->system_id !== null && (int) $application->system_id !== (int) $system->id, 404);
        return $system;
    }
}
