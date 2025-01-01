<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h2>Request Logs</h2>
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
