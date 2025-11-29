@extends('admin.layouts.app')
@section('title','User #'.$user->id)
@section('content')
<h2>{{ $user->first_name }} {{ $user->last_name }} ({{ $user->role }})</h2>
<p><b>Email:</b> {{ $user->email }}</p>
<p><b>Status:</b> {{ $user->status }}</p>

<div class="mb-3">
  <form method="post" action="{{ route('admin.users.verify', $user->id) }}">@csrf
    <button name="action" value="approve" class="btn btn-success">Approve</button>
    <button name="action" value="reject" class="btn btn-danger">Reject</button>
    <input name="notes" class="form-control mt-2" placeholder="Notes (optional)">
  </form>
</div>

<div class="mb-3">
  <form method="post" action="{{ route('admin.users.reset_password', $user->id) }}">@csrf
    <button class="btn btn-warning">Reset Password</button>
  </form>
  <form method="post" action="{{ route('admin.users.flag', $user->id) }}" class="d-inline">@csrf
    <input name="reason" placeholder="Flag reason" class="form-control d-inline" style="width:200px">
    <button class="btn btn-outline-danger">Flag</button>
  </form>
</div>

<h4>Documents</h4>
@include('admin.users.documents', ['docs' => $docs])
@endsection
