<?php

namespace App\Services\MeWo;

use App\Models\Activity;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class WorkAccess
{
    public function projects(Request $request): Builder
    {
        return Project::whereIn('system_id', $request->user()->systems()->with('identity')->get()->filter(fn($system)=>app(ActorAccess::class)->canEnter($request->user(),$system))->modelKeys());
    }

    public function activity(Request $request, Activity $activity): void
    {
        abort_unless($this->projects($request)->whereKey($activity->project_id)->exists(), 404);
    }
}
