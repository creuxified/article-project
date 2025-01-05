<!-- CSS DataTables -->
<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">

<!-- JS DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<!-- Highcharts Library -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Scraper Publications</h1>

    <!-- User Info -->
    <div class="mb-4">
        <p class="text-lg mb-2">Current User ID: {{ auth()->user()->id }}</p>
        <p class="text-lg mb-4">Scholar ID: {{ auth()->user()->scholar }}</p>
        <p class="text-lg mb-4">Scopus ID: {{ auth()->user()->scopus }}</p>
    </div>

    <!-- Success or Error Notification -->
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 mb-4 rounded">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="bg-red-500 text-white p-4 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Delete Data Button -->
    <form action="{{ route('scraper.deleteData') }}" method="POST"
        onsubmit="return confirm('Are you sure you want to delete all your data?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded mb-4">Delete All Data</button>
    </form>

    <!-- Scrape Form -->
    <div class="mb-4">
        <form action="{{ route('scraper.scrape') }}" method="POST">
            @csrf
            <div class="flex items-center space-x-4">
                <label for="author_id_scholar" class="font-bold">Scholar Author ID</label>
                <input type="text" name="author_id_scholar" id="author_id_scholar" class="p-2 border rounded"
                    value="{{ auth()->user()->scholar }}" placeholder="Enter Google Scholar Author ID" required>

                <label for="author_id_scopus" class="font-bold">Scopus Author ID</label>
                <input type="text" name="author_id_scopus" id="author_id_scopus" class="p-2 border rounded"
                    value="{{ auth()->user()->scopus }}" placeholder="Enter Scopus Author ID" required>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Scrape</button>
            </div>
        </form>
    </div>

    <!-- Publications Table -->
    <div class="mt-8">
        <h2 class="font-semibold text-xl">Publications</h2>
        <div class="mb-4">
            <label for="publicationTableSourceFilter" class="font-bold">Filter by Source:</label>
            <select id="publicationTableSourceFilter" class="p-2 border rounded">
                <option value="all sources">All Sources</option>
                <option value="Google Scholar">Google Scholar</option>
                <option value="Scopus">Scopus</option>
            </select>
        </div>
        @if (auth()->user()->role_id == 3)
            <div class="mb-4">
                <label for="publicationTableLecturerFilter" class="font-bold">Filter by Lecturer:</label>
                <select id="publicationTableLecturerFilter" class="p-2 border rounded">
                    <option value="all lecturer">All Lecturer</option>
                    @foreach ($publications->pluck('user.name')->unique() as $name)
                        <option value="{{ $name }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Year Range Filter -->
        <div class="mb-4">
            <label for="startPublicationTableYear" class="font-bold">Start Year:</label>
            <input type="number" id="startPublicationTableYear" class="p-2 border rounded" placeholder="Start Year"
                min="1900" max="2100">

            <label for="endPublicationTableYear" class="font-bold">End Year:</label>
            <input type="number" id="endPublicationTableYear" class="p-2 border rounded" placeholder="End Year"
                min="1900" max="2100">

            <button id="applyPublicationYearRange" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Apply Year
                Range</button>
        </div>

        {{-- <!-- Year Range Filter -->
        <div class="mb-4">
            <label for="startPublicationTableYear" class="font-bold">Start Year:</label>
            <input type="number" id="startPublicationTableYear" class="p-2 border rounded" placeholder="Start Year"
                min="1900" max="2100">

            <label for="endPublicationTableYear" class="font-bold">End Year:</label>
            <input type="number" id="endPublicationTableYear" class="p-2 border rounded" placeholder="End Year"
                min="1900" max="2100">

            <button id="applyPublicationYearRange" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Apply Year
                Range</button>
        </div> --}}
        <div class="overflow-x-auto">
            <table id="publicationTable" class="min-w-full mt-4 border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th>No.</th>
                        @if (auth()->user()->role_id == 3)
                            <th>Lecturer</th>
                        @endif
                        <th>Title</th>
                        <th>Journal</th>
                        <th>Publication Date</th>
                        <th>Citations</th>
                        <th>Source</th>
                        <th>Link</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($publications as $publication)
                        <tr data-source="{{ $publication->source }}"
                            data-lecturer="{{ $publication->user->name ?? 'N/A' }}" data-year="{{ \Carbon\Carbon::parse($publication->publication_date)->year }}">
                            <td id="no_baris"></td>
                            @if (auth()->user()->role_id == 3)
                                <td>{{ $publication->user->name ?? 'N/A' }}</td>
                            @endif
                            <td>{{ $publication->title }}</td>
                            <td>{{ $publication->journal_name }}</td>
                            <td>{{ $publication->publication_date }}</td>
                            <td>{{ $publication->citations }}</td>
                            <td>{{ $publication->source }}</td>
                            <td>
                                @if ($publication->link)
                                    <a href="{{ $publication->link }}" target="_blank"
                                        class="text-blue-500">View</a>
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


    <!-- Highcharts Publication Diagrams -->
    <div class="mt-8">
        <h1>Highcharts Diagram for Total Publications</h1>
        <div class="mb-4">
            <label for="publicationChartSourceFilter" class="font-bold">Filter by Source:</label>
            <select id="publicationChartSourceFilter" class="p-2 border rounded">
                <option value="all">All Sources</option>
                <option value="Scopus">Scopus</option>
                <option value="Google Scholar">Google Scholar</option>
            </select>
        </div>

        @if (auth()->user()->role_id == 3)
            <div class="mb-4">
                <label for="publicationChartLecturerFilter" class="font-bold">Filter by Lecturer:</label>
                <select id="publicationChartLecturerFilter" class="p-2 border rounded">
                    <option value="all">All Lecturer</option>
                    @foreach ($publications->pluck('user.name')->unique() as $name)
                        <option value="{{ $name }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Year Range Filter for Publications -->
        <div class="mb-4">
            <label for="startPublicationChartYear" class="font-bold">Start Year:</label>
            <input type="number" id="startPublicationChartYear" class="p-2 border rounded"
                placeholder="Start Year" min="1900" max="2100">
            <label for="endPublicationChartYear" class="font-bold">End Year:</label>
            <input type="number" id="endPublicationChartYear" class="p-2 border rounded" placeholder="End Year"
                min="1900" max="2100">
            <button id="applyPublicationChartYearRange"
                class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Apply
                Year Range</button>
        </div>

        <div id="publicationChartContainer" class="mt-4"></div>
    </div>

    <!-- Highcharts Citations Diagrams -->
    <div class="mt-8">
        <h1>Highcharts Diagram for Citations</h1>
        <div class="mb-4">
            <label for="citationChartSourceFilter" class="font-bold">Filter by Source:</label>
            <select id="citationChartSourceFilter" class="p-2 border rounded">
                <option value="all">All Sources</option>
                <option value="Google Scholar">Google Scholar</option>
                <option value="Scopus">Scopus</option>
            </select>
        </div>

        @if (auth()->user()->role_id == 3)
            <div class="mb-4">
                <label for="citationChartLecturerFilter" class="font-bold">Filter by Lecturer:</label>
                <select id="citationChartLecturerFilter" class="p-2 border rounded">
                    <option value="all">All Lecturer</option>
                    @foreach ($publications->pluck('user.name')->unique() as $name)
                        <option value="{{ $name }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Year Range Filter for Citations -->
        <div class="mb-4">
            <label for="startCitationChartYear" class="font-bold">Start Year:</label>
            <input type="number" id="startCitationChartYear" class="p-2 border rounded"
                placeholder="Start Year" min="1900" max="2100">
            <label for="endCitationChartYear" class="font-bold">End Year:</label>
            <input type="number" id="endCitationChartYear" class="p-2 border rounded" placeholder="End Year"
                min="1900" max="2100">
            <button id="applyCitationChartYearRange" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Apply
                Year
                Range</button>
        </div>
        <div id="citationChartContainer" class="mt-4"></div>
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
                if ((startYear && publicationYear < startYear) || (endYear && publicationYear > endYear)) {
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

{{-- <script>
    $(document).ready(function() {
        var table = $('#publicationTable').DataTable({
            paging: true, // enable pagination
            searching: true, // enable searching
            ordering: true, // enable column sorting
            columnDefs: [{
                targets: [0], // Disable sorting on the first column (No.)
                orderable: false
            }]
        });

        // Filter by Source
        $('#publicationTableSourceFilter').on('change', function() {
            var sourceValue = this.value;

            // Reset pencarian jika "All" dipilih
            if (sourceValue === 'all sources') {
                table.column(6).search('').draw(); // Column 6 is "Source"
            } else {
                table.column(6).search(sourceValue).draw(); // Column 6 is "Source"
            }
        });

        // Filter by Lecturer (only available for role 3)
        $('#publicationTableLecturerFilter').on('change', function() {
            var lecturerValue = this.value;

            // Reset pencarian jika "All" dipilih
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
            var yearFilter = '';
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
            console.log('tes');
            table.draw();
        });
    });
</script> --}}


<script>
    $(document).ready(function() {

        var allPublicationChartData = @json($publications);

        console.log(allPublicationChartData);

        function updatePublicationChart(filteredData) {
            // Group data by year and lecturer (user_id)
            var years = [...new Set(filteredData.map(item => {
                // Check if publication_date exists, otherwise categorize as "Tidak Diketahui"
                if (item.publication_date) {
                    return new Date(item.publication_date).getFullYear();
                } else {
                    return 'Tidak Diketahui'; // Add a separate category for unknown dates
                }
            }))].sort((a, b) => {
                // Sort years numerically and ensure "Tidak Diketahui" appears last
                if (a === 'Tidak Diketahui') return 1;
                if (b === 'Tidak Diketahui') return -1;
                return a - b;
            });

            // Prepare series data for each lecturer
            var seriesData = [];
            var lecturerNames = [...new Set(filteredData.map(item => item.user.name))];

            lecturerNames.forEach(lecturerName => {
                var lecturerData = years.map(year => {
                    // Filter data for the specific lecturer and year or "Tidak Diketahui"
                    return filteredData.filter(item => {
                        var publicationYear = item.publication_date ? new Date(item
                            .publication_date).getFullYear() : 'Tidak Diketahui';
                        return item.user.name === lecturerName && publicationYear ===
                            year;
                    }).length;
                });

                seriesData.push({
                    name: lecturerName,
                    data: lecturerData
                });
            });

            // Create the chart with the filtered and grouped data
            Highcharts.chart('publicationChartContainer', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Publications per Year by Lecturer'
                },
                xAxis: {
                    categories: years,
                    title: {
                        text: 'Year'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Number of Publications'
                    }
                },
                series: seriesData
            });
        }

        function applyPublicationChartFilters() {
            var selectedSource = $('#publicationChartSourceFilter').val();
            var selectedLecturer = $('#publicationChartLecturerFilter').val();
            var startYear = $('#startPublicationChartYear').val();
            var endYear = $('#endPublicationChartYear').val();

            // Filter by source
            var filteredData = selectedSource === 'all' ? allPublicationChartData : allPublicationChartData
                .filter(item => item.source === selectedSource);

            // Filter by lecturer
            if (selectedLecturer !== 'all') {
                filteredData = filteredData.filter(item => item.user.name === selectedLecturer);
            }

            // Filter by year range
            if (startYear && endYear && startYear <= endYear) {
                filteredData = filteredData.filter(item => {
                    var publicationYear = item.publication_date ? new Date(item.publication_date)
                        .getFullYear() : null;
                    return publicationYear >= startYear && publicationYear <= endYear;
                });
            } else if (startYear || endYear) {
                alert('Please provide a valid year range.');
                return;
            }

            // Update chart with sorted and filtered data
            updatePublicationChart(filteredData);
        }

        $('#publicationChartSourceFilter').on('change', function() {
            applyPublicationChartFilters();
        });

        $('#publicationChartLecturerFilter').on('change', function() {
            applyPublicationChartFilters();
        });

        $('#applyPublicationChartYearRange').on('click', function() {
            applyPublicationChartFilters();
        });

        // Initial application of filters
        applyPublicationChartFilters();
    });
