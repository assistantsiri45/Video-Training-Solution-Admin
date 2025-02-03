<!-- <script src="{{ asset('quiz/js/app.js') }}"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/tinymce.min.js"></script>

        <script>

    $(document).ready(function () {
        $('.select2-hidden-accessible').select2()
        setTimeout(function (){
            $('.status-block').hide();
        }, 5000)
                    $("#name").keypress(function (event) {
                        var inputValue = event.which;
                        //Allow letters, white space, backspace and tab.
                        //Backspace ASCII = 8
                        //Tab ASCII = 9
                        if (!(inputValue >= 65 && inputValue <= 123)
                            && (inputValue != 32 && inputValue != 0)
                            && (inputValue != 48 && inputValue != 8)
                            && (inputValue != 9)){
                                event.preventDefault();
                        }
                        console.log(inputValue);
                    });
                 });

    $(document).ready(function () {
  var table = $('#admin-datatable').DataTable({
    "ordering": false,
    // responsive: true,
    "scrollX": true,
    autoWidth: false // dom: 'Blfrtip',
    // dom: '<"wrapper"Blfrtip>',
    // buttons: [
    //     'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
    // ]

  });
  table.buttons().container().appendTo($('.col-sm-6:eq(0)', table.table().container()));
  $('.zoom-img').magnificPopup({
    type: 'image',
    removalDelay: 300,
    mainClass: 'mfp-fade'
  });
  inputmask__WEBPACK_IMPORTED_MODULE_0___default()().mask("input");
  $('.select2').select2({
    theme: 'bootstrap4'
  });
  tinymce.init({
    selector: 'textarea.tinymce-editor',
    plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
    imagetools_cors_hosts: ['picsum.photos'],
    menubar: 'file edit view insert format tools table help',
    toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
    toolbar_sticky: true,
    autosave_ask_before_unload: true,
    autosave_interval: '30s',
    autosave_prefix: '{path}{query}-{id}-',
    autosave_restore_when_empty: false,
    autosave_retention: '2m',
    image_advtab: true,
    link_list: [{
      title: 'My page 1',
      value: 'http://www.tinymce.com'
    }, {
      title: 'My page 2',
      value: 'http://www.moxiecode.com'
    }],
    image_list: [{
      title: 'My page 1',
      value: 'http://www.tinymce.com'
    }, {
      title: 'My page 2',
      value: 'http://www.moxiecode.com'
    }],
    image_class_list: [{
      title: 'None',
      value: ''
    }, {
      title: 'Some class',
      value: 'class-name'
    }],
    importcss_append: true,
    file_picker_callback: function file_picker_callback(callback, value, meta) {
      /* Provide file and text for the link dialog */
      if (meta.filetype === 'file') {
        callback('https://www.google.com/logos/google.jpg', {
          text: 'My text'
        });
      }
      /* Provide image and alt text for the image dialog */


      if (meta.filetype === 'image') {
        callback('https://www.google.com/logos/google.jpg', {
          alt: 'My alt text'
        });
      }
      /* Provide alternative source and posted for the media dialog */


      if (meta.filetype === 'media') {
        callback('movie.mp4', {
          source2: 'alt.ogg',
          poster: 'https://www.google.com/logos/google.jpg'
        });
      }
    },
    templates: [{
      title: 'New Table',
      description: 'creates a new table',
      content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'
    }, {
      title: 'Starting my story',
      description: 'A cure for writers block',
      content: 'Once upon a time...'
    }, {
      title: 'New list with dates',
      description: 'New List with dates',
      content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'
    }],
    template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
    template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
    height: 400,
    image_caption: true,
    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
    noneditable_noneditable_class: 'mceNonEditable',
    toolbar_mode: 'sliding',
    contextmenu: 'link image imagetools table',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
  });
});
        function getGrade(){
            // alert(1);
            var token = $('input[name=_token]').val();
            var board = $('#board option:selected').val();
            // alert(board);
            $.ajax({
                url: "{{ route('quiz.getGrade') }}",
                type: "POST",
                data: {
                        _token:token,
                        board:board
                        },
                success: function(response) {
                    var html = '<option value="">Select</option>';

                     $.each(response, function (key, val) {
                        // alert(key);
                           var selected = '' == key ? "selected" : "";
                           html += '<option value="'+key+'" '+selected+'>'+val+'</option>';
                      });
                     console.log(html);
                     $('#grade').html(html);
                     $('#subject').html('<option value="">Select</option>');
                     $('#chapter').html('<option value="">Select</option>');
                     // $('#concept').html('<option value="">Select</option>');
                }
            });
        }

        function getSubject(){
            // alert(1);
            var token = $('input[name=_token]').val();
            var grade = $('#grade option:selected').val();
            $.ajax({
                url: "{{ route('quiz.getSubject') }}",
                type: "POST",
                data: {
                        _token:token,
                        grade:grade
                        },
                success: function(response) {
                    var html = '<option value="">Select</option>';

                     $.each(response, function (key, val) {
                           var selected = '' == key ? "selected" : "";
                           html += '<option value="'+key+'" '+selected+'>'+val+'</option>';
                      });
                     $('#subject').html(html);
                     $('#chapter').html('<option value="">Select</option>');
                     // $('#concept').html('<option value="">Select</option>');
                }
            });
        }

        function getChapter(){
            var token   = $('input[name=_token]').val();
            var subject = $('#subject option:selected').val();
            $.ajax({
                url: "{{ route('quiz.getChapter') }}",
                type: "POST",
                data: {
                        _token:token,
                        subject:subject
                        },
                success: function(response) {
                    var html = '<option value="">Select</option>';

                     $.each(response, function (key, val) {
                           var selected = '' == val.id ? "selected" : "";
                           html += '<option value="'+val.id+'" '+selected+'>'+val.name+'</option>';
                      });
                     $('#chapter').html(html);
                     // $('#concept').html('<option value="">Select</option>');
                }
            });
        }

        function getConcept(){
            var token   = $('input[name=_token]').val();
            var chapter = $('.chapter option:selected').val();
            $.ajax({
                url: "{{ route('quiz.getConcept') }}",
                type: "POST",
                data: {
                    _token:token,
                    chapter:chapter
                },
                success: function(response) {
                    var html = '<option value="">Select</option>';

                    $.each(response, function (key, val) {
                        var selected = '' == val.id ? "selected" : "";
                        html += '<option value="'+val.id+'" '+selected+'>'+val.name+'</option>';
                    });
                    $('#concept').html(html);
                }
            });
        }

        function getModules(){
            var token   = $('input[name=_token]').val();
            var concept = $('.concept option:selected').val();
            $.ajax({
                url: "{{ route('quiz.getModules') }}",
                type: "POST",
                data: {
                    _token:token,
                    concept:concept
                },
                success: function(response) {
                    var html = '<option value="">Select</option>';

                    $.each(response, function (key, val) {
                        var selected = '' == val.id ? "selected" : "";
                        html += '<option value="'+val.id+'" '+selected+'>'+val.name+'</option>';
                    });
                    $('#module').html(html);
                }
            });
        }
        function confirmAlert(url)
        {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false
          },
          function(){
            swal("Deleted!", "Your data  has been deleted!", "success");
          if(url)
          {
            window.location = url;
          }
          });
        }
        </script>