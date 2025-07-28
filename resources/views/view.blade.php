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
    let progressData = {};

    //  Session start time
    const sessionStart = Date.now();

    //  Dummy SCORM API
    window.API = {
        LMSInitialize(param) {
            console.log(' LMSInitialize called');
            return 'true';
        },
        LMSFinish(param) {
            console.log(' LMSFinish called');
            return 'true';
        },
        LMSGetValue(name) {
            console.log(' LMSGetValue:', name);
            return progressData[name] || '';
        },
        LMSSetValue(name, value) {
            console.log(' LMSSetValue:', name, '=', value);
            progressData[name] = value;

            //  Auto mark completed if session_time >= 600s
            if (name === 'cmi.core.lesson_status') {
                progressData['lesson_status'] = value;
            }

            return 'true';
        },
        LMSCommit(param) {
            console.log('LMSCommit called');
            return 'true';
        },
        LMSGetLastError: () => '0',
        LMSGetErrorString: () => 'No error',
        LMSGetDiagnostic: () => 'No diagnostic'
    };
    window.API_1484_11 = window.API;
    console.log(" SCORM API injected");

    // Calculate session time
    function getSessionTimeInSeconds() {
        return Math.floor((Date.now() - sessionStart) / 1000);
    }

    //  Auto save every 5 seconds
    setInterval(() => {
        const location = progressData['cmi.core.lesson_location'] || '';
        let status = progressData['lesson_status'] || 'incomplete';
        const sessionTime = getSessionTimeInSeconds();

        // If user stayed >= 600 seconds, auto complete
        if (sessionTime >= 600 && status !== 'completed') {
            status = 'completed';
            progressData['lesson_status'] = 'completed';
            console.log(' Auto-marked as completed (10 min+ session)');
        }

        fetch('/course/progress/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                course_id: "{{ $courseId }}",
                cmi_core_lesson_location: location,
                cmi_core_lesson_status: status,
                session_time: sessionTime
            })
        }).then(res => {
            if (res.ok) {
                console.log(" Saved: time =", sessionTime, "status =", status);
            } else {
                console.error("❌ Save failed");
            }
        }).catch(err => {
            console.error(" Error saving", err);
        });
    }, 5000);
</script>

</body>
</html>
