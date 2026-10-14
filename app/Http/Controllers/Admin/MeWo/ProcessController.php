<?php

namespace App\Http\Controllers\Admin\MeWo;

use App\Http\Controllers\Controller;
use App\Models\Context;
use App\Models\MewoProcess;
use App\Models\OperationalVerb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProcessController extends Controller
{
    public function index()
    {
        return view('admin.mewo.processes.index', ['processes' => MewoProcess::with(['context', 'verbs'])->orderBy('context_id')->orderBy('id')->get()]);
    }

    public function create()
    {
        return view('admin.mewo.processes.form', ['process' => null, 'contexts' => Context::where('type', 'simple')->get()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        DB::transaction(function () use ($data) {
            $process = MewoProcess::create(collect($data)->except(['verbs'])->all());
            $this->verbs($process, $data['verbs']);
        });
        return redirect()->route('admin.mewo.processes.index')->with('info', 'Proceso creado.');
    }

    public function edit(MewoProcess $process)
    {
        $process->load('verbs');
        return view('admin.mewo.processes.form', ['process' => $process, 'contexts' => Context::where('type', 'simple')->get()]);
    }

    public function update(Request $request, MewoProcess $process)
    {
        $data = $this->validated($request, $process);
        DB::transaction(function () use ($process, $data) {
            $process->update(collect($data)->except(['verbs'])->all());
            $this->verbs($process, $data['verbs']);
        });
        return redirect()->route('admin.mewo.processes.index')->with('info', 'Proceso actualizado.');
    }

    private function validated(Request $request, ?MewoProcess $process = null): array
    {
        $data = $request->validate([
            'context_id' => ['required', 'integer', Rule::exists('contexts', 'id')->where('type', 'simple')],
            'code' => ['required', 'string', 'max:30', Rule::unique('mewo_processes', 'code')->where('context_id', $request->input('context_id'))->ignore($process?->id)],
            'name' => ['required', 'string', 'max:255', Rule::unique('mewo_processes', 'name')->where('context_id', $request->input('context_id'))->ignore($process?->id)],
            'description' => ['nullable', 'string'], 'input' => ['nullable', 'string'],
            'output' => ['nullable', 'string'], 'procedure' => ['nullable', 'string'],
            'verbs' => ['required', 'string', 'max:1000'],
        ]);
        if ($process && $process->context_id !== (int) $data['context_id'] && ($process->activities()->exists() || $process->tasks()->exists())) {
            throw \Illuminate\Validation\ValidationException::withMessages(['context_id' => 'No cambie el contexto de un proceso utilizado.']);
        }
        $verbs = array_unique(array_filter(array_map(fn ($verb) => mb_strtolower(trim($verb)), explode(',', $data['verbs']))));
        foreach ($verbs as $verb) {
            if (! preg_match('/^[\p{L}]+(?:ar|er|ir)$/u', $verb) || mb_strlen($verb) > 80) {
                throw \Illuminate\Validation\ValidationException::withMessages(['verbs' => 'Use infinitivos separados por comas, por ejemplo: reparar, verificar.']);
            }
        }
        if (! $verbs) {
            throw \Illuminate\Validation\ValidationException::withMessages(['verbs' => 'Indique al menos un verbo.']);
        }
        $data['verbs'] = $verbs;
        return $data;
    }

    private function verbs(MewoProcess $process, array $verbs): void
    {
        $ids = array_map(fn ($verb) => OperationalVerb::firstOrCreate(['infinitive' => $verb], ['forms' => []])->id, $verbs);
        $process->verbs()->sync($ids);
    }
}
