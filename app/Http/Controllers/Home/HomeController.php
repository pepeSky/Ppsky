<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $systems = $request->user()
            ->systems()
            ->with('identity.entity')
            ->orderBy('systems.id')
            ->get();

        $activeSystem = $systems->firstWhere(
            'id',
            $request->session()->get('active_system_id')
        );

        if (! $activeSystem) {
            $activeSystem = $systems->first();

            if ($activeSystem) {
                $request->session()->put(
                    'active_system_id',
                    $activeSystem->id
                );
            }
        }

        return view('home.index', compact(
            'systems',
            'activeSystem'
        ));
    }
}
