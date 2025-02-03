<div class="float-right">
    <a href="{{ route('custom-testimonials.edit', $id) }}" ><i class="fas fa-edit ml-3"></i></a>
{{--    <a href="" data-toggle="modal" data-target="#publish-{{ $id }}"><i class="fas fa-edit ml-3"></i></a>--}}
    <a href="#" id="delete-{{ $id }}" class="text-danger btn-delete"><i class="fas fa-trash ml-3"></i></a>
</div>

<script>
    $("#delete-{{ $id }}").click(function () {
        let confirmation = confirm("Delete this item?");
        let table = $('#datatable');

        if (confirmation) {
            $.ajax({
                url: "{{ route('custom-testimonials.destroy', $id) }}",
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
