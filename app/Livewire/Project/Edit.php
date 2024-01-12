<?php

namespace App\Livewire\Project;

use App\Models\Project;
use Livewire\Component;

class Edit extends Component
{
    public Project $project;
    protected $rules = [
        'project.name' => 'required|min:3|max:255',
        'project.description' => 'nullable|string|max:255',
    ];
    public function mount() {
        $projectUuid = request()->route('project_uuid');
        $teamId = currentTeam()->id;
        $project = Project::where('team_id', $teamId)->where('uuid', $projectUuid)->first();
        if (!$project) {
            return redirect()->route('dashboard');
        }
        $this->project = $project;
    }

    public function submit()
    {
        $this->validate();
        try {
            $this->project->save();
            $this->dispatch('saved');
        } catch (\Throwable $e) {
            return handleError($e, $this);
        }
    }
}