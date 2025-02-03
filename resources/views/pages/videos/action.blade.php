<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
    </button>
    <div class="dropdown-menu">
        @if ($video->chapter_id && ! $video->is_published)
            <a href="{{ route('videos.publish', $video->id) }}" data-id="{{$video->id}}" data-video-url="{{$video->url}}" data-video-duration="{{$video->duration}}" class="btn-publish dropdown-item"><i class="fas fa-user-check"></i> Publish</a>
        @endif
        @if ($video->is_published)
            <a href="{{ route('videos.un-publish', $video->id) }}" class="btn-un-publish dropdown-item"><i class="fas fa-backspace" title="Un-Publish"></i> Un-Publish</a>
        @endif
        @if (! $video->is_published)
            <a href="{{ route('videos.edit', $video->id) }}" class="btn-edit dropdown-item"><i class="fas fa-edit"></i> Edit</a>
        @endif
        @if (! $video->is_published)
            <a class="change-video dropdown-item" href="" data-video-id="{{ $video->id }}" data-media-id="{{ $video->media_id }}"
               data-duration="{{ $video->duration ? \App\Models\Video::formatDuration($video->duration) : null }}">
                <i class="fas fa-exchange-alt"></i> Change Media ID
            </a>
        @endif
        @if (! $video->is_published)
            <a href="{{ route('videos.archive.add', $video->id) }}" class="dropdown-item a-add-to-archive"><i class="fas fa-box"></i> Archive</a>
        @endif
{{--            <a class="popup-iframe dropdown-item" href="{{ route('videos.show', $id) }}">--}}
              <!-- <a class="popup-video dropdown-item" href="" data-toggle="modal" data-target="#demoVideoModal"> -->
              <a class="popup-iframe dropdown-item" href="{{ route('videos.show', $video->id) }}">
            <i class="fas fa-play"></i> Play
        </a>
    </div>
</div>

<div class="modal" id="demoVideoModal"  role="dialog" aria-labelledby="demoVideoModalLabel"  >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="demoVideoModalLabel">Demo Video</h3>
                <button type="button" class="close mr-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div id="demo-video-player-div">
                            <div id="video-play-container" class="position-relative">
                                <div id="video-player" class="video-player-container bg-dark shadow-lg" style="min-height:500px;">
                                </div>
                            </div>

{{--                            <script type='text/javascript' src='{{ $signedUrl }}'></script>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--
<script src="https://cdn.jwplayer.com/libraries/rVFAIHjQ.js?exp=1652534100&sig=2766a9f6b5e527e3d9368aa58e343a1c"></script>
-->
<script src="https://cdn.jwplayer.com/libraries/rVFAIHjQ.js?sig=2766a9f6b5e527e3d9368aa58e343a1c"></script>
<script type="text/javascript">

    $(function () {

        $( "#demoVideoModal" ).on('shown', function(){
            var videoID = '{{ $video->id }}';
            $.ajax({
                async:false,
                url: '{{ url('videos/get-player') }}' + '/' + videoID
            }).done(function(response) {
                jwplayer('video-player').setup({
                    'file': response,
                    'autostart': "viewable"
                });
            });
            });


        // if ($('.popup-iframe').length) {
        //     $('.popup-iframe').magnificPopup({
        //         disableOn: 700,
        //         type: 'iframe',
        //         mainClass: 'mfp-fade',
        //         removalDelay: 160,
        //         preloader: false,
        //         fixedContentPos: false
        //     });
        // }
        //
        // $('.popup-iframe').click( function () {
        //     $('#demoVideoModal').show();
        // })


        {{--var videoID = '{{ $video->id }}';--}}


    });
</script>
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
