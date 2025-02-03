<div>
    <input type="hidden" id="query_id" value="{{$id}}">
    <input type="checkbox" class="ext" name="ext" id="{{$id}}" value="{{$id}}">
</div>


<!-- Modal -->
<div id="myModal3" class="modal fade" role="dialog" >
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
       
      </div>
      <div class="modal-body">
        <form action="{{ route('email.update_extension') }}" method="POST">
            @csrf
            <div class="row">
                <input type="hidden" name="s_id" id="s_id">
                <input type="hidden" name="u_id" id="u_id">
               
                <div class="form-group col-md-4 col-sm-12">
                    <label for="remark">Student Name :</label>
                </div>

                <div class="form-group col-md-8 col-sm-12">
                    <input type="text" class="form-control" name="name" id="student_name">
                </div>
            </div>

            <div class="row">
              
                <div class="form-group col-md-4 col-sm-12">
                    <label for="remark">Student ID :</label>
                </div>

                <div class="form-group col-md-8 col-sm-12">
                    <input type="text" class="form-control" name="student_id" id="student_id">
                </div>
            </div>

            <!-- <div class="row">
                
                <div class="form-group col-md-4 col-sm-12">
                    <label for="remark">Course :</label>
                </div>

                <div class="form-group col-md-8 col-sm-12">
                    <input type="text" class="form-control" name="course" id="course">
                </div>
            </div> -->

            <div class="row" >
               
                <div class="form-group col-md-4 col-sm-12">
                    <label for="remark">Courses:</label>
                </div>

                <div class="form-group col-md-8 col-sm-12">
                    <select name="course" id="course" class="form-control" style="width: 100% !important;">Courses</select>
                </div> 
            </div>

            <div class="row">
               
               <div class="form-group col-md-4 col-sm-12">
                   <label for="remark">Completed viewing hrs:</label>
               </div>

               <div class="form-group col-md-8 col-sm-12">
                   <input type="text" class="form-control" name="view_hr" id="view_hr">
               </div>
           </div>

           <div class="row">
               
               <div class="form-group col-md-4 col-sm-12">
                   <label for="remark">Valid uptil:</label>
               </div>

               <div class="form-group col-md-8 col-sm-12">
                   <input type="text" class="form-control" name="valid" id="valid">
               </div>
           </div>

           <div class="row">
               
               <div class="form-group col-md-4 col-sm-12">
                   <label for="remark">Extension :</label>
               </div>

               <div class="form-group col-md-8 col-sm-12">
                   <input type="date" class="form-control" name="extension" id="extension" required>
               </div>
           </div>


            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="modal-close" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>





<script>
    $('input[type="checkbox"]').on('change', function(e){
    if(e.target.checked){
      var x = this.id;
      $('#s_id').val(x);
      $.ajax({
            type:"GET",
            dataType:"json",
            url:'/getStudentData/'+x,
            success:function(response){
                $('#student_id').val(response.student_id);
                $('#student_name').val(response.student_name);
                // $('#view_hr').val(response.watched_hrs);
                // $('#valid').val(response.expire_at);
                $('#u_id').val(response.user_id);
                $("#course").html(response.order_items); 
               // $('#myModal3').modal();
            }
        })
    $('#myModal3').modal({
        backdrop: 'static',
        keyboard: false
    });
    

    $('#course').on('change', function(e){
    var package_id = $('#course').val();
    var user_id = $('#u_id').val();
    if (package_id) {
        $.ajax({
            url: '{{ url('/getPackvalidity') }}',
            data: {user_id :user_id,package_id:package_id},
            type: "POST",
            dataType: "json",
            success: function (response) {
                $('#view_hr').val(response.total_watched);
                $('#valid').val(response.validity);
                   // $('#level').empty();
                   // $('#level').append('<option disabled selected>  Choose Level </option>');
                   // $.each(data, function (key, value) {
                   // $('#level').append('<option value="' + value.id + '">' + value.name + '</option>');
               // });

            }
        });
    } 
});
   }
});

// $('#myModal3').modal({
//     backdrop: 'static',
//     keyboard: false
// })

$('#modal-close').click(function(){
    $('#email_support').modal('hide');
    location.reload();
});

$('#course').select2({
                placeholder: 'Course'
            });


</script>