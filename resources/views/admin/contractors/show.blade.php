@extends('admin.layouts.app')
@section('title','Contractor #'.$contractor->contractor_id)
@section('content')
<h2>{{ $contractor->company_name }}</h2>
<p><b>Contact:</b> {{ $contractor->first_name }} {{ $contractor->last_name }}</p>
<p><b>Status:</b> {{ $contractor->verification_status }}</p>

<div class="d-flex gap-2 mb-3">
  <form method="post" action="{{ route('admin.contractors.approve', $contractor->contractor_id) }}">@csrf<button class="btn btn-success">Approve</button></form>
  <form method="post" action="{{ route('admin.contractors.reject', $contractor->contractor_id) }}">@csrf
    <input name="reason" class="form-control" placeholder="Reason"><button class="btn btn-danger mt-2">Reject</button>
  </form>
</div>

<h4>Documents</h4>
@include('admin.users.documents', ['docs'=>$docs])
@endsection
