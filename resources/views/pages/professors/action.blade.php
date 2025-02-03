<div class="float-right">
    <a href="{{ route('professors.edit', $id) }}"><i class="fas fa-edit ml-3"></i></a>
    @if( \Auth::user()->role!=9)
    @if (!$publish_status)
        @if($video_type==1)
            <a href="#" data-toggle="modal" data-target="#publishModal-{{ $id }}"  ><i class="fas fa-check-circle ml-3"></i></a>
       @endif
    @endif
   
    <a href="#" id="delete-{{ $id }}"><i class="fas fa-trash ml-3"></i></a>
    @endif
</div>


<div class="modal fade" id="publishModal-{{ $id }}">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Publish</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Type <strong>publish</strong> to continue</p>
                <div class="form-group">
                    <input type="hidden" id="confirmation-url">
                    <input type="text" class="form-control" id="publish-confirmation-{{$id}}" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn-publish-{{ $id }}">Publish</button>
            </div>
        </div>
    </div>
</div>


<script>
    (function($){

        //DELETE
        $("#btn-publish-{{ $id }}").click(function () {
            let table = $('#datatable');
            if ( $('#publish-confirmation-'+{{$id}}).val() === 'publish') {
                $.ajax({
                    url: "{{ url('publish-professor-video') }}",
                    type: "POST",
                    data: {
                        id: {{$id}},
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (result) {

                        if (result.status == 200) {
                            toastr.success(result.message);
                        }else{
                            toastr.error(result.message);
                        }
                        table.DataTable().draw();
                    }
                });
            }
            $('#publish-confirmation-'+{{$id}}).val("");
        });

        //DELETE
        $("#delete-{{ $id }}").click(function () {
            let confirmation = confirm("Delete this item?");
            let table = $('#datatable');

            if (confirmation) {
                $.ajax({
                    url: "{{ route('professors.destroy', $id) }}",
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        if (result) {

                            table.DataTable().draw();
                        }
                        $('#publishModal-{{ $id }}').modal('hide');
                    }
                });
            }
        });
    })(jQuery);
</script>
