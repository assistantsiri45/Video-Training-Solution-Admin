<div class="float-right">
    <a href="{{ route('spin-wheel-campaigns.show', $id) }}"><i class="fas fa-eye ml-1"></i></a>
    <a href="{{ route('spin-wheel-campaigns.edit', $id) }}" class=""><i class="fas fa-edit ml-1"></i></a>
    <a href="#" id="delete-{{ $id }}"><i class="fas fa-trash ml-1"></i></a>
    <a alt="Copy url" href="#" id="copy-{{ $id }}" title="Copy Url"><i class="fas fa-copy ml-1"></i></a>
</div>


<script>
    (function($){
        $("#delete-{{ $id }}").click(function () {
            let confirmation = confirm("Delete this item?");
            let table = $('#datatable');

            if (confirmation) {
                $.ajax({
                    url: "{{ route('spin-wheel-campaigns.destroy', $id) }}",
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
        $("#copy-{{ $id }}").click(function () {
            var env='{{ env('WEB_URL') }}';
            var url=env+'/campaigns/spin-wheels/'+'{{$slug}}';
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            document.execCommand("copy");
            $temp.remove();
            toastr.info("copied");
        });

    })(jQuery);
</script>

