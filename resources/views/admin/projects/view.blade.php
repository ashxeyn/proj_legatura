@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">

    <h2>Project Details</h2>

    <a href="{{ route('admin.projects.edit', $project->project_id) }}" class="btn btn-primary mb-3">Edit Project</a>

    <div class="card mb-4">
        <div class="card-header"><strong>Basic Information</strong></div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $project->project_id }}</p>
            <p><strong>Title:</strong> {{ $project->project_title }}</p>
            <p><strong>Description:</strong> {{ $project->project_description }}</p>
           <p><strong>Location:</strong> {{ $project->project_location }}</p>
              <p><strong>Budget Range:</strong> {{ $project->budget_range_min }} - {{ $project->budget_range_max }}</p>
            <p><strong>Status:</strong> {{ $project->project_status }}</p>
            <p><strong>Owner:</strong> {{ $project->first_name }} {{ $project->last_name }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>Uploaded Files</strong></div>
        <div class="card-body">
            <div class="row">
                @forelse($files as $f)
                    <div class="col-md-3 mb-3">
                        <a href="{{ asset($f->file_path) }}" target="_blank">{{ basename($f->file_path) }}</a>
                    </div>
                @empty
                    <p>No files uploaded.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card mb-4">
    <div class="card-header"><strong>Bids</strong></div>
    <div class="card-body">
        @forelse($bids as $b)
            <div class="border p-3 mb-2">
                <p><strong>No.:</strong> {{ $b->bid_id }}</p>
                <p><strong>Contractor:</strong> {{ $b->contractor_id }}</p>
                <p><strong>Amount:</strong> ₱{{ number_format($b->proposed_cost, 2) }}</p>
                <p><strong>Status:</strong> {{ $b->bid_status }}</p>
                <small>Submitted: {{ $b->submitted_at }}</small>

                <div class="mt-2">
                    <a class="btn btn-sm btn-primary"
                       href="{{ route('admin.bids.show', $b->bid_id) }}">
                        View Bid
                    </a>
                </div>
            </div>
        @empty
            <p>No bids submitted yet.</p>
        @endforelse
    </div>
</div>


    <div class="card mb-4">
        <div class="card-header"><strong>Milestones</strong></div>
        <div class="card-body">
            @forelse($milestones as $m)
                <div class="border p-3 mb-2">
                    <strong>{{ $m->milestone_name }}</strong>
                    {{ $m->milestone_description }}<br>
                    <small>Sequence: {{ $m->milestone_id }}</small>
                </div>
            @empty
                <p>No milestones available.</p>
            @endforelse
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>Progress Updates</strong></div>
        <div class="card-body">
            @forelse($updates as $u)
                <div class="border p-3 mb-2">
                    <p>{{ $u->remarks ?? $u->description ?? $u->comment ?? '-' }}</p>
                    @if($u->file_path)
                        <a href="{{ asset($u->file_path) }}" target="_blank">View Attachment</a>
                    @endif
                    <br><small>{{ $u->created_at }}</small>
                </div>
            @empty
                <p>No updates posted.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
