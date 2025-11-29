@extends('admin.layouts.app')
@section('title','Dispute Actions')
@section('content')
<h2>Dispute #{{ $dispute->id ?? '' }}</h2>

<form method="post" action="{{ route('admin.disputes.request_evidence', $dispute->id) }}">@csrf
  <textarea name="note" class="form-control" placeholder="Request more evidence (message to parties)"></textarea>
  <button class="btn btn-info mt-2">Request Evidence</button>
</form>

<hr>

<form method="post" action="{{ route('admin.disputes.issue_ruling', $dispute->id) }}">@csrf
  <select name="ruling" class="form-control mb-2">
    <option value="owner">Favor Owner</option>
    <option value="contractor">Favor Contractor</option>
    <option value="split">Split</option>
  </select>
  <textarea name="notes" class="form-control" placeholder="Notes (optional)"></textarea>
  <button class="btn btn-success mt-2">Issue Ruling</button>
</form>

<hr>

<form method="post" action="{{ route('admin.projects.freeze', $dispute->project_id) }}">@csrf
  <input name="reason" class="form-control" placeholder="Reason to freeze project">
  <button class="btn btn-warning mt-2">Freeze Project</button>
</form>

<form method="post" action="{{ route('admin.projects.unfreeze', $dispute->project_id) }}" class="mt-2">@csrf<button class="btn btn-success">Unfreeze Project</button></form>
@endsection
