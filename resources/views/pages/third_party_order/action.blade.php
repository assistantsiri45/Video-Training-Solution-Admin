<div class="float-right">
    <a href="#" id="assign-{{ $id }}" class="btn btn-primary">Assign</a>
</div>
<div class="modal fade" id="exampleModal-{{$id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body p-5 align-items-center" >
                <div class="row text-center">
                    <div class="col-md-12 mb-5">
                        <a href="#" class="btn btn-secondary study-material assign-package-{{$id}} d-none" data-is-study-material="1">Assign Package with Study Material</a>
                    </div>
                    <div class="col-md-12 ">
                        <a href="#" class="btn btn-primary assign-package-{{$id}}">Assign Package Only</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    (function($){

        var id =  $("#student-id-{{ $id }}").val();


        $("#assign-{{ $id }}").click(function () {
            var study_material_price = {{$study_material_price}}

            if(study_material_price>0)
            {
                $(".study-material").removeClass('d-none');
                $('#exampleModal-{{$id}}').modal('toggle');
            }
            else {
                $(".study-material").addClass('d-none');
                $('#exampleModal-{{$id}}').modal('toggle');
            }
        });



        $(".assign-package-{{$id}}").click(function () {
            var package_id = {{$id}};
                $.ajax({
                    method: 'POST',
                    url: '{{route('third-party-orders.store')}}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        student_id: id,
                        package_id: package_id,
                        is_study_material: $(this).attr('data-is-study-material')

                    },
                    success: function (response) {
                        if (response) {
                            toastr.success('package assigned successfully')
                            $('#exampleModal-{{$id}}').modal('toggle');
                        }
                    },
                });
        });


    })(jQuery);
</script>
