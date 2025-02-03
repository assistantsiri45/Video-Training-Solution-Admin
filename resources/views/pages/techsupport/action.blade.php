<input type="hidden" id="x_{{$query->id}}" value="{{$query->id}}">
    <!-- Trigger the modal with a button -->
@if($query->status != 1)
<button type="button" id="{{$query->id}}" style="border:0"; class="remark"  data-toggle="modal" data-target="#myModal"><a href="#" class=""><i class="fas fa-edit "></i></a></button>
@endif
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
       
      </div>
      <div class="modal-body">
        <form action="{{ route('techsupport.update-remark') }}" method="POST" id="remark-form">
            @csrf
            <div class="row">
                <input type="hidden" name="qid" id="q_id">
                <div class="form-group col-md-12 col-sm-12">
                    <label for="remark">Remark</label>
                    <textarea  class="form-control" onkeypress="return isAlfa(event)" name="remark" id="remark" ></textarea>
                    <p id="charNum"></p>
                </div>
            </div>
            <button type="submit" id="remark_submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="btn-close" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script>
  $('.remark').click(function(){ 
       var id = this.id;
       $('#q_id').val(id);
  });

  $('#btn-close').click(function(){
      $('#remark').val('');
  });

  function isAlfa(evt) {
    if (event.target.value.substr(-1) === ' ' && event.code === 'Space') {
      return false;
    }
  }

  $('#remark').keyup(function(){
      var word = $(this).val().trim();
      var counts = word.split(' ');
      var maxLength = counts.length;
      var remaining = 250 - maxLength;
      //remaining = Math.max(0, remaining);
      if(word == '' && maxLength == 1){
        document.getElementById("charNum").innerHTML = '<span style="color: red;font-size: 80%;float: right;">250 words remaining</span>';
      }
      else if(remaining > 0 ){
        document.getElementById("charNum").innerHTML = '<span style="color: red;font-size: 80%;float: right;">'+remaining+' words remaining</span>';
      }else{
        document.getElementById("charNum").innerHTML = '<span style="color: red;font-size: 80%;float: right;">'+maxLength+' words </span>';
    
      }
  });


  jQuery.validator.addMethod("validate_query", function(value, element) {
            var reg =/<(.|\n)*?>/g;
            if (reg.test(value)) {
                return false;
            } else {
                return true;
            }
        }, "HTML tags are not allowed");

  jQuery.validator.addMethod("validate_desc", function(value, element) {
            var text=value.trim();
            var words = text.split(' ');
            if (words.length > 250) {
                return false;
            }else if(value.trim()<1){
                return false;
            } else {
                return true;
            }
  }, "Maximum 250 words are allowed");

  $('#remark-form').validate({
    onsubmit:false,
    rules: {
      remark: {
          required: true,
          validate_query:true,
          validate_desc:true,
        },
      }       
  });

  $("#remark_submit").click(function (e) {
    e.preventDefault();
    let isValid = $('#remark-form').valid();
    if (isValid)
    { 
        $("#remark_submit").html("Saving <i class='fa fa-spinner fa-spin'>");
        $('#remark-form').submit();
    }
  });
</script>

