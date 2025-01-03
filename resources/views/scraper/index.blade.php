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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Scraper Publications</h1>

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
        <form action="{{ route('scraper.deleteData') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all your data?');">
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
                                <tr>
                                    <td class="border p-2">{{ $index + 1 }}</td>
                                    <td class="border p-2">{{ $publication->author_name }}</td>
                                    <td class="border p-2">{{ $publication->title }}</td>
                                    <td class="border p-2">{{ $publication->journal_name }}</td>
                                    <td class="border p-2">{{ $publication->publication_date }}</td>
                                    <td class="border p-2">{{ $publication->citations }}</td>
                                    <td class="border p-2">{{ $publication->source }}</td>
                                    <td class="border p-2">
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
                @endif
            </div>
        </div>

       <!-- Highcharts Diagram -->
       <div class="mt-8">
        <h1>Highcharts Diagram for Total Publications</h1>
        <div class="mb-4">
            <label for="sourceFilter" class="font-bold">Filter by Source:</label>
            <select id="sourceFilter" class="p-2 border rounded">
                <option value="all">All Sources</option>
                <option value="Google Scholar">Google Scholar</option>
                <option value="Scopus">Scopus</option>
            </select>
        </div>
        <div id="chartContainer" class="mt-4"></div>
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
        <div id="citationChartContainer" class="mt-4"></div>
    </div>

    <script>
        $(document).ready(function () {
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
                    series: [
                        {
                            name: 'Citations',
                            data: citationCounts,
                        },
                    ],
                });
            }

            // Initial citation chart render
            updateCitationChart(allCitationData);

            // Handle filter change for citations
            $('#citationSourceFilter').on('change', function () {
                var selectedSource = $(this).val();

                if (selectedSource === 'all') {
                    updateCitationChart(allCitationData);
                } else {
                    var filteredData = allCitationData.filter(item => item.source === selectedSource);
                    updateCitationChart(filteredData);
                }
            });
        });
    </script>

</div>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTable pada tabel publikasi
        $('table').DataTable({
            "paging": true, // Enable pagination
            "searching": true, // Enable search bar
            "ordering": true, // Enable column ordering
            "info": true, // Show info
            "lengthChange": false, // Disable length change (number of rows displayed)
            "responsive": true // Make table responsive
        });
    });
</script>
{{-- <script>
    $(document).ready(function () {
        var chartData = @json($formattedChartData);
        console.log(chartData); // Debug output

        // Konversi chartData dari objek menjadi array
        var chartDataArray = Object.values(chartData);

        if (!Array.isArray(chartDataArray)) {
            console.error('chartDataArray is not an array:', chartDataArray);
            return;
        }

        var categories = chartDataArray.map(function (item) {
            return item.year;
        });
        var publicationCounts = chartDataArray.map(function (item) {
            return item.count;
        });

        Highcharts.chart('chartContainer', {
            chart: {
                type: 'column',
            },
            title: {
                text: 'Total Publications per Year',
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
                    text: 'Number of Publications',
                },
            },
            series: [
                {
                    name: 'Publications',
                    data: publicationCounts,
                },
            ],
        });
    });
</script> --}}

<script>
    $(document).ready(function () {
        var allChartData = @json($formattedChartData);

        // Filter chart data based on source
        function updateChart(filteredData) {
            var categories = filteredData.map(item => item.year);
            var publicationCounts = filteredData.map(item => item.count);

            Highcharts.chart('chartContainer', {
                chart: {
                    type: 'column',
                },
                title: {
                    text: 'Total Publications per Year',
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
                        text: 'Number of Publications',
                    },
                },
                series: [
                    {
                        name: 'Publications',
                        data: publicationCounts,
                    },
                ],
            });
        }

        // Initial chart render
        updateChart(allChartData);

        // Handle filter change
        $('#sourceFilter').on('change', function () {
            var selectedSource = $(this).val();

            if (selectedSource === 'all') {
                updateChart(allChartData);
            } else {
                var filteredData = allChartData.filter(item => item.source === selectedSource);
                updateChart(filteredData);
            }
        });
    });
</script>


</body>
