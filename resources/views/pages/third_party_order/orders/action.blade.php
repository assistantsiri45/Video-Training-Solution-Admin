@if($query->orderitem->is_canceled == 0)
<div class="float-right" id="assign-package">
    <a href="{{route('third-party-orders.cancel', $query->id)}}" id="cancel-{{$query->id}}" class="btn btn-primary cancel" data-id="{{$query->id}}">Cancel</a>
</div>
@else
<div class="float-right">
    <p id="canceled-{{$query->id}}">Canceled</p>
</div>
@endif
