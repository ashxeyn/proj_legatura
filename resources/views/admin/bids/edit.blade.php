@extends('admin.layouts.app')
@section('title','Edit Bid '.$bid->bid_id)
@section('content')

<h2>Edit Bid #{{ $bid->bid_id }}</h2>

<p><b>Project:</b> {{ $project->project_title }}</p>
<p><b>Contractor:</b> {{ $contractor->company_name }}</p>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form method="post" action="{{ route('admin.bids.update', $bid->bid_id) }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Proposed Cost</label>
        <input type="number" step="0.01" name="proposed_cost" class="form-control" value="{{ old('proposed_cost', $bid->proposed_cost) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Estimated Timeline (days)</label>
        <input type="number" name="estimated_timeline" class="form-control" value="{{ old('estimated_timeline', $bid->estimated_timeline) }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Bid Status</label>
        <select name="status" class="form-control" required>
            <option value="submitted" {{ $bid->bid_status=='submitted'?'selected':'' }}>Submitted</option>
            <option value="under_review" {{ $bid->bid_status=='under_review'?'selected':'' }}>Under Review</option>
            <option value="accepted" {{ $bid->bid_status=='accepted'?'selected':'' }}>Accepted</option>
            <option value="rejected" {{ $bid->bid_status=='rejected'?'selected':'' }}>Rejected</option>
            <option value="withdrawn" {{ $bid->bid_status=='withdrawn'?'selected':'' }}>Withdrawn</option>
        </select>
    </div>

    <button class="btn btn-primary" type="submit">Update Bid</button>
    <a href="{{ route('admin.bids.show', $bid->bid_id) }}" class="btn btn-secondary">Cancel</a>
</form>

@endsection
