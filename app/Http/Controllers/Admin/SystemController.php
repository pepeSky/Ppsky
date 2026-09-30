<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function active(Request $request)
    {
        $data = $request->validate([
            'system_id' => ['required', 'integer'],
        ]);

        $system = $request->user()
            ->systems()
            ->whereKey($data['system_id'])
            ->firstOrFail();

        $request->session()->put('active_system_id', $system->id);

        return back()->with(
            'info',
            'Sistema activo: '.$system->identity->entity->name
        );
    }
}
