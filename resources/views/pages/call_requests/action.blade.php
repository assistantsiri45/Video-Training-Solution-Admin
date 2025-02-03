<div class="float-right">
    <a href="#" id="edit-{{ $id }}"  data-toggle="modal" data-target="#editModal-{{ $id }}" class=""><i class="fas fa-edit ml-3"></i></a>
    <a href="#" id="delete-{{ $id }}" class="text-danger btn-delete"><i class="fas fa-trash ml-3"></i></a>
</div>

<!-- Modal -->
<div class="modal fade" id="editModal-{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="modal-content" method="post" action="{{route('call-requests.update',$id)}}" >
            @csrf
                @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title" >Content</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <textarea name="description" id="description-{{ $id }}" class="form-control"  required value="{{ $content }}" rows="3">{{ $content  }}</textarea>
            </div>
            <div class="modal-footer">
                <button id="updateModal-{{ $id }}"  type="submit" class="btn btn-primary">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function($){


        $("#delete-{{ $id }}").click(function () {
            let confirmation = confirm("Delete this item?");
            let table = $('#datatable');

            if (confirmation) {
                $.ajax({
                    url: "{{ route('call-requests.destroy', $id) }}",
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        if (result) {
                            toastr.options = {
                                "preventDuplicates": true,
                                "preventOpenDuplicates": true
                            };
                            toastr.success("Call request deleted successfully !");
                            table.DataTable().draw();
                        }
                    }
                });
            }
        });
    })(jQuery);
</script>
