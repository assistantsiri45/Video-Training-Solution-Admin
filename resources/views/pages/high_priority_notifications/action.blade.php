<div class="float-right">
    <a href="{{ route('high-priority-notifications.edit', $id) }}" class=""><i class="fas fa-edit ml-3"></i></a>
    <a href="#" id="delete-{{ $id }}"><i class="fas fa-trash ml-3"></i></a>
</div>

<script>
    (function($){
        //DELETE
        $("#delete-{{ $id }}").click(function () {
            let confirmation = confirm("Delete this item?");
            let table = $('#datatable');

            if (confirmation) {
                $.ajax({
                    url: "{{ route('high-priority-notifications.destroy', $id) }}",
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        console.log(result);
                        if (result.status == 200) {
                                toastr.success(result.message);
                                }else{
                                toastr.error(result.message);
                                }
                            table.DataTable().draw();
                    }
                });
            }
        });
    })(jQuery);
</script>
