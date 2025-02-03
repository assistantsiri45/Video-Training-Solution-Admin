<form action="{{ route('rule-book.destroy', ['rule_book' => $rulebook->id]) }}" method="POST">
    @method('DELETE')
    @csrf
    <button type="submit" class="btn btn-danger">Delete</button>
</form>
