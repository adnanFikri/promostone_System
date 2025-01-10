<form action="{{ route('roles.update', $role) }}" method="post" class="container">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" class="form-control" value="{{ $role->name }}" id="name" name="name">
    </div>
    <button type="submit" class="btn btn-green">Save</button>
</form>
