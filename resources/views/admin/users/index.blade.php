@extends('admin.layouts.app')
@section('title','Users')
@section('content')
<h2>Users</h2>
<form method="get" class="mb-3 row">
  <div class="col-md-2">
    <select name="type" class="form-control">
      <option value="all" {{ $type=='all'?'selected':'' }}>All</option>
      <option value="owner" {{ $type=='owner'?'selected':'' }}>Owners</option>
      <option value="contractor" {{ $type=='contractor'?'selected':'' }}>Contractors</option>
    </select>
  </div>
  <div class="col-md-4"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="search name or email"></div>
  <div class="col-md-2"><select name="status" class="form-control"><option value="">All status</option><option value="active">Active</option><option value="pending">Pending</option><option value="banned">Banned</option></select></div>
  <div class="col-md-1"><button class="btn btn-primary">Filter</button></div>
</form>

<table class="table">
<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
@foreach($users as $u)
<tr>
  <td>{{ $u->id }}</td>
  <td>{{ $u->first_name }} {{ $u->last_name }}</td>
  <td>{{ $u->email }}</td>
  <td>{{ $u->role }}</td>
  <td>{{ $u->status }}</td>
  <td>
    <a class="btn btn-sm btn-primary" href="{{ route('admin.users.show', $u->id) }}">View</a>
    <form method="post" action="{{ route('admin.users.change_status', $u->id) }}" style="display:inline">@csrf
      <select name="status" onchange="this.form.submit()" class="form-control form-control-sm" style="width:auto;display:inline">
        <option value="">Change</option>
        <option value="active">Activate</option>
        <option value="suspended">Suspend</option>
        <option value="banned">Ban</option>
      </select>
    </form>
  </td>
</tr>
@endforeach
</tbody>
</table>
{{ $users->links() }}
@endsection
