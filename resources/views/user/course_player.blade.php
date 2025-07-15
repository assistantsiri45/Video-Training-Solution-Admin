<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const COURSE_ID = {{ $course->id }}; // Laravel se course_id bhejna

    function getCurrentProgress() {
        // Example: progress bar ka percentage ya video player se duration
        return parseFloat(document.getElementById('progress_percent').value); // Example
    }

    setInterval(() => {
        let progress = getCurrentProgress();
        axios.post('/course/progress/update', {
            course_id: COURSE_ID,
            progress_percent: progress
        })
        .then(response => {
            console.log('Progress Saved', response.data);
        })
        .catch(error => {
            console.error('Error saving progress:', error);
        });
    }, 30000);
</script>
