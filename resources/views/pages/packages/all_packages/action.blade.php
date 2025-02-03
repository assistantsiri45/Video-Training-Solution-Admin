<div class="text-center">
    <a href="{{ route('packages.show', $package->id) }}" class="btn-publish"><i class="fas fa-eye ml-3"></i></a>
{{--   @if($package->is_archived == 0)--}}
{{--    <a href="{{ route('packages.archive.add', $package->id) }}" title="Add to Archive" class="btn-publish add-to-archived"><i class="fas fa-archive ml-3"></i></a>--}}
{{--    @endif--}}
</div>
