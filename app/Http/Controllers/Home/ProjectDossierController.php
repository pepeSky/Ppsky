<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Project;

class ProjectDossierController extends Controller
{
    public function show(Project $project)
    {
        $project->load([
            'system.identity.entity',
            'records.actor.identity.entity',
            'records.documents',
            'documents.system.identity.entity',
        ]);

        return view('home.projects.dossier.show', compact('project'));
    }
}
