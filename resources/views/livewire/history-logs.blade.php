<div class="antialiased flex justify-center items-center px-4 p-8">
    <div class="card bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-9xl p-3">
        <div class="card-header text-black rounded-t-lg">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">History Logs</h2>
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
                    <th class="px-6 py-3 text-center rounded-tl-lg">
                        Time
                    </th>
                    <th class="px-6 py-3 text-center">
                        Name
                    </th>
                    <th class="px-6 py-3 text-center">
                        Email
                    </th>
                    <th class="px-6 py-3 text-center">
                        Programs
                    </th>
                    <th class="px-6 py-3 text-center rounded-tr-lg">
                        Logs
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-200">
                    <td class="px-6 py-4 text-center">{{ $log->created_at }}</td>
                    <td class="px-6 py-4 text-center">{{ $log->user->name }}</td>
                    <td class="px-6 py-4 text-center">{{ $log->user->email }}</td>
                    <td class="px-6 py-4 text-center">{{ $log->program->name }}</td>
                    <td class="px-6 py-4 text-center">{{ $log->action }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No logs available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
