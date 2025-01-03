<div class="antialiased flex justify-center px-4">
    <div class="card bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-9xl p-3">
        <div class="card-header text-black p-4 rounded-t-lg">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">List Users</h2>
                <a href="{{ route('users-add') }}"
                   class="flex items-center justify-center bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                   <i class="fas fa-plus mr-2"></i> Add Users
                </a>
            </div>
        </div>
        <!-- Display success message -->
        @if(session()->has('message'))
            <div class="alert alert-success bg-green-100 border-l-4 border-green-500 text-green-900 p-3 mb-4 rounded-lg">
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <!-- Table -->
        <table class="table-auto w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white rounded-t-lg">
                    <th class="px-6 py-3 text-center rounded-tl-lg">ID</th>
                    <th class="px-6 py-3 text-center">Role</th>
                    <th class="px-6 py-3 text-center">Username</th>
                    <th class="px-6 py-3 text-center">Email</th>
                    <th class="px-6 py-3 text-center">Name</th>
                    <th class="px-6 py-3 text-center rounded-tr-lg">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-200">
                        <td class="px-6 py-4 text-center text-black">{{ $user->id }}</td>
                        <td class="px-6 py-4 text-center text-black">{{ $user->role->name }}</td>
                        <td class="px-6 py-4 text-center text-black">{{ $user->username }}</td>
                        <td class="px-6 py-4 text-center text-black">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-center text-black">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-center text-black flex justify-center items-center space-x-2">
                            <a href="{{ route('user.edit', $user) }}"
                               class="btn flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <button class="btn flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm"
                                    wire:click="delete({{ $user->id }})"
                                    wire:confirm="Are you sure you want to delete this?">
                                <i class="fas fa-trash mr-2"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-4">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
