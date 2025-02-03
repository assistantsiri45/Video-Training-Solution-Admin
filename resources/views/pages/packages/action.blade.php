<div class="text-right">
    <a href="{{ url('packages/prebook-package', $id) }}"  class="text-info">Prebook</a>
    @if (! $is_approved)
        <a href="{{ route('packages.publish', $id) }}" class="btn-publish"><i class="fas fa-user-check ml-3"></i></a>
        @if ($type == 2 || $type == 3)
            <a href="@if ($type == 2) {{ route('packages.subject.edit', $id) }} @endif @if ($type == 3) {{ route('packages.customize.edit', $id) }} @endif" ><i class="fas fa-edit ml-3"></i></a>
        @endif
        @if ($type == 1)
            <a href="{{ route('packages.chapter.edit', $id) }}" ><i class="fas fa-edit ml-3"></i></a>
        @endif
        <a href="{{ route('packages.destroy', $id) }}" class="text-danger btn-delete"><i class="fas fa-trash ml-3"></i></a>
    @else
        @if ($type == 2 || $type == 3)
            <a href="@if ($type == 2) {{ route('packages.subject.edit', $id) }} @endif @if ($type == 3) {{ route('packages.customize.edit', $id) }} @endif" ><i class="fas fa-edit ml-3"></i></a>
        @endif
        @if ($type == 1)
            <a href="{{ route('packages.chapter.edit', $id) }}" ><i class="fas fa-edit ml-3"></i></a>
        @endif
        <a href="{{ route('packages.un-publish', $id) }}" class="btn-un-publish"><i class="fas fa-backspace ml-3" title="Un-Publish"></i></a>
        <a href="{{ url('package-study-materials', $id) }}" class="text-info pl-3">Add Study Materials</a>
    @endif
</div>
