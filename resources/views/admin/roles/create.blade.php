<form action="{{ route('roles.store') }}" method="post" class="container">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" class="form-control" id="name" name="name">
    </div>
    <button type="submit" class="btn btn-green">Save</button>
</form>
