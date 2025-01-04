<head>
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
</head>

<body class="bg-gray-100">
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

            <h2 class="font-semibold text-xl">Publications</h2>
            <div class="mb-4">
                <label for="publicationTableSourceFilter" class="font-bold">Filter by Source:</label>
                <select id="publicationTableSourceFilter" class="p-2 border rounded">
                    <option value="all">All Sources</option>
                    <option value="Google Scholar">Google Scholar</option>
                    <option value="Scopus">Scopus</option>
                </select>
            </div>
            <div class="overflow-x-auto">
                <table id="publicationTable" class="min-w-full mt-4 border-collapse border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th>#</th>
                            {{-- @if (auth()->user()->role_id == 3)
                                <th>Dosen</th>
                            @endif --}}
                            <th>Title</th>
                            <th>Journal</th>
                            <th>Publication Date</th>
                            <th>Citations</th>
                            <th>Source</th>
                            <th>Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($publications as $index => $publication)
                            <tr data-source="{{ $publication->source }}">
                                <td>{{ $index + 1 }}</td>
                                {{-- @if (auth()->user()->role_id == 3)
                                    <td>{{ $publication->user->name ?? 'N/A' }}</td>
                                @endif --}}
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

            <!-- Year Range Filter for Publications -->
            <div class="mb-4">
                <label for="startPublicationChartYear" class="font-bold">Start Year:</label>
                <input type="number" id="startPublicationChartYear" class="p-2 border rounded" placeholder="Start Year"
                    min="1900" max="2100">
                <label for="endPublicationChartYear" class="font-bold">End Year:</label>
                <input type="number" id="endPublicationChartYear" class="p-2 border rounded" placeholder="End Year"
                    min="1900" max="2100">
                <button id="applyPublicationChartYearRange"
                    class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Apply
                    Year Range</button>
            </div>

            <div id="publicationChartContainer" class="mt-4"></div>
        </div>

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
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                lengthChange: true,
                pageLength: 10,
                lengthMenu: [5, 10, 50, 100],
                // columnDefs: [{
                //         targets: '_all',
                //         defaultContent: '-'
                //     }
                // ]
                // columnDefs: [{
                //         targets: [0, 7],
                //         orderable: false,
                //     }, // Disable sorting on specific columns
                // ],
            });

            // Store the original dataset for filtering
            var originalData = [];
            $('#publicationTable tbody tr').each(function() {
                var row = $(this);
                originalData.push({
                    element: row,
                    source: row.data('source'),
                    publicationYear: new Date(row.find('td:nth-child(5)').text()).getFullYear(),
                });
            });

            // Filter function
            function applyFilters() {
                var selectedSource = $('#publicationTableSourceFilter').val();
                var startYear = parseInt($('#startPublicationTableYear').val());
                var endYear = parseInt($('#endPublicationTableYear').val());

                // Validate year range
                if (!isNaN(startYear) && !isNaN(endYear) && startYear > endYear) {
                    alert('Please provide a valid year range.');
                    return;
                }

                // Clear existing table rows
                table.clear().draw();

                // Filter data and redraw
                originalData.forEach(function(data) {
                    var matchesSource =
                        selectedSource === 'all' || data.source === selectedSource;
                    var matchesYear =
                        (!isNaN(startYear) &&
                            !isNaN(endYear) &&
                            startYear <= data.publicationYear &&
                            data.publicationYear <= endYear) ||
                        (isNaN(startYear) || isNaN(endYear));

                    if (matchesSource && matchesYear) {
                        table.row.add(data.element.clone().get(0)).draw(false);
                    }
                });
            }

            // Apply year range filter
            $('#applyPublicationYearRange').on('click', applyFilters);

            // Apply source filter
            $('#publicationTableSourceFilter').on('change', applyFilters);
        });
    </script>


    <script>
        $(document).ready(function() {

            var allPublicationChartData = @json($formattedChartData);

            function updatePublicationChart(filteredData) {
                // Memisahkan tahun dari tanggal dan mengurutkan data berdasarkan tahun
                var categories = filteredData.map(item => {
                    // Ambil tahun saja dari string tanggal
                    return new Date(item.year).getFullYear();
                });

                // Menghapus duplikat tahun dan mengurutkannya secara numerik
                categories = [...new Set(categories)].sort((a, b) => a - b);

                // Menyesuaikan data berdasarkan kategori tahun yang sudah terurut
                var publicationCounts = categories.map(year => {
                    // Menjumlahkan publikasi berdasarkan tahun
                    return filteredData.filter(item => new Date(item.year).getFullYear() === year)
                        .reduce((sum, item) => sum + item.count, 0);
                });

                Highcharts.chart('publicationChartContainer', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Total Publications per Year'
                    },
                    xAxis: {
                        categories: categories,
                        title: {
                            text: 'Year'
                        },
                        labels: {
                            formatter: function() {
                                return this.value;
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Number of Publications'
                        }
                    },
                    series: [{
                        name: 'Publications',
                        data: publicationCounts
                    }]
                });
            }

            function applyPublicationChartFilters() {
                var selectedSource = $('#publicationChartSourceFilter').val();
                var startYear = $('#startPublicationChartYear').val();
                var endYear = $('#endPublicationChartYear').val();

                // Filter by source
                var filteredData = selectedSource === 'all' ?
                    allPublicationChartData :
                    allPublicationChartData.filter(item => item.source === selectedSource);

                // Filter by year range
                if (startYear && endYear && startYear <= endYear) {
                    filteredData = filteredData.filter(item => new Date(item.year).getFullYear() >= startYear &&
                        new Date(item.year).getFullYear() <= endYear);
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

            $('#applyPublicationChartYearRange').on('click', function() {
                applyPublicationChartFilters();
            });

            // Initial application of filters
            applyPublicationChartFilters();
        });
    </script>

    <script>
        var allCitationChartData = @json($formattedCitationData);

        function updateCitationChart(filteredData) {
            // Memisahkan tahun dari tanggal dan mengurutkannya berdasarkan tahun
            var categories = filteredData.map(item => {
                // Ambil tahun saja dari string tanggal
                return new Date(item.year).getFullYear();
            });

            // Menghapus duplikat tahun dan mengurutkannya secara numerik
            categories = [...new Set(categories)].sort((a, b) => a - b);

            // Menyesuaikan data berdasarkan kategori tahun yang sudah terurut
            var citationCounts = categories.map(year => {
                // Menjumlahkan sitasi berdasarkan tahun
                return filteredData.filter(item => new Date(item.year).getFullYear() === year)
                    .reduce((sum, item) => sum + item.count, 0);
            });

            Highcharts.chart('citationChartContainer', {
                chart: {
                    type: 'column',
                },
                title: {
                    text: 'Total Citations per Year',
                },
                xAxis: {
                    categories: categories,
                    title: {
                        text: 'Year',
                    },
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Number of Citations',
                    },
                },
                series: [{
                    name: 'Citations',
                    data: citationCounts,
                }, ],
            });
        }

        function applyCitationChartFilters() {
            var selectedSource = $('#citationChartSourceFilter').val();
            var startYear = $('#startCitationChartYear').val();
            var endYear = $('#endCitationChartYear').val();

            // Filter by source
            var filteredData = selectedSource === 'all' ? allCitationChartData :
                allCitationChartData.filter(item => item.source === selectedSource);

            // Filter by year range
            if (startYear && endYear && startYear <= endYear) {
                filteredData = filteredData.filter(item => new Date(item.year).getFullYear() >= startYear && new Date(item
                    .year).getFullYear() <= endYear);
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

        $('#applyCitationChartYearRange').on('click', function() {
            applyCitationChartFilters();
        });

        // Initial application of filters
        applyCitationChartFilters();
    </script>
</body>
