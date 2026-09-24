<?php

namespace App\Http\Controllers\Admin\SyQuAc;

use App\Http\Controllers\Controller;
use App\Models\Uf;
use App\Services\SyQuAc\UfService;
use Illuminate\Http\Request;

class UfController extends Controller
{
    public function __construct(
        protected UfService $ufService
    ) {
    }

    public function index()
    {
        $ufs = Uf::orderByDesc('fecha')
            ->paginate(31);

        return view('admin.syquac.uf.index', compact('ufs'));
    }

    public function show(Uf $uf)
    {
        return view('admin.syquac.uf.show', compact('uf'));
    }

    public function sincronizar(Request $request)
    {
        $request->validate([
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ]);

        $registros = $this->ufService->sincronizar(
            $request->fecha_inicio,
            $request->fecha_fin
        );

        return redirect()
            ->route('admin.syquac.uf.index')
            ->with(
                'success',
                "Se sincronizaron {$registros} valores UF desde el Banco Central de Chile."
            );
    }
}
