<body style="margin: 0; padding: 0;">
    <link href="{{ asset('vendor/video-js/video-js.min.css') }}" rel="stylesheet">
    <div id="videoContainer" style="position: relative; height: 100vh;">
        <video id="s3Video" class="video-js vjs-default-skin" controls style="width: 100%; height: 100%;">
            <source src="{{ $signedUrl }}" <?php if($extension == "m3u8") { ?>type="application/x-mpegURL"<?php } else { ?>type="video/mp4"<?php } ?>>
        </video>
    </div>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/video-js/video.min.js') }}"></script>
    <script src="{{ asset('vendor/video-js/videojs-contrib-hls.js') }}"></script>
    <script src="{{ asset('vendor/video-js/videojs-resolution-switcher.js') }}"></script>
    <script>
      $(document).ready(function() {
        var video = videojs('s3Video',{
            plugins: {
                videoJsResolutionSwitcher: {
                    default: 'auto',
                    dynamicLabel: true
                }
            },
            <?php if(!empty($res_src)){?>
            sources: <?php echo $res_src; ?>
            <?php } ?>
        });
        video.on('loadedmetadata', function() {
            var savedDuration = {{$videoDuration ?? 0}};
            // if duration is saved then return    
            if (savedDuration > 0){ return false;} 
            // otherwise save duration in seconds and ask inform the parent
            var duration = Math.floor(video.duration());
            $.ajax({
                url: "{{ route('api.s3videos.updateDuration.index', $videoId) }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    duration: duration,
                },
                success: function(response) {
                    parent.postMessage({
                        type: response.success,
                        duration: response.duration,
                        videoId: response.videoId
                    }, '*');
                }
            });
        });
      });
    </script>
  </body>
  