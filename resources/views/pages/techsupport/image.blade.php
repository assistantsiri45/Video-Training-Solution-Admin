<style>
    .image_modal{
         max-width: 80% !important;   
    }

    .close_btn{
        background: #fff !important;
        border-radius: 50%;
        height: 30px;
        width: 30px;
        padding: 5px 7px !important;
        opacity: 1;
    }
    .close_btn span{
        color: #dc3545;
        line-height: 16px;
        font-size: 30px;
    }
</style>
@php 
$allowed =  array('jpeg','jpg', "png", "gif", "bmp", "JPEG","JPG", "PNG", "GIF", "BMP","webp");
$allowed_doc = array('csv','pdf','doc','docx','xls','xlsx');
@endphp
@foreach($attachments as $attachment)
<?php
$att = $attachment->attachment;
$ext = pathinfo($att, PATHINFO_EXTENSION);
?>
@if(in_array($ext,$allowed))
<span><img src="{{env('WEB_URL')}}/screenshots/{{$attachment->attachment}}" class="rounded-square q_img" width="200" height="120" id="imageresource_{{$attachment->id}}"></span>
<button type="button" id="{{$attachment->id}}" style="border:0"; class="remark" onclick='view(<?= $attachment->id ?>)'><a href="#" class=""><i class="fas fa-eye"></i></a></button><br><br>
@elseif(in_array($ext,$allowed_doc))
<a href="{{env('WEB_URL')}}/screenshots/{{$attachment->attachment}}" target="_blank">{{$attachment->attachment}}</a>
@else
<div id="jw-player">
<input type="hidden" id="video_{{$attachment->id}}" value="{{env('WEB_URL')}}/screenshots/{{$attachment->attachment}}">
<video width="200" height="120" controls>
  <source src="{{env('WEB_URL')}}/screenshots/{{$attachment->attachment}}" id="videoresource_{{$attachment->id}}" type="video/mp4">
</video>
<a title="test" class="popup-iframe" href="{{env('WEB_URL')}}/screenshots/{{$attachment->attachment}}">
  <button type="button" id="{{$attachment->id}}" style="border:0"; class="remark" ><i class="fas fa-eye" id="demo_video"></i></button><br><br>
</a>
                          
</div>

@endif
@endforeach


<!-- Creates the bootstrap modal where the image will appear -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog image_modal">
    <div class="modal-content" style="background: #111;">
      <div class="modal-header" style="border:0;">
        <button type="button" class="close close_btn" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
        <img src="" id="imagepreview" style="width: 100%" >
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>
  </div>
</div>

<script src="https://cdn.jwplayer.com/libraries/rVFAIHjQ.js?exp=1652534100&sig=2766a9f6b5e527e3d9368aa58e343a1c"></script>
<script>
  $(function() {
    if ($(".popup-youtube, .popup-vimeo, .popup-gmaps, .popup-iframe").length) {
      $('.popup-youtube, .popup-vimeo, .popup-gmaps, .popup-iframe').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 3,
        preloader: false,
        closeOnBgClick: false,
        fixedContentPos: false,
        iframe: {
            markup: '<style>.mfp-iframe-holder .mfp-content {max-width: 900px;height:500px}</style>' +
                '<div class="mfp-iframe-scaler" ><div class="mfp-title"></div>' +
                '<div class="mfp-close"></div>' +
                '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                '</div></div>'
        },
      });
    }
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
<script>
  function view(id){
    $('#imagepreview').attr('src', $('#imageresource_'+id).attr('src'));
    $('#imagemodal').modal('show');
  }

    
</script>