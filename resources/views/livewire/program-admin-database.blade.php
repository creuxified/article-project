<table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-4">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="px-6 py-3 text-center">Role</th>
            <th scope="col" class="px-6 py-3 text-center">User</th>
            <th scope="col" class="px-6 py-3 text-center">Email</th>
            <th scope="col" class="px-6 py-3 text-center">Faculty</th>
            <th scope="col" class="px-6 py-3 text-center">Study Program</th>
            <th scope="col" class="px-6 py-3 text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
            <td class="px-6 py-4 text-center">{{ $user->role->name }}</td>
            <td class="px-6 py-4 text-center">{{ $user->name }}</td>
            <td class="px-6 py-4 text-center">{{ $user->email }}</td>
            <td class="px-6 py-4 text-center">{{ $user->faculty->name }}</td>
            <td class="px-6 py-4 text-center">{{ $user->program->name }}</td>

            <td class="px-6 py-4 text-center">
                <div class="inline-flex rounded-md shadow-sm" role="group">
                    <button class="block text-white focus:ring-4 focus:outline-no focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button" wire:click="openModal">Edit</button>
                    <button class="block text-white focus:ring-4 focus:outline-no focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button" wire:click="">Delete</button>
                    {{-- <button @if ($log->is_reviewed == true) disabled @endif
                        class="block @if ($log->is_reviewed == true) cursor-not-allowed bg-dim-red opacity-50 @else
                        bg-blue-700 hover:bg-blue-800 @endif text-white focus:ring-4 focus:outline-none
                        focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                        type="button"
                        wire:click="openModal({{ $log->user->id }}, {{ $log->id }})">
                        Review
                    </button> --}}
                </div>
            </td>
        </tr>
        @empty
        @endforelse
    </tbody>
</table>