<head>
    <link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">
    <style type="text/css">
        body {margin: 0; padding: 0; min-width: 100%!important; font-family: 'Ubuntu', sans-serif;}
        img {height: auto;}
        .content {width: 100%; max-width: 660px;}
        .header {padding: 40px 30px 20px 30px;}
        .innerpadding {padding: 30px 30px 30px 30px; line-height: 25px;}
        .borderbottom {border-bottom: 1px solid #f2eeed;}
        .subhead {font-size: 15px; color: #ffffff; letter-spacing: 10px;}
        .h1, .h2, .bodycopy {color: #153643;}
        .h1 {font-size: 33px; line-height: 38px; font-weight: bold;}
        .h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
        .bodycopy {font-size: 16px; line-height: 22px;}
        .button {text-align: center; font-size: 18px; font-weight: bold; padding: 0 30px 0 30px;}
        .button a {color: #ffffff; text-decoration: none;}
        .footer {padding: 20px 30px 20px 30px;}
        .footercopy {font-size: 14px; color: #ffffff;}
        .footercopy a {color: #ffffff; text-decoration: underline;}
        @media  only screen and (max-width: 550px), screen and (max-device-width: 550px) {
            body[yahoo] .hide {display: none!important;}
            body[yahoo] .buttonwrapper {background-color: transparent!important;}
            body[yahoo] .button {padding: 0px!important;}
            body[yahoo] .button a {background-color: #effb41; padding: 15px 15px 13px!important;}
            body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}
        }
    </style>
</head>
<body>
<table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
    <tbody><tr>
        <td  class="header">
            <table width="70" align="center" border="0" cellpadding="0" cellspacing="0">
                <tbody><tr>
                    <td height="70" style="padding: 0 20px 20px 0;">
                        <a href="{{ $user['web'] }}"><img class="fix" src="<?php  echo $message->embed($user['logo']); ?>" width="150" border="0" alt=""></a>
                    </td>
                </tr>
                </tbody></table>
        </td>
    </tr>
    <tr>
    </tr>
   
    <tr>
        <td class="innerpadding borderbottom" style="padding-top: 0px;">
            Hi {{$user['name']}},
            <br>
            {!! nl2br($user['body']) !!}
            <div class="border w-100" id="editorjs"></div>
        </td>
    </tr>
    <tr>
       
    </tr>
    </tbody>
</table>
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

@include('adminlte::plugins', ['type' => 'js'])
<script>
    !function(t,e){"object"==typeof exports&&"undefined"!=typeof module?module.exports=e():"function"==typeof define&&define.amd?define(e):(t=t||self).edjsHTML=e()}(this,(function(){"use strict";var t={delimiter:function(){return"<br/>"},header:function(t){var e=t.data;return"<h"+e.level+"> "+e.text+" </h"+e.level+">"},paragraph:function(t){return"<p> "+t.data.text+" </p>"},list:function(t){var e=t.data,n="unordered"===e.style?"ul":"ol",r="";return e.items&&(r=e.items.map((function(t){return"<li> "+t+" </li>"})).reduce((function(t,e){return t+e}),"")),"<"+n+"> "+r+" </"+n+">"},image:function(t){var e=t.data,n=e.caption?e.caption:"Image";return'<img src="'+(e.file?e.file.url:"")+'" alt="'+n+'" />'},quote:function(t){var e=t.data;return"<blockquote> "+e.text+" </blockquote> - "+e.caption}};function e(t){return new Error('[31m The Parser function of type "'+t+'" is not defined. \n\n  Define your custom parser functions as: [34mhttps://github.com/pavittarx/editorjs-html#extend-for-custom-blocks [0m')}return function(n){return void 0===n&&(n={}),Object.assign(t,n),{parse:function(n){return n.blocks.map((function(n){return t[n.type]?t[n.type](n):e(n.type)}))},parseBlock:function(n){return t[n.type]?t[n.type](n):e(n.type)}}}}));
</script>
<script>
    $(function () {
        let body = @json($user['body']);
        let data = JSON.parse(body);

        const editor = new EditorJS({
            placeholder: 'Body',
            tools: {
                header: {
                    class: Header,
                    placeholder: 'Header'
                },
                {{--image: {--}}
                {{--    class: ImageTool,--}}
                {{--    config: {--}}
                {{--        endpoints: {--}}
                {{--            byFile: '{{ route('blogs.images.store') }}'--}}
                {{--        }--}}
                {{--    }--}}
                {{--}--}}
            },
            data: data
        });
    });
</script>
</body>
