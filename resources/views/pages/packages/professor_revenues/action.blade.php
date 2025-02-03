<div class="">
    <a href="#" id="package-{{ $id }}">Update</a>
</div>

<script>
    (function($){
        $("#package-{{ $id }}").click(function () {
            var id = {{ $id }};
            var test = $('#package-value-'+id).val();
            // console.log(test);
            if(test!='') {
                $.ajax({
                    url: '{{url('packages/professor/revenues/update',$id)}}',
                    data: {
                        package_id: id,
                        professor_revenue: test,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (result) {
                        // console.log(result);
                        // if (result) {
                        //     table.DataTable().draw();
                        // }
                        toastr.success("Updated");
                        $("#package-{{ $id }}").text('Updated');
                    }
                });
            }
            else
            {
                toastr.error(" Required field cannot be null");
            }


        });

    })(jQuery);
</script>

