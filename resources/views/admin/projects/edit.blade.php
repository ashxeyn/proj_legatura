@extends('admin.layouts.app')
@section('title','Edit Project')
@section('content')
<h2>Edit Project</h2>
<form method="post" action="{{ route('admin.projects.update', $project->project_id) }}">
  @csrf
  <div class="mb-2"><label>Title</label><input name="project_title" value="{{ $project->project_title }}" class="form-control"></div>
  <div class="mb-2"><label>Description</label><textarea name="project_description" class="form-control">{{ $project->project_description }}</textarea></div>
  <div class="mb-2">
    <label>Budget (Min)</label>
    <input name="budget_min" value="{{ $project->budget_range_min }}" class="form-control">
</div>

<div class="mb-2">
    <label>Budget (Max)</label>
    <input name="budget_max" value="{{ $project->budget_range_max }}" class="form-control">
</div>

  <div class="mb-2"><label>Location</label><input name="project_location" value="{{ $project->project_location }}" class="form-control"></div>
  <button class="btn btn-primary">Save</button>
</form>

<h4 class="mt-4">Files</h4>
<div class="row">
@foreach($files as $f)
  <div class="col-md-3"><a href="{{ asset($f->file_path) }}" target="_blank">{{ basename($f->file_path) }}</a></div>
@endforeach
</div>

<h4 class="mt-4">Milestones</h4>
<table class="table">
  <thead>
    <tr>
      <th>#</th>
      <th>Milestone Name</th>
      <th>Description</th>
      <th>Assigned Contractor</th>
      <th>Status</th>
      <th>Duration</th>
    </tr>
  </thead>
  <tbody>

@foreach($milestones as $m)
<tr>
  <td>{{ $loop->iteration }}</td>
  <td>{{ $m->milestone_name }}</td>
  <td>{{ $m->milestone_description }}</td>
  <td>{{ $m->contractor_id }}</td>
  <td>{{ $m->milestone_status }}</td>
  <td>{{ $m->start_date }} — {{ $m->end_date }}</td>
</tr>
@endforeach

</tbody></table>

<div class="mt-3">
  <form method="post" action="{{ route('admin.projects.freeze', $project->project_id) }}">@csrf
    <input name="reason" class="form-control" placeholder="Reason to freeze"><button class="btn btn-warning mt-2">Freeze</button>
  </form>
  <form method="post" action="{{ route('admin.projects.unfreeze', $project->project_id) }}" class="mt-2">@csrf<button class="btn btn-success">Unfreeze</button></form>
</div>
@endsection
