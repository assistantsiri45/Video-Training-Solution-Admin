<style>
    .badge{
        width: 55px;
    }
</style>
<div class="float-right">
<button type="button" id="{{$id}}" style="border:0;background: none;" class="edit"  data-toggle="modal" data-target="#myModal2"><a href="#" class=""><i class="fas fa-edit "></i></a></button>
    
<div class="modal fade" id="myModal2">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('deal_of_day.update') }}" method="POST" >
                    @csrf
                    <input type="hidden" id="pkgId" name="pkgId" value="{{$id}}"/>
                        
                    <div class="form-group">
                    <label for="special_price">Special Price</label>
                        <input type="number" class="form-control" id="special_price" name="special_price" >
                    </div>
                    <div class="form-group">
                    <label for="special_price">Special Price Active from</label>
                        <input type="date" class="form-control" id="special_price_active_from" name="special_price_active_from" >
                    </div>
                    <div class="form-group">
                    <label for="special_price">Special Price Expire At</label>
                        <input type="date" class="form-control" id="special_price_expire_at" name="special_price_expire_at" >
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                    
                </div>
                </form>
            </div>
        </div>
    </div>
<script>
     $('.edit').click(function(){
       
       var id = this.id;
       $('#pkgId').val(id);
       $.ajax({
            type:"GET",
            dataType:"json",
            url:'{{url("/getPackageData/")}}'+'/'+ id,
            success:function(response){
                $('#special_price').val(response.special_price);
                $('#special_price_active_from').val(response.special_price_active_from);
                $('#special_price_expire_at').val(response.special_price_expire_at);
                $("#modal-title").html(response.package_name); 
            }
        })

    });
    </script>