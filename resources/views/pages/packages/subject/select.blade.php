<div class="custom-control custom-checkbox text-center">
    <input class="custom-control-input select-package" type="checkbox" id="checkbox-package-{{ $id }}" name="packages[]" value="{{ $id }}" @if ($subject_package) checked @endif>
    <label for="checkbox-package-{{ $id }}" class="custom-control-label"></label>
</div>
