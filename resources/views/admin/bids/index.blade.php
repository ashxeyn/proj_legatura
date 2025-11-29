@extends('admin.layouts.app')
@section('title','Bids')
@section('content')
<h2>Bids</h2>
<form method="get" class="mb-3 row">
  <div class="col-md-2"><input name="project_id" value="{{ request('project_id') }}" class="form-control" placeholder="Project ID"></div>
  <div class="col-md-2"><input name="contractor_id" value="{{ request('contractor_id') }}" class="form-control" placeholder="Contractor ID"></div>
  <div class="col-md-3"><input name="q" value="{{ request('q') }}" class="form-control" placeholder="search project title"></div>
  <div class="col-md-1"><button class="btn btn-primary">Filter</button></div>
</form>

<table class="table">
<thead><tr><th>Bid ID</th><th>Project</th><th>Contractor</th><th>Amount</th><th>Actions</th></tr></thead>
<tbody>
@foreach($bids as $b)
<tr>
  <td>{{ $b->bid_id }}</td>
  <td>{{ $b->project_title }}</td>
  <td>{{ $b->company_name }}</td>
  <td>{{ number_format($b->proposed_cost,2) }}</td>
  <td>
    <a class="btn btn-sm btn-primary" href="{{ route('admin.bids.show', $b->bid_id) }}">View</a>
    <form method="post" action="{{ route('admin.bids.delete', $b->bid_id) }}" style="display:inline">@csrf<button class="btn btn-sm btn-danger">Remove</button></form>
  </td>
</tr>
@endforeach
</tbody>
</table>
{{ $bids->links() }}
@endsection
