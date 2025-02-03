<style>
    .badge{
        width: 55px;
    }
</style>
<div class="float-right">
    <a href="{{ route('modules.edit', $id) }}" class=""><i class="fas fa-edit ml-3"></i></a>
    @if($has_package==true)
        @if($is_enabled==true)
            <span class="badge badge-secondary">Enabled</span>
            @else
            <span class="badge badge-secondary">Disabled</span>
        @endif
    @else
        @if($is_enabled==true)
            <!-- <a href="#" id="delete-{{ $id }}"><i class="fas fa-trash ml-3"></i></a> -->
            <a href="#" class="badge badge-danger" id="delete-{{ $id }}" >Disable </a>
            @else
            <a href="#" class="badge badge-success" id="delete-{{ $id }}">Enable </a>
        @endif
    @endif
</div>


<script>
    (function($){
        $("#delete-{{ $id }}").click(function () {
            let confirmation = confirm("Are you sure to enable/disable this?");
            let table = $('#datatable');

            if (confirmation) {
                $.ajax({
                    url: "{{ route('modules.destroy', $id) }}",
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        if (result) {
                            toastr.success('Module status updated');
                                           
                             location.reload();
                            table.DataTable().draw();
                        }
                    }
                });
            }
        });
    })(jQuery);
</script>
