<div class="float-right">
    <a href="{{ route('free-resource.edit', $id) }}"><i class="fas fa-edit"></i></a>
    <a href="#" id="delete-{{ $id }}"><i class="fas fa-trash ml-3"></i></a>
</div>


<script>
    (function($){
        $("#delete-{{ $id }}").click(function () {
            let confirmation = confirm("Delete this item?");
            let table = $('#datatable');

            if (confirmation) {
                $.ajax({
                    url: "{{ route('free-resource.destroy', $id) }}",
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
    })(jQuery);
</script>
