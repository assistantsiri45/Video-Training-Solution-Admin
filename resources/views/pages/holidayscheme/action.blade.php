<div class="float-right">
        @if($is_published==true)
            <!-- <a href="#" id="delete-{{ $id }}"><i class="fas fa-trash ml-3"></i></a> -->
            <a href="#" class="badge badge-danger" style="float: left;margin-top: 4px;" id="enable-{{ $id }}" >Unpublish </a>
            @else
            <a href="#" class="badge badge-success" style="float: left;margin-top: 4px;" id="enable-{{ $id }}">Publish </a>
        @endif
    <a href="{{ route('holiday-scheme.edit', $id) }}" class=""   @if($is_published==true) onclick="return confirm('This scheme is already published.Are you sure you want to continue?')" @endif><i class="fas fa-edit ml-1"></i></a>
    <a href="#" id="delete-{{ $id }}"><i class="fas fa-trash ml-1"></i></a>
</div>


<script>
    (function($){
        $("#delete-{{ $id }}").click(function () {
            let confirmation = confirm("Delete this item?");
            let table = $('#datatable');

            if (confirmation) {
                $.ajax({
                    url: "{{ route('holiday-scheme.destroy', $id) }}",
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


        $("#enable-{{ $id }}").click(function () {
            
            let confirmation = confirm("Are you sure to publish/unpublish this?");
            let table = $('#datatable');

            if (confirmation) {
                $.ajax({
                    url: "{{ route('holiday-scheme.publishOffer') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: "{{$id}}"
                    },
                    success: function(result) {
                        if (result) {
                            
                            toastr.success('Offer statuts updated');
                                           
                             location.reload();
                            table.DataTable().draw();
                        }
                    }
                });
            }
        });
    })(jQuery);
</script>
