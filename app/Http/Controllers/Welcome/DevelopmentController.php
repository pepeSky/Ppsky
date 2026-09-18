<?php

namespace App\Http\Controllers\Welcome;

use App\Http\Controllers\Controller;
use App\Models\Development;
use Illuminate\Http\Request;

class DevelopmentController extends Controller
{
    public function index(){

        $developments = Development::all();

        return view('welcome.developments.index',compact('developments'));
    }

    public function show(){
        return view('welcome.developments.show');
    }
}
