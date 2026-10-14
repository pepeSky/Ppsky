<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::orderBy('id')->paginate(20);

        return view('admin.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.units.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['required', 'string', 'max:255'],
            'magnitude' => ['nullable', 'string', 'max:255'],
            'system' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
        ]);
        Unit::create($validated);
        return redirect()->route('admin.units.index')->with('info', 'Unidad creada correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        return view('admin.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['required', 'string', 'max:255'],
            'magnitude' => ['nullable', 'string', 'max:255'],
            'system' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
        ]);
        if((\App\Models\Actor::where('unit_id',$unit->id)->exists() || \App\Models\ActorClassification::where('unit_id',$unit->id)->exists()) && ($unit->symbol!==$validated['symbol'] || $unit->system!==($validated['system'] ?? null))) {
            return back()->withErrors(['symbol'=>'Esta unidad tiene actores asociados; conserve su símbolo y sistema.']);
        }
        $unit->update($validated);
        return redirect()->route('admin.units.index')->with('info', 'Unidad modificada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        if((\App\Models\Actor::where('unit_id',$unit->id)->exists() || \App\Models\ActorClassification::where('unit_id',$unit->id)->exists())) { return back()->withErrors(['unit'=>'Esta unidad tiene actores asociados y debe conservarse.']); }
        $unit->delete();
        return redirect()->route('admin.units.index')->with('info', 'Unidad eliminada correctamente.');
    }
}
