<!-- Add DataTables CSS and JS -->

<head>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h2>List Users</h2>
                </div>
                <div class="col">
                    <a href="{{ route('users.add') }}" class="btn btn-primary btn-sm float-end">Add Users</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Table -->
            <table class="table table-bordered" id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->status == 1 ? 'Active' : 'Inactive' }}</td>
                            <td>
                                <!-- Tombol Edit -->
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <!-- Tombol Delete -->
                                <button class="btn btn-danger btn-sm"
                                    wire:click="delete({{ $user->id }})">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            // Optional customization, like pagination, sorting, etc.
            "paging": true,
            "searching": true,
            "ordering": true,
            "lengthChange": true,
            "pageLength": 10, // Set default number of rows per page
        });
    });
</script>
