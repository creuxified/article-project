<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h2>Faculty List</h2>
                </div>
                <div class="col">
                    <a href="{{ route('faculty-add') }}" class="btn btn-primary btn-sm float-end">Add Faculty</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Display success message -->
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($faculties as $faculty)
                        <tr>
                            <td>{{ $faculty->name }}</td>
                            <td>
                                <a href="faculty/edit/{{ $faculty->id }}" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                            <td><button class="btn btn-danger btn-sm" wire:click="delete({{$faculty->id}})" wire:confirm="Are you sure you want to delete this?">Delete</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
