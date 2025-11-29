@extends('admin.layouts.app')
@section('title','Dispute #'.$dispute->id)
@section('content')
<h2>Dispute #{{ $dispute->id }}</h2>
<p><b>Project ID:</b> {{ $dispute->project_id }}</p>
<p><b>Reason:</b> {{ $dispute->dispute_reason }}</p>
<p><b>Status:</b> {{ $dispute->dispute_status }}</p>

<h4>Evidence</h4>
<div class="row">
  @foreach($evidence as $e)
    <div class="col-md-3">
      @if(stripos($e->file_path, '.pdf') !== false)
        <a href="{{ asset($e->file_path) }}" target="_blank">PDF / Document</a>
      @else
        <img src="{{ asset($e->file_path) }}" class="img-fluid" />
      @endif
      <p>{{ $e->caption }}</p>
    </div>
  @endforeach
</div>

<h4>Messages</h4>
<ul>
  @foreach($messages as $m)
    <li><b>{{ $m->sender_type }} #{{ $m->sender_id }}:</b> {{ $m->message }} <small>{{ $m->created_at }}</small></li>
  @endforeach
</ul>

<form method="post" action="{{ route('admin.disputes.resolve', $dispute->id) }}">@csrf
  <div class="mb-2">
    <select name="action" class="form-control">
      <option value="dismiss">Dismiss</option>
      <option value="refund_owner">Refund Owner</option>
      <option value="penalize_contractor">Penalize Contractor</option>
    </select>
  </div>
  <div class="mb-2">
    <textarea name="notes" class="form-control" placeholder="Resolution notes (optional)"></textarea>
  </div>
  <button class="btn btn-success">Resolve</button>
</form>
@endsection
