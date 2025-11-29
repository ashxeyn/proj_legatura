@extends('admin.layouts.app')
@section('title','Projects')
@section('content')
<h2>Projects</h2>
<form method="get" class="mb-3 row">
  <div class="col-md-3"><input name="q" class="form-control" placeholder="Search title" value="{{ request('q') }}"></div>
  <div class="col-md-2"><select name="status" class="form-control"><option value="">All</option><option value="open">Open</option><option value="in_progress">In Progress</option><option value="rejected">Rejected</option></select></div>
  <div class="col-md-1"><button class="btn btn-primary">Filter</button></div>
</form>

<table class="table">
<thead><tr><th>ID</th><th>Title</th><th>Owner</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
@foreach($projects as $p)
<tr>
  <td>{{ $p->project_id }}</td>
  <td>{{ $p->project_title }}</td>
  <td>{{ $p->first_name ?? '' }} {{ $p->last_name ?? ''}}</td>
  <td>{{ $p->project_status }}</td>
  <td>
    <a class="btn btn-sm btn-info" href="{{ route('admin.projects.view', $p->project_id) }}">View</a>
    <a class="btn btn-sm btn-primary" href="{{ route('admin.projects.edit', $p->project_id) }}">Edit</a>
    <form method="post" action="{{ route('admin.projects.delete', $p->project_id) }}" style="display:inline">
        @csrf
        <button class="btn btn-sm btn-danger">Delete</button>
    </form>
</td>

</tr>
@endforeach
</tbody>
</table>
{{ $projects->links() }}
@endsection
