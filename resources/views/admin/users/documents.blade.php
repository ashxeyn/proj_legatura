<div class="row">
@foreach($docs as $d)
  <div class="col-md-3 mb-2">
    <a href="{{ asset($d->file_path) }}" target="_blank">{{ basename($d->file_path) }}</a>
    <p>{{ $d->doc_type ?? 'Document' }}</p>
  </div>
@endforeach
</div>
