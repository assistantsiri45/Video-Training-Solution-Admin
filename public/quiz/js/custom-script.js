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
            var html = '<option value="">Select1</option>';

             $.each(response, function (key, val) {
                   var selected = '' == key ? "selected" : "";
                   html += '<option value="'+key+'" '+selected+'>'+val+'</option>';
              });
             $('#subject').html(html);
        }
    });
}