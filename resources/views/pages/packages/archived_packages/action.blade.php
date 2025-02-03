<div class="text-center">
    <a href="{{ route('packages.show', $package->id) }}" class="btn-publish"><i class="fas fa-eye ml-3"></i></a>
        <a href="{{ route('packages.archive.remove', $package->id) }}" title="Remove From Archive" class="text-danger remove-from-archived"><i class="fas fa-archive ml-3"></i></a>
</div>
