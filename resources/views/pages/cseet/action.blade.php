<a onclick="return confirm('Are you sure you want to approve this order ?');" href="{{ route('cseet.status_accepted', $query->id) }}" class=" button-status-accepted" >
    <i class="fas fa-check-circle ml-3 text-success"></i>
</a>
<a onclick="return confirm('Are you sure you want to reject this order ?');" href="{{ route('cseet.status_rejected', $query->id) }}" class=" button-status-rejected" >
    <i class="fas fa-eraser ml-3 text-danger"></i>
</a>