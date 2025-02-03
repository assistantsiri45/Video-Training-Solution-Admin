<div class="float-left">
    @if( $is_refunded==1)
            <p>Refunded</p>
    @else
            <a alt="Refund" href="#" id="refund-amount-{{$id}}" data-toggle="modal" data-target="#exampleModal-{{$id}}">Refund</a>
    @endif

</div>
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="exampleModal-{{$id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h5>Do you want to refund the amount?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="refund-{{ $id }}">Yes</button>
            </div>
        </div>
    </div>
</div>
<script>
    (function($){
        $("#refund-{{ $id }}").click(function () {
            $("#refund-{{ $id }}").attr('disabled','disabled');
            let table = $('#datatable');
            var id = {{ $id }};
            var email = $('#email-'+id).val();
            var name = $('#name-'+id).val();
            // console.log(test);
                $.ajax({
                    url: "{{ route('refunds.update', $id) }}",
                    type:'PUT',
                    data: {
                        id:{{$id}},
                        email:email,
                        name:name,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        console.log(result)
                        if (result) {
                            $("#exampleModal-{{$id}}").modal('toggle');
                            toastr.success('Amount refunded');
                            table.DataTable().draw();
                        }
                    }
                });
        });

    })(jQuery);
</script>
