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
                            data-lecturer="{{ $publication->user->name ?? 'N/A' }}"
                            data-year="{{ \Carbon\Carbon::parse($publication->publication_date)->year }}">
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

    <!-- Highcharts Publications Diagram -->
    <div class="mt-8">
        <h1>Highcharts Diagram for Publications</h1>
        <div class="mb-4">
            <label for="publicationChartSourceFilter" class="font-bold">Filter by Source:</label>
            <select id="publicationChartSourceFilter" class="p-2 border rounded">
                <option value="all">All Sources</option>
                <option value="Google Scholar">Google Scholar</option>
                <option value="Scopus">Scopus</option>
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
            <button id="applyPublicationChartYearRange" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">
                Apply Year Range
            </button>
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

        <!-- Year Range Filter for Citations -->
        <div class="mb-4">
            <label for="startCitationChartYear" class="font-bold">Start Year:</label>
            <input type="number" id="startCitationChartYear" class="p-2 border rounded" placeholder="Start Year"
                min="1900" max="2100">
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

<script>
    $(document).ready(function() {
        // Highcharts Initialization for Publications
        function loadPublicationChart(data) {
            Highcharts.chart('publicationChartContainer', {
                chart: {
                    type: 'line',
                },
                title: {
                    text: 'Publications Over The Years',
                },
                subtitle: {
                    text: 'Source: Publications Table',
                },
                xAxis: {
                    categories: data.years,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Number of Publications'
                    }
                },
                series: [{
                    name: 'Publications',
                    data: data.counts
                }],
                exporting: {
                    enabled: true,
                },
            });
        }

        // Generate Chart Data for Publications
        // Generate Chart Data for Publications
        function generateChartData(filterSource, startYear, endYear) {
            const publications = @json($publications); // Pass PHP data to JavaScript
            const yearPublicationMap = {};

            publications.forEach(publication => {
                // Check if publication_date exists, otherwise categorize as "Tidak Diketahui"
                let year = publication.publication_date ? new Date(publication.publication_date)
                    .getFullYear() : 'Tidak Diketahui';

                const source = publication.source;

                if ((filterSource === 'all' || filterSource === source) &&
                    (!startYear || year >= startYear) &&
                    (!endYear || year <= endYear)) {
                    if (!yearPublicationMap[year]) {
                        yearPublicationMap[year] = 0;
                    }
                    yearPublicationMap[year] += 1; // Count publications per year
                }
            });

            const years = Object.keys(yearPublicationMap).sort((a, b) => {
                if (a === 'Tidak Diketahui') return 1; // Ensure "Tidak Diketahui" is shown last
                if (b === 'Tidak Diketahui') return -1;
                return a - b;
            });

            const counts = years.map(year => yearPublicationMap[year]);

            return {
                years: years,
                counts: counts,
            };
        }


        // Load Chart with Default Data for Publications
        const initialPublicationData = generateChartData('all', null, null);
        loadPublicationChart(initialPublicationData);

        // Apply Filters for Publication Chart
        $('#applyPublicationChartYearRange').on('click', function() {
            const startYear = $('#startPublicationChartYear').val();
            const endYear = $('#endPublicationChartYear').val();
            const filterSource = $('#publicationChartSourceFilter').val();

            const filteredPublicationData = generateChartData(filterSource, startYear, endYear);
            loadPublicationChart(filteredPublicationData);
        });

        $('#publicationChartSourceFilter').on('change', function() {
            const filterSource = this.value;
            const startYear = $('#startPublicationChartYear').val();
            const endYear = $('#endPublicationChartYear').val();

            const filteredPublicationData = generateChartData(filterSource, startYear, endYear);
            loadPublicationChart(filteredPublicationData);
        });
    });
</script>


