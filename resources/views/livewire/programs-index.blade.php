<div class="antialiased flex justify-center px-4">
    <div class="card bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-9xl p-3">
        <div class="card-header text-black p-4 rounded-t-lg">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Faculty List</h2>
                <a href="{{ route('faculty-add') }}"
                   class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-900 px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i> Add Faculty
                </a>
            </div>
        </div>

        <!-- Display success message -->
        @if(session()->has('message'))
            <div class="alert alert-success bg-green-100 border-l-4 border-green-500 text-green-900 p-3 mb-4 rounded-lg">
                <span>{{ session('message') }}</span>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger bg-red-100 border-l-4 border-red-500 text-red-900 p-3 mb-4 rounded-lg">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Table -->
        <table class="table-auto w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-900 rounded-t-lg">
                    <th class="px-6 py-3 text-center rounded-tl-lg">Name</th>
                    <th class="px-6 py-3 text-center rounded-tr-lg">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faculties as $faculty)
                    <tr class="{{ $loop->odd ? 'bg-gray-50' : 'bg-white' }} border-b hover:bg-gray-200">
                        <td class="px-6 py-4 text-black text-center">{{ $faculty->name }}</td>
                        <td class="px-6 py-4 text-black flex justify-center items-center space-x-2">
                            <a href="{{ route('faculty-edit', $faculty->id) }}"
                               class="btn flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <button wire:click="delete({{ $faculty->id }})"
                                    class="btn flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-trash mr-2"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-center text-gray-500">No faculties available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $faculties->links() }} <!-- Laravel Pagination -->
        </div>
    </div>
</div>
