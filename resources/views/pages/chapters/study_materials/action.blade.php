<div class="float-right">
    <a href="" class="" data-toggle="modal" data-target="#editModal-{{ $id }}"><i class="fas fa-edit ml-3"></i></a>
    <a href="#" id="delete-{{ $id }}"><i class="fas fa-trash ml-3"></i></a>
</div>

<!-- Modal -->
<div class="modal fade" id="editModal-{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="modal-content" method="post" action="{{url('update-study-material')}}"  enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="modal-header">
                    <h5 class="modal-title" >Update Study Material</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="{{$id}}">
                    <input type="file" name="study_material" accept="application/pdf"  class="form-control" id="study_material-{{$id}}" @error('study_material') is-invalid @enderror value="{{ old('study_material') }}">
                    @error('study_material')
                    <span class="invalid-feedback" role="alert" style="display: inline;">{{ $errors->first('study_material') }}</span>
                    @enderror
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
                    url: "{{ url('delete-study_materials') }}",
                    type: "POST",
                    data: {
                        id: {{$id}},
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        if (result) {
                            table.DataTable().draw();
                        }
                    }
                });
            }
        });
    })(jQuery);
</script>
