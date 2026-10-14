<?php

namespace App\Http\Controllers\Admin\MeWo;

use App\Http\Controllers\Controller;
use App\Models\Context;

class ContextController extends Controller
{
    public function index()
    {
        return view('admin.mewo.contexts.index', [
            'contexts' => Context::with(['components', 'mewoProcesses.verbs'])->orderBy('id')->get(),
        ]);
    }
}
