<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h2>Request Logs</h2>
    <div class="overlay {{ $isModalOpen ? 'active' : '' }}"></div>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-4">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3 text-center">User</th>
                <th scope="col" class="px-6 py-3 text-center">Email</th>
                <th scope="col" class="px-6 py-3 text-center">Study Program</th>
                <th scope="col" class="px-6 py-3 text-center">Request</th>
                <th scope="col" class="px-6 py-3 text-center">Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($logs as $log)
        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $log->user->name }}
            </th>
            <td class="px-6 py-4 text-center">{{ $log->user->email }}</td>
            <td class="px-6 py-4 text-center">{{ $log->program->name }}</td>
            <td class="px-6 py-4 text-center">{{ $log->action }}</td>
            <td class="px-6 py-4 text-center">
                <div class="inline-flex rounded-md shadow-sm" role="group">
<button @if ($log->is_reviewed == true) disabled @endif 
    class="block @if ($log->is_reviewed == true) cursor-not-allowed bg-dim-red opacity-50 @else bg-blue-700 hover:bg-blue-800 @endif text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" 
    type="button" 
    wire:click="openModal({{ $log->user->id }}, {{ $log->id }})">
                        Review
                    </button>
                </div>
            </td>
        </tr>
        @empty
        @endforelse  
        </tbody>
    </table>
    @if($isModalOpen)
        @livewire('modal-role', ['user' => $log->user,'log' => $log, 'isModalOpen' => $isModalOpen])
    @endif
</div>