<script>
    $(document).ready(function() {
        // Highcharts Initialization
        function loadCitationChart(data) {
            Highcharts.chart('citationChartContainer', {
                chart: {
                    type: 'line',
                },
                title: {
                    text: 'Citations Over Years',
                },
                subtitle: {
                    text: 'Source: Publications Table',
                },
                xAxis: {
                    categories: data.years, // Dynamic years
                    title: {
                        text: 'Year',
                    },
                },
                yAxis: {
                    title: {
                        text: 'Citations',
                    },
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' citations',
                },
                series: data.series, // Dynamic series
                credits: {
                    enabled: false,
                },
                exporting: {
                    enabled: true,
                },
            });
        }

        // Generate Chart Data for Citations
        function generateChartData(filterSource, startYear, endYear) {
            const publications = @json($publications); // Pass PHP data to JavaScript
            const yearCitationMap = {};

            publications.forEach(publication => {
                // Check if publication_date exists, otherwise categorize as "Tidak Diketahui"
                let year = publication.publication_date ? new Date(publication.publication_date)
                    .getFullYear() : 'Tidak Diketahui';
                const source = publication.source;
                const citations = parseInt(publication.citations, 10) || 0;

                if ((filterSource === 'all' || filterSource === source) &&
                    (!startYear || year >= startYear) &&
                    (!endYear || year <= endYear)) {
                    if (!yearCitationMap[year]) {
                        yearCitationMap[year] = 0;
                    }
                    yearCitationMap[year] += citations;
                }
            });

            const years = Object.keys(yearCitationMap).sort((a, b) => {
                if (a === 'Tidak Diketahui') return 1; // Ensure "Tidak Diketahui" is shown last
                if (b === 'Tidak Diketahui') return -1;
                return a - b;
            });

            const citations = years.map(year => yearCitationMap[year]);

            return {
                years: years,
                series: [{
                    name: filterSource === 'all' ? 'All Sources' : filterSource,
                    data: citations,
                }],
            };
        }



        // Load Chart with Default Data
        const initialData = generateChartData('all', null, null);
        loadCitationChart(initialData);

        // Apply Filters for Citation Chart
        $('#applyCitationChartYearRange').on('click', function() {
            const startYear = $('#startCitationChartYear').val();
            const endYear = $('#endCitationChartYear').val();
            const filterSource = $('#citationChartSourceFilter').val();

            const filteredData = generateChartData(filterSource, startYear, endYear);
            loadCitationChart(filteredData);
        });

        $('#citationChartSourceFilter').on('change', function() {
            const filterSource = this.value;
            const startYear = $('#startCitationChartYear').val();
            const endYear = $('#endCitationChartYear').val();

            const filteredData = generateChartData(filterSource, startYear, endYear);
            loadCitationChart(filteredData);
        });
    });
</script>


{{-- <script>
    $(document).ready(function() {
        // Highcharts for Publications
        function renderPublicationChart(data) {
            Highcharts.chart('publicationChartContainer', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Publications Over the Years'
                },
                xAxis: {
                    categories: data.years,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Number of Publications'
                    }
                },
                series: [{
                    name: 'Publications',
                    data: data.counts
                }]
            });
        }

        // Initial data processing for publications
        function getPublicationData(filterSource, startYear, endYear) {
            let publicationData = @json($publications); // Data from Laravel
            let filteredData = {};

            // Filter publications by source and year range
            publicationData.forEach(publication => {
                let year = new Date(publication.publication_date).getFullYear();
                let source = publication.source;

                // Apply filters
                if ((filterSource === 'all' || source === filterSource) &&
                    (!startYear || year >= startYear) &&
                    (!endYear || year <= endYear)) {
                    filteredData[year] = (filteredData[year] || 0) + 1;
                }
            });

            // Convert filtered data to sorted arrays for Highcharts
            let years = Object.keys(filteredData).map(Number).sort((a, b) => a - b);
            let counts = years.map(year => filteredData[year]);

            return {
                years,
                counts
            };
        }

        // Render chart on page load
        let publicationChartData = getPublicationData('all', null, null);
        renderPublicationChart(publicationChartData);

        // Handle filter changes
        $('#publicationChartSourceFilter, #applyPublicationChartYearRange').on('change click', function() {
            let filterSource = $('#publicationChartSourceFilter').val();
            let startYear = parseInt($('#startPublicationChartYear').val());
            let endYear = parseInt($('#endPublicationChartYear').val());

            let updatedData = getPublicationData(filterSource, startYear, endYear);
            renderPublicationChart(updatedData);
        });
    });
</script> --}}
