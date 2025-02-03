@extends('adminlte::page')

@section('title', 'Videos')


@section('content')
    @foreach ($packageVideos as $package => $packageVideo)
        <div class="row">
            <div class="col-md-12">
                <h3>{{ $package }}</h3>
            </div>
        </div>
        <div class="row mt-3 mb-5">
            @foreach ($packageVideo as $video)
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <a class="popup-iframe" href="{{ route('videos.show', $video['id']) }}">
                                <img src="https://cdn.jwplayer.com/v2/media/{{ $video['media_id'] }}/poster.jpg?width=320">
                            </a>
                        </div>
                        <div class="card-footer">
                            <h5>{{ strlen($video['title']) > 20 ? substr($video['title'], 0, 20) . '...' : $video['title'] }}</h5>
                            {{ $video['formatted_duration'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
@stop

@push('js')
    <script>
        $(function () {
            if ($('.popup-iframe').length) {
                $('.popup-iframe').magnificPopup({
                    disableOn: 700,
                    type: 'iframe',
                    mainClass: 'mfp-fade',
                    removalDelay: 160,
                    preloader: false,
                    fixedContentPos: false
                });
            }
        });
    </script>
@endpush
