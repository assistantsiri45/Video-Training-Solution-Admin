<div class="float-right">
    <a href="{{url('orders',$id)}}" id="view-{{ $id }}" class="text-primary"><i class="fas fa-eye ml-3"></i></a>
{{--    <a href="#" id="delete-{{ $id }}" class="text-danger btn-delete"><i class="fas fa-trash ml-3"></i></a>--}}
</div>
<script>
    (function($){
        $("#delete-{{ $id }}").click(function () {
            let confirmation = confirm("Delete this item?");
            let table = $('#datatable');

            if (confirmation) {
                $.ajax({
                    url: "{{ route('orders.destroy', $id) }}",
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
                            toastr.success("Order deleted successfully");
                            table.DataTable().draw();
                        }
                    }
                });
            }
        });
    })(jQuery);
</script>
