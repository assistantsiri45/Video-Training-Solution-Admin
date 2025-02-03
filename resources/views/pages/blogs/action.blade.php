<div class="float-right">
    <a class="button-preview" href="{{ route('blogs.preview', $id) }}"><i class="fas fa-eye"></i></a>
    @if ($is_published)
        <a class="button-publish" href="{{ route('blogs.publish', $id) }}"><i class="fas fa-download ml-2"></i></a>
    @else
        <a class="button-publish" href="{{ route('blogs.publish', $id) }}"><i class="fas fa-upload ml-2"></i></a>
    @endif
    <a href="{{ route('blogs.edit', $id) }}"><i class="fas fa-edit ml-2"></i></a>
    <a class="button-delete" href="{{ route('blogs.destroy', $id) }}"><i class="fas fa-trash ml-2"></i></a>
</div>
