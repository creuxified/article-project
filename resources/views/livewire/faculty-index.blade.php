<div class="antialiased flex justify-center px-4">
    <div class="card bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-9xl p-3">
        <div class="card-header text-black p-4 rounded-t-lg">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Faculty List</h2>
                <a href="{{ route('faculty-add') }}"
                class="flex items-center justify-center bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
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

        <!-- Table -->
        <table class="table-auto w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white rounded-t-lg">
                    <th class="px-6 py-3 text-center rounded-tl-lg">
                     Name
                    </th>
                    <th class="px-6 py-3 text-center rounded-tr-lg">
                     Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($faculties as $faculty)
                    <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-200 ">
                        <td class="px-6 py-4 text-black">{{ $faculty->name }}</td>
                        <td class="px-6 py-4 text-black dark:text-gray-200 flex justify-center items-center space-x-2">
                            <a href="faculty/edit/{{ $faculty->id }}"
                               class="btn flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <button class="btn flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm"
                                    wire:click="delete({{ $faculty->id }})"
                                    wire:confirm="Are you sure you want to delete this?">
                                <i class="fas fa-trash mr-2"></i> Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
