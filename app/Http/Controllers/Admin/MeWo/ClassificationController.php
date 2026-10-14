<?php

namespace App\Http\Controllers\Admin\MeWo;

use App\Http\Controllers\Controller;
use App\Models\Proposition;
use App\Services\MeWo\PropositionClassifier;
use Illuminate\Http\Request;

class ClassificationController extends Controller
{
    public function index()
    {
        return view('admin.mewo.classification.index', ['sentence' => '', 'suggestions' => []]);
    }

    public function proposition(Request $request, Proposition $proposition, PropositionClassifier $classifier)
    {
        abort_unless($proposition->actor?->identity?->user_id === $request->user()->id, 404);
        return view('admin.mewo.classification.index', [
            'sentence' => $proposition->sentence, 'suggestions' => $classifier->suggest($proposition->sentence),
        ]);
    }

    public function analyze(Request $request, PropositionClassifier $classifier)
    {
        $data = $request->validate(['sentence' => ['required', 'string', 'max:5000']]);
        return view('admin.mewo.classification.index', ['sentence' => $data['sentence'], 'suggestions' => $classifier->suggest($data['sentence'])]);
    }
}
