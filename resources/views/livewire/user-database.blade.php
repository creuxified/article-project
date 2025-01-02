<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h2>List Users</h2>
                </div>
                <div class="col">
                    <a href="{{ route('users-add') }}" class="btn btn-primary btn-sm float-end">Add Users</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Table -->
            <table class="table table-bordered" id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Role</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->role->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->name }}</td>
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

{{-- <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    @if (session()->has('message'))
    <div class="flex p-4 mb-4 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
        role="alert">
        <svg aria-hidden="true" class="w-5 h-5 inline mr-3" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V7h2v2z"></path>
        </svg>
        <div class="flex-1">{{ session('message') }}</div>
        <button type="button"
            class="ml-2 inline-flex items-center justify-center w-4 h-4 text-green-500 hover:bg-green-200 rounded-full focus:outline-none"
            wire:click="$set('message', null)" aria-label="Close">
            <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    @endif

    <div class="overlay {{ $isModalOpen ? 'active' : '' }}"></div>

    @if($userRole >= 3)
        <h2>Lecturer Datas</h2>
        @livewire('lecturer-database')
    @endif

    @if($userRole >= 4)
        <h2>Program Admin Datas</h2>
        @livewire('program-admin-database')
    @endif

    @if($userRole >= 5)
        <h2>Faculty Admin Datas</h2>
        @livewire('faculty-admin-database')
    @endif

    @if($isModalOpen)
        @livewire('modal-role', ['user' => $log->user,'log' => $log, 'isModalOpen' => $isModalOpen])
    @endif

</div> --}}
