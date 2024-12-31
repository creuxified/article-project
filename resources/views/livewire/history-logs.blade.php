<div>
    <h2>History logs</h2>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 mt-4">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3 text-center">Time</th>
                <th scope="col" class="px-6 py-3 text-center">Name</th>
                <th scope="col" class="px-6 py-3 text-center">email</th>
                <th scope="col" class="px-6 py-3 text-center">programs</th>
                <th scope="col" class="px-6 py-3 text-center">Logs</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($logs as $log)
        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $log->created_at }}
            </th>
            <td class="px-6 py-4 text-center">{{ $log->user->name }}</td>
            <td class="px-6 py-4 text-center">{{ $log->user->email }}</td>
            <td class="px-6 py-4 text-center">{{ $log->program->name }}</td>
            <td class="px-6 py-4 text-center">{{ $log->action }}</td>

        </tr>
        @empty
        @endforelse  
        </tbody>
    </table>
</div>
