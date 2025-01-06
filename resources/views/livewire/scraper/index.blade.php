<div class="antialiased flex justify-center px-4">
    <div class="card bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-9xl p-3">
        <div class="card-header text-black p-4 rounded-t-lg">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-semibold">Table Publications</h1>
            </div>
        </div>

        <!-- User Info and Scrape Form Combined -->
        <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg mb-4">
            <form wire:submit.prevent="scrapeAndShow">
                <!-- Input Fields -->
                <div class="flex flex-col space-y-4 mb-4">
                    <div>
                        <label for="scholar_id" class="block text-gray-900 dark:text-white font-bold mb-2">Scholar Author
                            ID</label>
                        <input type="text" name="scholar_id" id="scholar_id"
                            class="input w-full border-gray-300 rounded-lg py-3 pl-3 pr-5" wire:model="scholar_id"
                            placeholder="Enter Google Scholar Author ID" required>
                    </div>
                    <div>
                        <label for="scopus_id" class="block text-gray-900 dark:text-white font-bold mb-2">Scopus Author
                            ID</label>
                        <input type="text" name="scopus_id" id="scopus_id"
                            class="input w-full border-gray-300 rounded-lg py-3 pl-3 pr-5" wire:model="scopus_id"
                            placeholder="Enter Scopus Author ID" required>
                    </div>
                </div>

                <!-- Success or Error Notification -->
                @if (session('success'))
                    <div
                        class="alert alert-success bg-green-100 border-l-4 border-green-500 text-green-900 p-3 mb-4 rounded-lg">
                        <span>{{ session('success') }}</span>
                    </div>
                @elseif (session('error'))
                    <div
                        class="alert alert-error bg-red-100 border-l-4 border-red-500 text-red-900 p-3 mb-4 rounded-lg">
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Buttons Section -->
                <div class="flex justify-between items-center mb-4">
                    <!-- Delete Button -->


                    <!-- Scrape Button -->
                    <button type="submit"
                        class="btn flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg text-sm">
                        <i class="fas fa-search mr-2"></i> Scrape
                    </button>
                </div>
            </form>
            <form action="{{ route('scraper.deleteData') }}" method="POST" class="mr-2"
                        onsubmit="return confirm('Are you sure you want to delete all your data?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="btn flex items-center justify-center bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg text-sm">
                            <i class="fas fa-trash mr-2"></i> Delete All Data
                        </button>
                    </form>

            <!-- Flash message after submission -->
            @if (session()->has('message'))
                <div class="mt-4 p-4 bg-green-200 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif

        </div>
        <!-- Publications Table -->
        <div class="overflow-x-auto">
            <table id="publicationTable" class="table-auto w-full border-collapse border border-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        @if (auth()->user()->role_id == 3)
                            <th class="px-6 py-3">Lecturer</th>
                        @endif
                        <th class="px-6 py-3">Title</th>
                        <th class="px-6 py-3">Journal</th>
                        <th class="px-6 py-3">Publication Date</th>
                        <th class="px-6 py-3">Citations</th>
                        <th class="px-6 py-3">Source</th>
                        <th class="px-6 py-3">Link</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($publications as $index => $publication)
                        <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-200"
                            data-source="{{ $publication->source }}"
                            data-lecturer="{{ $publication->user->name ?? 'N/A' }}"
                            data-year="{{ \Carbon\Carbon::parse($publication->publication_date)->year }}">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            @if (auth()->user()->role_id == 3)
                                <td class="px-6 py-4">{{ $publication->user->name ?? 'N/A' }}</td>
                            @endif
                            <td class="px-6 py-4">{{ $publication->title }}</td>
                            <td class="px-6 py-4">{{ $publication->journal_name }}</td>
                            <td class="px-6 py-4">{{ $publication->publication_date }}</td>
                            <td class="px-6 py-4">{{ $publication->citations }}</td>
                            <td class="px-6 py-4">{{ $publication->source }}</td>
                            <td class="px-6 py-4">
                                @if ($publication->link)
                                    <a href="{{ $publication->link }}" target="_blank" class="text-blue-500">View</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#publicationTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });

            // Update nomor urut after sorting or searching
            table.on('order.dt search.dt', function() {
                let i = 1;
                table.cells(null, 0, {
                        search: 'applied',
                        order: 'applied'
                    })
                    .every(function(cell) {
                        this.data(i++); // Update row numbers
                    });
            }).draw(); // Refresh DataTable

            // Filter by Source
            $('#publicationTableSourceFilter').on('change', function() {
                var sourceValue = this.value;

                // Reset search if "All" is selected
                if (sourceValue === 'all sources') {
                    table.column(6).search('').draw(); // Column 6 is "Source"
                } else {
                    table.column(6).search(sourceValue).draw(); // Column 6 is "Source"
                }
            });

            // Filter by Lecturer (only available for role 3)
            $('#publicationTableLecturerFilter').on('change', function() {
                var lecturerValue = this.value;

                // Reset search if "All" is selected
                if (lecturerValue === 'all lecturer') {
                    table.column(1).search('').draw(); // Column 1 is "Lecturer"
                } else {
                    table.column(1).search(lecturerValue).draw(); // Column 1 is "Lecturer"
                }
            });

            // Filter by Year Range
            $('#applyPublicationYearRange').on('click', function() {
                var startYear = $('#startPublicationTableYear').val();
                var endYear = $('#endPublicationTableYear').val();

                // Apply year range filter
                table.rows().every(function() {
                    var publicationYear = $(this.node()).data('year');

                    // Check if the publication year falls within the selected range
                    if ((startYear && publicationYear < startYear) || (endYear && publicationYear >
                            endYear)) {
                        $(this.node()).hide(); // Hide row if it doesn't match the range
                    } else {
                        $(this.node()).show(); // Show row if it matches the range
                    }
                });

                // Redraw the table to apply the changes
                table.draw();
            });
        });
    </script>
