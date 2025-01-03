<div class="antialiased flex justify-center px-4">
    <div class="card bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-9xl p-3">
        <div class="card-header text-black p-4 rounded-t-lg">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Study Program List</h2>
                <a href="{{ route('programs-add') }}"
                   class="flex items-center justify-center bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i> Add Study Program
                </a>
            </div>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success bg-green-100 border-l-4 border-green-500 text-green-900 p-3 mb-4 rounded-lg">
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <table class="table-auto w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white rounded-t-lg">
                    <th class="px-6 py-3 text-center rounded-tl-lg">Name</th>
                    <th class="px-6 py-3 text-center">Faculty</th>
                    <th class="px-6 py-3 text-center rounded-tr-lg">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($programs as $program)
                    <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-200">
                        <td class="px-6 py-4 text-black">{{ $program->name }}</td>
                        <td class="px-6 py-4 text-black">{{ $program->faculty->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-black dark:text-gray-200 flex justify-center items-center space-x-2">
                            <a href="programs/edit/{{ $program->id }}"
                               class="btn flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <button class="btn flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm"
                                    wire:click="delete({{ $program->id }})"
                                    wire:confirm="Are you sure you want to delete this?">
                                <i class="fas fa-trash mr-2"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No study programs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination and Result Info -->
        <div class="flex flex-col items-center mt-4 space-y-4">
            <!-- Pagination Links -->
            <div class="flex justify-center">
                {{ $programs->links() }}
            </div>
        </div>

    </div>
</div>
