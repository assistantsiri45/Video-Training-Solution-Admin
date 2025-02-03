<div class="float-right">
    
<input type="hidden" id="x_{{$id}}" value="{{$id}}">
    <!-- Trigger the modal with a button -->
<button type="button" id="{{$id}}" style="border:0"; class="rej"  data-toggle="modal" data-target="#myModal2"><a href="#" class=""><i class="fas fa-times "></i></a></button>
<button type="button" id="{{$id}}" style="border:0"; class="aprv" data-toggle="modal" data-target="#myModal"><a href="#" class=""><i class="fas fa-check "></i></a></button>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
       
      </div>
      <div class="modal-body">
        <form action="{{ route('email.update-status') }}" method="POST">
            @csrf
            <div class="row">
                <input type="hidden" name="qid" id="q_id">
                <input type="hidden" value="1" name="approve_status">
                <div class="form-group col-md-12 col-sm-12">
                    <label for="remark">Remark</label>
                    <textarea  class="form-control" name="remark" id="remark"   required></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
       
      </div>
      <div class="modal-body">
        <form action="{{ route('email.update-status') }}" method="POST">
            @csrf
            <div class="row">
                <input type="hidden" name="qid" id="q_id2">
                <input type="hidden" value="2" name="approve_status">
                <div class="form-group col-md-12 col-sm-12">
                    <label for="remark">Remark</label>
                    <textarea  class="form-control" name="remark" id="remark"   required></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
</div>


<script>
    $('.aprv').click(function(){
       
       var id = this.id;
       $('#q_id').val(id);
    });

    $('.rej').click(function(){
       
       var id = this.id;
       $('#q_id2').val(id);
    });
   
</script>
