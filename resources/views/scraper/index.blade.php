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
                <label for="startYear" class="font-bold">Start Year:</label>
                <input type="number" id="startYear" class="p-2 border rounded" placeholder="Start Year" min="1900"
                    max="2100">

                <label for="endYear" class="font-bold">End Year:</label>
                <input type="number" id="endYear" class="p-2 border rounded" placeholder="End Year" min="1900"
                    max="2100">

                <button id="applyYearRange" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Apply Year
                    Range</button>
            </div>

            <h2 class="font-semibold text-xl">Publications</h2>
            <div class="mb-4">
                <label for="publicationSourceFilter" class="font-bold">Filter by Source:</label>
                <select id="publicationSourceFilter" class="p-2 border rounded">
                    <option value="all">All Sources</option>
                    <option value="Google Scholar">Google Scholar</option>
                    <option value="Scopus">Scopus</option>
                </select>
            </div>
            <div class="overflow-x-auto">
                @if ($publications->isEmpty())
                    <p class="mt-4">Tidak ada data publikasi yang tersedia.</p>
                @else
                    <table id="publicationsTable" class="min-w-full mt-4 border-collapse border border-gray-300">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="border p-2">#</th>
                                <th class="border p-2">Author</th>
                                <th class="border p-2">Title</th>
                                <th class="border p-2">Journal</th>
                                <th class="border p-2">Publication Date</th>
                                <th class="border p-2">Citations</th>
                                <th class="border p-2">Source</th>
                                <th class="border p-2">Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($publications as $index => $publication)
                                <tr data-source="{{ $publication->source }}"> <!-- Tambahkan atribut data-source -->
                                    <td class="border p-2">{{ $index + 1 }}</td>
                                    <td class="border p-2">{{ $publication->author_name }}</td>
                                    <td class="border p-2">{{ $publication->title }}</td>
                                    <td class="border p-2">{{ $publication->journal_name }}</td>
                                    <td class="border p-2">{{ $publication->publication_date }}</td>
                                    <td class="border p-2">{{ $publication->citations }}</td>
                                    <td class="border p-2">{{ $publication->source }}</td>
                                    <td class="border p-2">
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
                @endif
            </div>
        </div>

        <!-- Highcharts Diagrams -->
        <div class="mt-8">
            <h1>Highcharts Diagram for Total Publications</h1>
            <div class="mb-4">
                <label for="sourceFilter" class="font-bold">Filter by Source:</label>
                <select id="sourceFilter" class="p-2 border rounded">
                    <option value="all">All Sources</option>
                    <option value="Scopus">Scopus</option>
                    <option value="Google Scholar">Google Scholar</option>
                </select>
            </div>

            <!-- Year Range Filter for Publications -->
            <div class="mb-4">
                <label for="publicationStartYear" class="font-bold">Start Year:</label>
                <input type="number" id="publicationStartYear" class="p-2 border rounded" placeholder="Start Year"
                    min="1900" max="2100">
                <label for="publicationEndYear" class="font-bold">End Year:</label>
                <input type="number" id="publicationEndYear" class="p-2 border rounded" placeholder="End Year"
                    min="1900" max="2100">
                <button id="applyPublicationYearRange" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Apply
                    Year Range</button>
            </div>

            <div id="publicationChartContainer" class="mt-4"></div>
        </div>

        <div class="mt-8">
            <h1>Highcharts Diagram for Citations</h1>
            <div class="mb-4">
                <label for="citationSourceFilter" class="font-bold">Filter by Source:</label>
                <select id="citationSourceFilter" class="p-2 border rounded">
                    <option value="all">All Sources</option>
                    <option value="Google Scholar">Google Scholar</option>
                    <option value="Scopus">Scopus</option>
                </select>
            </div>

            <!-- Year Range Filter for Citations -->
            <div class="mb-4">
                <label for="citationStartYear" class="font-bold">Start Year:</label>
                <input type="number" id="citationStartYear" class="p-2 border rounded" placeholder="Start Year"
                    min="1900" max="2100">
                <label for="citationEndYear" class="font-bold">End Year:</label>
                <input type="number" id="citationEndYear" class="p-2 border rounded" placeholder="End Year"
                    min="1900" max="2100">
                <button id="applyCitationYearRange" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Apply Year
                    Range</button>
            </div>
            <div id="citationChartContainer" class="mt-4"></div>
        </div>

    </div>

    <!-- JS Scripts -->
    <script>
        $(document).ready(function() {
            var table = $('#publicationsTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthChange: false,
                responsive: true
            });

            // Apply Year Range Filter
            $('#applyYearRange').on('click', function() {
                var startYear = $('#startYear').val();
                var endYear = $('#endYear').val();

                // Reset previous filters if any
                table.column(4).search(''); // Clear previous search for publication date

                if (startYear && endYear) {
                    // Apply the filter based on the start and end year range
                    table.rows().every(function() {
                        var data = this.data();
                        var publicationYear = new Date(data[4])
                            .getFullYear(); // Assuming publication date is in the 5th column

                        if (publicationYear >= startYear && publicationYear <= endYear) {
                            // Show the row
                            table.row(this).nodes().to$().show();
                        } else {
                            // Hide the row
                            table.row(this).nodes().to$().hide();
                        }
                    });
                } else {
                    // If no range, reset and show all rows
                    table.rows().every(function() {
                        table.row(this).nodes().to$().show(); // Show all rows
                    });
                }

                // Redraw the table after filtering
                table.draw();
            });

            // Filter publications by source (Google Scholar, Scopus, or All)
            $('#publicationSourceFilter').on('change', function() {
                var selectedSource = $(this).val();

                // Show or hide rows based on selected source
                $('#publicationsTable tbody tr').each(function() {
                    var source = $(this).data('source');
                    if (selectedSource === 'all' || source === selectedSource) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>

    <script>
        var allChartData = @json($formattedChartData);

        // Function to update the Publications Chart
        function updateChart(filteredData) {
            var categories = filteredData.map(item => item.year);
            var publicationCounts = filteredData.map(item => item.count);

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

        // Function to apply both filters and update the chart
        function applyFilters() {
            var selectedSource = $('#sourceFilter').val();
            var startYear = $('#publicationStartYear').val();
            var endYear = $('#publicationEndYear').val();

            // Filter data based on source
            var filteredData = selectedSource === 'all' ? allChartData : allChartData.filter(item => item.source ===
                selectedSource);

            // Filter data based on year range if both start and end years are provided and valid
            if (startYear && endYear && startYear <= endYear) {
                filteredData = filteredData.filter(item => item.year >= startYear && item.year <= endYear);
            } else if (startYear || endYear) {
                alert('Please provide a valid year range.');
                return;
            }

            // Update the chart with the filtered data
            updateChart(filteredData);
        }

        // Update chart when source filter changes
        $('#sourceFilter').on('change', function() {
            applyFilters(); // Apply both filters and update chart
        });

        // Update chart when year range filter changes
        $('#applyPublicationYearRange').on('click', function() {
            applyFilters(); // Apply both filters and update chart
        });

        // Initial chart rendering for both filters
        applyFilters(); // Initial chart for Publications
    </script>

    <script>
        $(document).ready(function() {
            var allCitationData = @json($formattedCitationData);

            // Function to update citation chart
            function updateCitationChart(filteredData) {
                var categories = filteredData.map(item => item.year);
                var citationCounts = filteredData.map(item => item.count);

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

            // Initial citation chart render
            updateCitationChart(allCitationData);

            // Handle filter change for citations
            $('#citationSourceFilter').on('change', function() {
                var selectedSource = $(this).val();

                if (selectedSource === 'all') {
                    updateCitationChart(allCitationData);
                } else {
                    var filteredData = allCitationData.filter(item => item.source === selectedSource);
                    updateCitationChart(filteredData);
                }
            });
        });

        // Apply Year Range Filter for Citations
        $('#applyCitationYearRange').on('click', function() {
            var startYear = $('#citationStartYear').val();
            var endYear = $('#citationEndYear').val();

            if (startYear && endYear) {
                var filteredData = allCitationData.filter(item => {
                    var year = parseInt(item.year);
                    return year >= startYear && year <= endYear;
                });
                updateCitationChart(filteredData); // Update the chart
            }
        });

        // Initial citation chart render
        updateCitationChart(allCitationData);
    </script>
</body>
