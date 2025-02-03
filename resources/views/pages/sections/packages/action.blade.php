<div class="float-right">
    <a href="{{url('packages/videos')}}?package_id={{ $package_id }}"><i class="fas fa-video"></i></a>
    <a href="#" id="delete-{{ $id }}"><i class="fas fa-trash ml-3"></i></a>
</div>


<script>
    (function($){
        $("#delete-{{ $id }}").click(function () {
            let confirmation = confirm("Delete this item?");
            let table = $('#section-packages');

            if (confirmation) {
                $.ajax({
                    url: "{{ route('section-packages.destroy', $id) }}",
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        if (result) {
                            table.DataTable().draw();
                            $('#tbl-packages').DataTable().draw();
                            location.reload();
                        }
                    }
                });
            }
        });
    })(jQuery);
</script>
