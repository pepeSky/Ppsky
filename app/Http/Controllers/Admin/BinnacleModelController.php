<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Binnacle;
use App\Services\MeWo\BinnacleAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BinnacleModelController extends Controller
{
    public function __construct(private BinnacleAccess $access) {}

    public function create(Request $request)
    {
        $binnacle = $this->binnacle($request);
        return view('admin.binnacle.model-create', compact('binnacle'));
    }

    public function store(Request $request)
    {
        $binnacle = $this->binnacle($request);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/\S/u', Rule::unique('models')->where('user_id', $request->user()->id)],
            'description' => ['nullable', 'string'],
        ]);
        DB::transaction(function () use ($request, $binnacle, $data) {
            $model = $request->user()->models()->create($data);
            $binnacle?->models()->attach($model);
        });
        if ($binnacle?->daily_date) {
            return redirect()->route('admin.posts.index', ['date' => $binnacle->daily_date])->with('info', 'Modelo agregado.');
        }
        return redirect()->route($binnacle ? 'admin.binnacles.show' : 'admin.posts.index', $binnacle ? [$binnacle] : [])
            ->with('info', 'Modelo agregado.');
    }

    private function binnacle(Request $request): ?Binnacle
    {
        $request->validate(['binnacle_id' => ['nullable', 'integer']]);
        if (! $request->filled('binnacle_id')) { return null; }
        $binnacle = Binnacle::findOrFail($request->integer('binnacle_id'));
        $this->access->check($request, $binnacle);
        return $binnacle;
    }
}
