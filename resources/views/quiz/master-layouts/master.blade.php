<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>iQurius | Dashboard</title>
        <link rel="icon" href="{{ asset('backend/dist/img/favicon.jpg') }}" sizes="16x16">
        <link rel="stylesheet" href="{{ asset('backend/css/app.css') }}">

        <!-- css swal -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js" integrity="sha512-MqEDqB7me8klOYxXXQlB4LaNf9V9S0+sG1i8LtPOYmHqICuEZ9ZLbyV3qIfADg2UJcLyCm4fawNiFvnYbcBJ1w==" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css" integrity="sha512-f8gN/IhfI+0E9Fc/LKtjVq4ywfhYAVeMGKsECzDUHcFJ5teVwvKTqizm+5a84FINhfrgdvjX8hEJbem2io1iTA==" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.js" integrity="sha512-XVz1P4Cymt04puwm5OITPm5gylyyj5vkahvf64T8xlt/ybeTpz4oHqJVIeDtDoF5kSrXMOUmdYewE4JS/4RWAA==" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css" integrity="sha512-hwwdtOTYkQwW2sedIsbuP1h0mWeJe/hFOfsvNKpRB3CkRxq8EW7QMheec1Sgd8prYxGm1OM9OZcGW7/GUud5Fw==" crossorigin="anonymous" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css.map">

        <!-- css -->
        @yield('css')
    </head>

    <body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

        <div class="wrapper" /*id="reactApp"*/>
            @include('backend.includes.navbar')
            @include('backend.includes.sidebar')
            <div class="content-wrapper">
{{--                @include('backend.includes.top-message')--}}
                @include('backend.includes.content-header')
                <div class="container-fluid status-block">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            <ul>
                                <li>{{ session('message') }}</li>
                            </ul>
                        </div>
                    @endif
                </div>
                </div>
                @yield('content.wrapper')
            </div>
            {{-- @include('backend.includes.control-sidebar') --}}
            @include('backend.includes.footer')
        </div>

        <script src="{{ asset('backend/js/app.js') }}"></script>
        <script>

    $(document).ready(function () {

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
        function getGrade(){
            // alert(1);
            var token = $('input[name=_token]').val();
            var board = $('.board option:selected').val();
            $.ajax({
                url: "{{ route('backend.getGrade') }}",
                type: "POST",
                data: {
                        _token:token,
                        board:board
                        },
                success: function(response) {
                    var html = '<option value="">Select</option>';

                     $.each(response, function (key, val) {
                           var selected = '' == val.id ? "selected" : "";
                           html += '<option value="'+val.id+'" '+selected+'>'+val.name+'</option>';
                      });
                     $('#grade').html(html);
                     $('#subject').html('<option value="">Select</option>');
                     $('#chapter').html('<option value="">Select</option>');
                     $('#concept').html('<option value="">Select</option>');
                }
            });
        }

        function getSubject(){
            // alert(1);
            var token = $('input[name=_token]').val();
            var grade = $('.grade option:selected').val();
            $.ajax({
                url: "{{ route('backend.getSubject') }}",
                type: "POST",
                data: {
                        _token:token,
                        grade:grade
                        },
                success: function(response) {
                    var html = '<option value="">Select</option>';

                     $.each(response, function (key, val) {
                           var selected = '' == val.id ? "selected" : "";
                           html += '<option value="'+val.id+'" '+selected+'>'+val.name+'</option>';
                      });
                     $('#subject').html(html);
                     $('#chapter').html('<option value="">Select</option>');
                     $('#concept').html('<option value="">Select</option>');
                }
            });
        }

        function getChapter(){
            var token   = $('input[name=_token]').val();
            var subject = $('.subject option:selected').val();
            $.ajax({
                url: "{{ route('backend.getChapter') }}",
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
                     $('#concept').html('<option value="">Select</option>');
                }
            });
        }

        function getConcept(){
            var token   = $('input[name=_token]').val();
            var chapter = $('.chapter option:selected').val();
            $.ajax({
                url: "{{ route('backend.getConcept') }}",
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
                url: "{{ route('backend.getModules') }}",
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
        @yield('js')
    </body>

</html>
