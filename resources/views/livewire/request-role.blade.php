<div class="antialiased flex justify-center items-center px-4"> <!-- Add padding-left and padding-right -->
    <div class="card bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-9xl p-3"> <!-- Max width set to 7xl -->
        <div class="card-header text-black rounded-t-lg">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Request Logs</h2>
            </div>
        </div>

        <!-- Display success message -->
        @if (session()->has('message'))
        <div class="alert alert-success bg-green-100 border-l-4 border-green-500 text-green-900 p-3 mb-4 rounded-lg">
            <span>{{ session('message') }}</span>
        </div>
        @endif

        <!-- Table -->
        <table class="bg-white table-auto w-full border-collapse text-sm text-left rtl:text-right text-gray-500 dark:text-black mt-4 rounded-t-lg">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white rounded-t-lg">
                    <th class="px-6 py-3 text-center rounded-tl-lg">User</th>
                    <th class="px-6 py-3 text-center">Email</th>
                    <th class="px-6 py-3 text-center">Study Program</th>
                    <th class="px-6 py-3 text-center">Request</th>
                    <th class="px-6 py-3 text-center rounded-tr-lg">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-200">
                    <td class="px-6 py-4 text-center">{{ $log->user->name }}</td>
                    <td class="px-6 py-4 text-center">{{ $log->user->email }}</td>
                    <td class="px-6 py-4 text-center">{{ $log->program->name }}</td>
                    <td class="px-6 py-4 text-center">{{ $log->action }}</td>
                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                            <button @if ($log->is_reviewed == true) disabled @endif
                                class="block @if ($log->is_reviewed == true) cursor-not-allowed bg-dim-red opacity-50 @else
                                bg-blue-700 hover:bg-blue-800 @endif text-white focus:ring-4 focus:outline-none
                                focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                type="button" wire:click="openModal({{ $log->user->id }}, {{ $log->id }})">
                                Review
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No logs available</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($isModalOpen)
        @livewire('modal-role', ['user' => $log->user, 'log' => $log, 'isModalOpen' => $isModalOpen])
        @endif
    </div>
</div>
