<?php

namespace App\Http\Controllers\welcome;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Level;
use App\Models\Modules\Subjects\Science;
use App\Models\Modules\Subjects\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(){

        $sciences = Science::all();
        $levels = Level::all();
        $categories = Category::all();
        $subjects = Subject::all();


        return view('welcome.subjects.index', compact('sciences', 'levels', 'subjects'));
    }
}
