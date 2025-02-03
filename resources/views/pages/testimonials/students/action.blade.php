<div class="float-right">
<a href="" data-toggle="modal" data-target="#publish-{{ $id }}"><i class="fas fa-check ml-3"></i></a>
    <!-- <a href="#" id="delete-{{ $id }}" class="text-danger btn-delete"><i class="fas fa-trash ml-3"></i></a> -->
    <a href="{{ route('student-testimonials.edit', $id) }}"  ><i class="fas fa-edit ml-3"></i></a>
</div>
<!-- Modal -->
<div class="modal fade" id="publish-{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="publish" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form  class="modal-content" method="POST" action="{{route('student-testimonials.update',$id)}}">
                @csrf
                @method('PUT')
               <div>
                   <div class="modal-header">
                       <h5 class="modal-title" >Update Testimonial Status</h5>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                       </button>
                   </div>
                   <div class="modal-body">
                      <div class="row">
                          <div class="col-md-8">
                              <div class="form-group">
                                  <label>Current Status</label>
                                  <p>@if($publish==\App\Models\Testimonial::UNPUBLISHED) <span class="badge badge-info">Unpublished</span>
                                      @else <span class="badge badge-success">Published</span> @endif</p>
                              </div>
                          </div>
                          <div class="col-md-4">
                              <div class="form-group">
                                  <label for="status">&nbsp;</label>
                                  <div class="form-group">
                                  @if($publish==\App\Models\Testimonial::UNPUBLISHED)
                                      <input class="" type="checkbox" id="is_published" name="is_published" required>
                                      <label for="status" class="">Publish</label>
                                 @else
                                        <input class="" type="checkbox" id="is_published" name="is_published" checked >
                                        <label for="status" class="">Publish</label>
                                 @endif
                                  </div>
                              </div>
                          </div>
                      </div>
                   </div>
               </div>
                <div class="modal-footer">
                    <button id type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button  type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $("#delete-{{ $id }}").click(function () {
        let confirmation = confirm("Delete this item?");
        let table = $('#datatable');

        if (confirmation) {
            $.ajax({
                url: "{{ route('student-testimonials.destroy', $id) }}",
                type: "DELETE",
                data: {
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
</script>
