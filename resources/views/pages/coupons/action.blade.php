<div class="float-right">
    <a href="{{ route('coupons.show', $id) }}"><i class="fas fa-eye ml-3"></i></a>
    <a href="{{ route('coupons.edit', $id) }}"><i class="fas fa-edit ml-3"></i></a>
    <a href="#" id="delete-{{ $id }}" class="text-danger btn-delete"><i class="fas fa-trash ml-3"></i></a>
</div>

<!-- Modal -->
<div class="modal fade" id="viewCoupon-{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="viewCoupon" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editCallRequestForm-{{ $id }}" class="modal-content" method="post" >
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" >Coupon Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><label>Coupon name :</label> {{$name}}</p>
                    @if($amount_type==1)
                    <p><label>Coupon amount :</label> FLAT <i class="fas fa-inr"></i>{{$amount}}/- OFF</p>
                    @else
                    <p><label>Coupon amount :</label> {{$amount}} % OFF</p>
                    @endif
                    <p><label>Total coupon per user :</label> {{$coupon_per_user}}</p>
                    <p><label>Total coupon limit :</label> {{$total_coupon_limit}}</p>
                    <p><label>Validity:</label> {{$valid_from}} - {{$valid_to}}</p>
                    @if($min_purchase_amount)
                    <p><label>Minimum purchase amount:</label> {{$min_purchase_amount}}/-</p>
                    @endif
                    @if($max_purchase_amount)
                    <p><label>Maximum purchase amount:</label> {{$max_purchase_amount}}/-</p>
                    @endif
                    @if($max_discount_amount)
                    <p><label>Maximum discount amount:</label> {{$max_discount_amount}}/-</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button  type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function($){


        $("#delete-{{ $id }}").click(function () {
            let confirmation = confirm("Delete this item?");
            let table = $('#datatable');

            if (confirmation) {
                $.ajax({
                    url: "{{ route('coupons.destroy', $id) }}",
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
