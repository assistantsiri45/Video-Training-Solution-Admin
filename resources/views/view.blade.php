<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }
        iframe {
            border: none;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <iframe src="{{ $launchUrl }}" id="scorm-content"></iframe>

    <script>
        // SCORM 1.2 dummy API implementation
        window.API = {
            LMSInitialize: function(param) {
                console.log('LMSInitialize called');
                return 'true';
            },
            LMSFinish: function(param) {
                console.log('LMSFinish called');
                return 'true';
            },
            LMSGetValue: function(name) {
                console.log('LMSGetValue called: ' + name);
                return '';
            },
            LMSSetValue: function(name, value) {
                console.log('LMSSetValue called: ' + name + ' = ' + value);
                return 'true';
            },
            LMSCommit: function(param) {
                console.log('LMSCommit called');
                return 'true';
            },
            LMSGetLastError: function() {
                return '0';
            },
            LMSGetErrorString: function(errorCode) {
                return 'No error';
            },
            LMSGetDiagnostic: function(errorCode) {
                return 'No diagnostic information';
            }
        };
    </script>
</body>
</html>
