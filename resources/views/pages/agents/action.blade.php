<div class="float-right">
    <a href="{{ route('agents.edit', $id) }}" class=""><i class="fas fa-edit ml-3"></i></a>
    <a href="#" id="delete-{{ $id }}"><i class="fas fa-trash ml-3"></i></a>
</div>


<script>
    (function($){
        $("#delete-{{ $id }}").click(function () {
            let confirmation = confirm("Delete this item?");
            let table = $('#datatable');

            if (confirmation) {
                $.ajax({
                    url: "{{ route('agents.destroy', $id) }}",
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
