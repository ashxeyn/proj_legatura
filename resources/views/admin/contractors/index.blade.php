@extends('admin.layouts.app')
@section('title','Contractor Verification')
@section('content')
<h2>Contractors</h2>
<table class="table">
<thead><tr><th>ID</th><th>Company</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
@foreach($contractors as $c)
<tr>
  <td>{{ $c->contractor_id }}</td>
  <td>{{ $c->company_name }}</td>
  <td>{{ $c->verification_status }}</td>
  <td><a class="btn btn-sm btn-primary" href="{{ route('admin.contractors.show', $c->contractor_id) }}">View</a></td>
</tr>
@endforeach
</tbody>
</table>
{{ $contractors->links() }}
@endsection
