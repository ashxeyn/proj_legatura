<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMilestoneRequest;
use App\Http\Requests\UpdateMilestoneRequest;
use App\Models\Milestone;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int)$request->input('per_page',15);
        $q = Milestone::query();
        if ($project = $request->input('project_id')) {
            $q->where('project_id',$project);
        }
        return response()->json($q->orderBy('milestone_id','desc')->paginate($perPage));
    }

    public function show($id)
    {
        $m = Milestone::find($id);
        if (!$m) return response()->json(['error'=>'Not found'],404);
        return response()->json($m);
    }

    public function store(StoreMilestoneRequest $request)
    {
        $m = Milestone::create($request->validated());
        return response()->json($m,201);
    }

    public function update(UpdateMilestoneRequest $request, $id)
    {
        $m = Milestone::find($id);
        if (!$m) return response()->json(['error'=>'Not found'],404);
        $m->update($request->validated());
        return response()->json($m);
    }

    public function destroy($id)
    {
        $m = Milestone::find($id);
        if (!$m) return response()->json(['error'=>'Not found'],404);
        $m->delete();
        return response()->json(['deleted'=>true]);
    }
}
