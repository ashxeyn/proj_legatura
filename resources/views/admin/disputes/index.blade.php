@extends('admin.layouts.app')
@section('title','Disputes')
@section('content')
<h2>Disputes</h2>
<table class="table">
  <thead><tr><th>ID</th><th>Project</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
  <tbody>
    @foreach($disputes as $d)
      <tr>
        <td>{{ $d->id }}</td>
        <td>{{ $d->project_title }}</td>
        <td>{{ $d->dispute_status }}</td>
        <td>{{ $d->created_at }}</td>
        <td>
          <a class="btn btn-sm btn-primary" href="{{ route('admin.disputes.show', $d->id) }}">View</a>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

{{ $disputes->links() }}
@endsection
