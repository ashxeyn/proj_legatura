<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $q = Project::query();

        if ($search = $request->input('search')) {
            $q->where('project_title', 'like', "%{$search}%");
        }

        $projects = $q->orderBy('project_id', 'desc')->paginate($perPage);
        return response()->json($projects);
    }

    public function show($id)
    {
        $project = Project::find($id);
        if (!$project) return response()->json(['error' => 'Not found'], 404);
        return response()->json($project);
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        $project = Project::create($data);
        return response()->json($project, 201);
    }

    public function update(UpdateProjectRequest $request, $id)
    {
        $project = Project::find($id);
        if (!$project) return response()->json(['error' => 'Not found'], 404);
        $project->update($request->validated());
        return response()->json($project);
    }

    public function destroy($id)
    {
        $project = Project::find($id);
        if (!$project) return response()->json(['error' => 'Not found'], 404);
        $project->delete();
        return response()->json(['deleted' => true]);
    }
}
