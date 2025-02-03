<div class="float-right">
    <a href="#" id="delete-{{ $id }}" class="text-danger btn-delete"> @if($expire_at>\Carbon\Carbon::now())<i class="fas fa-trash ml-3"></i>@endif</a>
</div>

<script>
    (function($){
        $("#delete-{{ $id }}").click(function () {
            let confirmation = confirm("Delete this item?");
            let table = $('#datatable');
            console.log({{$id}})

            if (confirmation) {
                $.ajax({
                    url: "{{ route('j-money.destroy', $id) }}",
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
