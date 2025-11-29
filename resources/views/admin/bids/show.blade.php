@extends('admin.layouts.app')
@section('title','Bid '.$bid->bid_id)
@section('content')
<h2>Bid #{{ $bid->bid_id }}</h2>

<p><b>Project:</b> {{ $project->project_title }}</p>
<p><b>Contractor:</b> {{ $contractor->company_name }}</p>
<p><b>Amount:</b> {{ number_format($bid->proposed_cost,2) }}</p>
<p><b>Timeline:</b> {{ $bid->estimated_timeline }} days</p>
<p><b>Status:</b> {{ $bid->bid_status }}</p>
<p><b>Submitted At:</b> {{ $bid->submitted_at }}</p>

<h4>Attachments</h4>
<div class="row">
@foreach($attachments as $a)
  <div class="col-md-3">
    <a href="{{ asset($a->file_path) }}" target="_blank">{{ basename($a->file_path) }}</a>
  </div>
@endforeach
</div>

<hr>
<div class="d-flex gap-2">
    <a class="btn btn-success" href="{{ route('admin.bids.edit', $bid->bid_id) }}">Edit</a>

    <form method="post" action="{{ route('admin.bids.delete', $bid->bid_id) }}">
        @csrf
        <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Remove</button>
    </form>

    <form method="post" action="{{ route('admin.bids.assign', $bid->bid_id) }}">
        @csrf
        <button class="btn btn-primary" onclick="return confirm('Force assign this bid to project?')">Force Assign</button>
    </form>
</div>
@endsection
