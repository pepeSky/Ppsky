<?php

namespace App\Http\Controllers\Welcome;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Development;

class WelcomeController extends Controller
{
    public function __invoke(){

        $developments = Development::where('status', '1')->latest('id')->get()->take(12);

        return view('welcome', compact('developments'));
    }
}
