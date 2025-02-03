<div class="text-right">
        <a href="{{ route('package-reports.show', $id) }}"><i class="fas fa-eye ml-3"></i></a>
        <a class="modal-package-edit" href="#modal-edit-package" data-toggle="modal" data-id="{{ $id }}"
           data-duration="{{ $duration }}" data-expire-at="{{ \Carbon\Carbon::parse($expire_at)->toDateString() }}"><i class="fas fa-pencil-alt ml-3"></i></a>
</div>