</script>



<script>
    $(document).ready(function() {
        var allCitationChartData = @json($publications);

        console.log(allCitationChartData);

        function updateCitationChart(filteredData) {
            // Group data by year and lecturer (user_id)
            var years = [...new Set(filteredData.map(item => {
                // Check if publication_date exists, otherwise categorize as "Tidak Diketahui"
                if (item.publication_date) {
                    return new Date(item.publication_date).getFullYear();
                } else {
                    return 'Tidak Diketahui'; // Add a separate category for unknown dates
                }
            }))].sort((a, b) => {
                // Sort years numerically and ensure "Tidak Diketahui" appears last
                if (a === 'Tidak Diketahui') return 1;
                if (b === 'Tidak Diketahui') return -1;
                return a - b;
            });

            // Prepare series data for each lecturer
            var seriesData = [];
            var lecturerNames = [...new Set(filteredData.map(item => item.user.name))];

            lecturerNames.forEach(lecturerName => {
                var lecturerData = years.map(year => {
                    // Filter data for the specific lecturer and year or "Tidak Diketahui"
                    return filteredData.filter(item => {
                        var publicationYear = item.publication_date ? new Date(item
                            .publication_date).getFullYear() : 'Tidak Diketahui';
                        return item.user.name === lecturerName && publicationYear ===
                            year;
                    }).reduce((sum, item) => sum + item.citations,
                        0); // Sum the citations for each year
                });

                seriesData.push({
                    name: lecturerName,
                    data: lecturerData
                });
            });

            // Create the chart with the filtered and grouped data
            Highcharts.chart('citationChartContainer', {
                chart: {
                    type: 'spline'
                },
                title: {
                    text: 'Total Citations per Year by Lecturer'
                },
                xAxis: {
                    categories: years,
                    title: {
                        text: 'Year'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Total Citations'
                    }
                },
                series: seriesData
            });
        }

        function applyCitationChartFilters() {
            var selectedSource = $('#citationChartSourceFilter').val();
            var selectedLecturer = $('#citationChartLecturerFilter').val();
            var startYear = $('#startCitationChartYear').val();
            var endYear = $('#endCitationChartYear').val();

            // Filter by source
            var filteredData = selectedSource === 'all' ? allCitationChartData : allCitationChartData.filter(
                item => item.source === selectedSource);

            // Filter by lecturer
            if (selectedLecturer !== 'all') {
                filteredData = filteredData.filter(item => item.user.name === selectedLecturer);
            }

            // Filter by year range
            if (startYear && endYear && startYear <= endYear) {
                filteredData = filteredData.filter(item => {
                    var publicationYear = item.publication_date ? new Date(item.publication_date)
                        .getFullYear() : null;
                    return publicationYear >= startYear && publicationYear <= endYear;
                });
            } else if (startYear || endYear) {
                alert('Please provide a valid year range.');
                return;
            }

            // Update chart with sorted and filtered data
            updateCitationChart(filteredData);
        }

        $('#citationChartSourceFilter').on('change', function() {
            applyCitationChartFilters();
        });

        $('#citationChartLecturerFilter').on('change', function() {
            applyCitationChartFilters();
        });

        $('#applyCitationChartYearRange').on('click', function() {
            applyCitationChartFilters();
        });

        // Initial application of filters
        applyCitationChartFilters();
    });
</script>
