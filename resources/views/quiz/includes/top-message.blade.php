@if ($errors->any())
    <div class="alert alert-danger">
        <p>{!! implode("<br>", $errors->all()); !!}</p>
    </div>
{{--    <mv-alert message="{!! implode("<br>", $errors->all()); !!}" type="danger"></mv-alert>--}}
@endif

@if(session()->has('message'))
    <div class="col-md-12 alert alert-success">
        <p>{{ session()->has('message_type') ? session('message_type') : 'success' }}</p>
    </div>
{{--    <mv-alert message="{{ session('message') }}"--}}
{{--              type="{{ session()->has('message_type') ? session('message_type') : 'success' }}"></mv-alert>--}}
@endif
