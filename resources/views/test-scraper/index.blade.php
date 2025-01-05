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
        <h1>Highcharts Diagram for Publications Lecturer</h1>
        <div class="mb-4">
            <label for="filterSourcePublicationChartLecturer" class="font-bold">Filter by Source:</label>
            <select id="filterSourcePublicationChartLecturer" class="p-2 border rounded">
                <option value="all">All Sources</option>
                <option value="Google Scholar">Google Scholar</option>
                <option value="Scopus">Scopus</option>
            </select>
        </div>

        <!-- Year Range Filter for Publications -->
        <div class="mb-4">
            <label for="startYearPublicationChartLecturer" class="font-bold">Start Year:</label>
            <input type="number" id="startYearPublicationChartLecturer" class="p-2 border rounded"
                placeholder="Start Year" min="1900" max="2100">
            <label for="endYearPublicationChartLecturer" class="font-bold">End Year:</label>
            <input type="number" id="endYearPublicationChartLecturer" class="p-2 border rounded"
                placeholder="End Year" min="1900" max="2100">
            <button id="applyPublicationChartYearRangeLecturer" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">
                Apply Year Range
            </button>
        </div>
        <section>
            <!-- Statistik Singkat untuk Publications -->
            <div class="mt-4">
                <h3 class="font-bold">Statistik Singkat</h3>
                <div id="publicationStats" class="flex gap-4 mt-2">
                    <div class="bg-blue-100 p-4 rounded shadow">
                        <h4 class="font-bold">Banyak Publikasi</h4>
                        <p id="totalPublications" class="text-xl font-semibold">0</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded shadow">
                        <h4 class="font-bold">Banyak Publikasi Google Scholar</h4>
                        <p id="publicationScholar" class="text-xl font-semibold">0</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded shadow">
                        <h4 class="font-bold">Banyak Publikasi Scopus</h4>
                        <p id="publicationScopus" class="text-xl font-semibold">0</p>
                    </div>
                </div>
            </div>
        </section>
        <div id="containerPublicationChartLecturer" class="mt-4"></div>
    </div>

    <!-- Highcharts Citations Diagrams -->
    <div class="mt-8">
        <h1>Highcharts Diagram for Citations</h1>
        <div class="mb-4">
            <label for="filterSourceCitationChartLecturer" class="font-bold">Filter by Source:</label>
            <select id="filterSourceCitationChartLecturer" class="p-2 border rounded">
                <option value="all">All Sources</option>
                <option value="Google Scholar">Google Scholar</option>
                <option value="Scopus">Scopus</option>
            </select>
        </div>

        <!-- Year Range Filter for Citations -->
        <div class="mb-4">
            <label for="startYearCitationChartLecturer" class="font-bold">Start Year:</label>
            <input type="number" id="startYearCitationChartLecturer" class="p-2 border rounded"
                placeholder="Start Year" min="1900" max="2100">
            <label for="endYearCitationChartLecturer" class="font-bold">End Year:</label>
            <input type="number" id="endYearCitationChartLecturer" class="p-2 border rounded"
                placeholder="End Year" min="1900" max="2100">
            <button id="applyCitationChartYearRangeLecturer"
                class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Apply
                Year
                Range</button>
        </div>
        <section>
            <!-- Statistik Singkat untuk Citations -->
            <div class="mt-4">
                <h3 class="font-bold">Statistik Singkat</h3>
                <div id="citationStats" class="flex gap-4 mt-2">
                    <div class="bg-blue-100 p-4 rounded shadow">
                        <h4 class="font-bold">Banyak Publikasi</h4>
                        <p id="totalPublicationsCitation" class="text-xl font-semibold">0</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded shadow">
                        <h4 class="font-bold">Total Sitasi</h4>
                        <p id="totalCitations" class="text-xl font-semibold">0</p>
                    </div>
                </div>
            </div>
        </section>
        <div id="containerCitationChartLecturer" class="mt-4"></div>
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
            <input type="number" id="startPublicationChartYear" class="p-2 border rounded" placeholder="Start Year"
                min="1900" max="2100">
            <label for="endPublicationChartYear" class="font-bold">End Year:</label>
            <input type="number" id="endPublicationChartYear" class="p-2 border rounded" placeholder="End Year"
                min="1900" max="2100">
            <button id="applyPublicationChartYearRange" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Apply
                Year Range</button>
        </div>
        <h3>Statistik Singkat</h3>
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
            <input type="number" id="startCitationChartYear" class="p-2 border rounded" placeholder="Start Year"
                min="1900" max="2100">
            <label for="endCitationChartYear" class="font-bold">End Year:</label>
            <input type="number" id="endCitationChartYear" class="p-2 border rounded" placeholder="End Year"
                min="1900" max="2100">
            <button id="applyCitationChartYearRange" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Apply
                Year
                Range</button>
        </div>
        <h3>Statistik Singkat</h3>
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
            "autoWidth": false,
            "pageLength": 10 // Default 10 rows per page
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

        // Variables to store filter values
        var startYear = null;
        var endYear = null;
        var sourceValue = 'all sources';

        // Custom filter function
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var publicationYear = $(table.row(dataIndex).node()).data('year');
            var publicationSource = $(table.row(dataIndex).node()).data('source');

            // Filter by Year Range
            if (startYear && publicationYear < startYear) {
                return false;
            }
            if (endYear && publicationYear > endYear) {
                return false;
            }

            // Filter by Source
            if (sourceValue !== 'all sources' && publicationSource !== sourceValue) {
                return false;
            }

            return true; // Include rows that match all filters
        });

        // Apply Year Range Filter
        $('#applyPublicationYearRange').on('click', function() {
            startYear = parseInt($('#startPublicationTableYear').val()) || null;
            endYear = parseInt($('#endPublicationTableYear').val()) || null;

            // Validate if startYear > endYear
            if (startYear && endYear && startYear > endYear) {
                alert('Tahun awal tidak boleh lebih besar dari tahun akhir.');
                return; // Stop processing if invalid range
            }

            table.draw(); // Redraw the table with the updated filter
        });

        // Apply Source Filter
        $('#publicationTableSourceFilter').on('change', function() {
            sourceValue = this.value; // Update the source value
            table.draw(); // Redraw the table with the updated filter
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Highcharts Initialization for Publications
        function loadPublicationChart(data) {
            Highcharts.chart('containerPublicationChartLecturer', {
                chart: {
                    type: 'spline',
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
        function generateChartData(filterSource, startYear, endYear) {
            const publications = @json($publications); // Pass PHP data to JavaScript
            const yearPublicationMap = {};

            publications.forEach(publication => {
                let year = publication.publication_date ? new Date(publication.publication_date).getFullYear() : 'Tidak Diketahui';
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
                if (a === 'Tidak Diketahui') return 1;
                if (b === 'Tidak Diketahui') return -1;
                return a - b;
            });

            const counts = years.map(year => yearPublicationMap[year]);

            return {
                years: years,
                counts: counts,
            };
        }

        // Update Publication Stats
        function updatePublicationStats(filterSource, startYear, endYear) {
            const publications = @json($publications); // Data PHP
            let totalPublications = 0;
            let publicationScholar = 0;
            let publicationScopus = 0;

            publications.forEach(publication => {
                const year = publication.publication_date ? new Date(publication.publication_date).getFullYear() : null;
                const source = publication.source;

                if ((filterSource === 'all' || filterSource === source) &&
                    (!startYear || year >= startYear) &&
                    (!endYear || year <= endYear)) {

                    totalPublications++;

                    // Hitung jumlah publikasi berdasarkan sumber
                    if (source === 'Google Scholar') {
                        publicationScholar++;
                    } else if (source === 'Scopus') {
                        publicationScopus++;
                    }
                }
            });

            // Update Statistik Singkat untuk Publications
            $('#totalPublications').text(totalPublications);
            $('#publicationScholar').text(publicationScholar);
            $('#publicationScopus').text(publicationScopus);
        }

        // Load Chart with Default Data for Publications
        const initialPublicationData = generateChartData('all', null, null);
        loadPublicationChart(initialPublicationData);
        updatePublicationStats('all', null, null);

        // Apply Filters for Publication Chart and Stats
        $('#applyPublicationChartYearRangeLecturer').on('click', function() {
            const startYear = $('#startYearPublicationChartLecturer').val();
            const endYear = $('#endYearPublicationChartLecturer').val();
            const filterSource = $('#filterSourcePublicationChartLecturer').val();

            const filteredPublicationData = generateChartData(filterSource, startYear, endYear);
            loadPublicationChart(filteredPublicationData);
            updatePublicationStats(filterSource, startYear, endYear);
        });

        $('#filterSourcePublicationChartLecturer').on('change', function() {
            const filterSource = this.value;
            const startYear = $('#startYearPublicationChartLecturer').val();
            const endYear = $('#endYearPublicationChartLecturer').val();

            const filteredPublicationData = generateChartData(filterSource, startYear, endYear);
            loadPublicationChart(filteredPublicationData);
            updatePublicationStats(filterSource, startYear, endYear);
        });
    });
</script>
{{-- <script>
    $(document).ready(function() {
        // Highcharts Initialization for Publications
        function loadPublicationChart(data) {
            Highcharts.chart('containerPublicationChartLecturer', {
                chart: {
                    type: 'spline',
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
        $('#applyPublicationChartYearRangeLecturer').on('click', function() {
            const startYear = $('#startYearPublicationChartLecturer').val();
            const endYear = $('#endYearPublicationChartLecturer').val();
            const filterSource = $('#filterSourcePublicationChartLecturer').val();

            const filteredPublicationData = generateChartData(filterSource, startYear, endYear);
            loadPublicationChart(filteredPublicationData);
        });

        $('#filterSourcePublicationChartLecturer').on('change', function() {
            const filterSource = this.value;
            const startYear = $('#startYearPublicationChartLecturer').val();
            const endYear = $('#endYearPublicationChartLecturer').val();

            const filteredPublicationData = generateChartData(filterSource, startYear, endYear);
            loadPublicationChart(filteredPublicationData);
        });
    });
</script> --}}

<script>
    $(document).ready(function() {
        // Highcharts Initialization
        function loadCitationChart(data) {
            Highcharts.chart('containerCitationChartLecturer', {
                chart: {
                    type: 'areaspline',
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
        $('#applyCitationChartYearRangeLecturer').on('click', function() {
            const startYear = $('#startYearCitationChartLecturer').val();
            const endYear = $('#endYearCitationChartLecturer').val();
            const filterSource = $('#filterSourceCitationChartLecturer').val();

            const filteredData = generateChartData(filterSource, startYear, endYear);
            loadCitationChart(filteredData);
        });

        $('#filterSourceCitationChartLecturer').on('change', function() {
            const filterSource = this.value;
            const startYear = $('#startYearCitationChartLecturer').val();
            const endYear = $('#endYearCitationChartLecturer').val();

            const filteredData = generateChartData(filterSource, startYear, endYear);
            loadCitationChart(filteredData);
        });
    });
</script>

<script>
    // function updatePublicationStats(filterSource, startYear, endYear) {
    //     const publications = @json($publications); // Data PHP
    //     let totalPublications = 0;
    //     let publicationScholar = 0;
    //     let publicationScopus = 0;

    //     publications.forEach(publication => {
    //         const year = publication.publication_date ? new Date(publication.publication_date).getFullYear() :
    //             null;
    //         const source = publication.source;
    //         const citations = parseInt(publication.citations, 10) || 0;

    //         if ((filterSource === 'all' || filterSource === source) &&
    //             (!startYear || year >= startYear) &&
    //             (!endYear || year <= endYear)) {

    //             totalPublications++;

    //             // Hitung jumlah publikasi berdasarkan sumber
    //             if (source === 'Google Scholar') {
    //                 publicationScholar++;
    //             } else if (source === 'Scopus') {
    //                 publicationScopus++;
    //             }
    //         }
    //     });

    //     // Update Statistik Singkat untuk Publications
    //     $('#totalPublications').text(totalPublications);
    //     $('#publicationScholar').text(publicationScholar);
    //     $('#publicationScopus').text(publicationScopus);
    // }

    // Event Handler untuk Filters
    $('#applyPublicationChartYearRangeLecturer').on('click', function() {
        const startYear = $('#startYearPublicationChartLecturer').val();
        const endYear = $('#endYearPublicationChartLecturer').val();
        const filterSource = $('#filterSourcePublicationChartLecturer').val();

        updatePublicationStats(filterSource, startYear, endYear);
    });

    // Inisialisasi Statistik Singkat dengan Default Data
    $(document).ready(function() {
        updatePublicationStats('all', null, null); // Data default tanpa filter
    });




    function updateCitationStats(filterSource, startYear, endYear) {
        const publications = @json($publications); // Data PHP
        let totalPublications = 0;
        let totalCitations = 0;

        publications.forEach(publication => {
            const year = publication.publication_date ? new Date(publication.publication_date).getFullYear() :
                null;
            const source = publication.source;
            const citations = parseInt(publication.citations, 10) || 0;

            if ((filterSource === 'all' || filterSource === source) &&
                (!startYear || year >= startYear) &&
                (!endYear || year <= endYear)) {
                totalPublications++;
                totalCitations += citations;
            }
        });

        // Update Statistik Singkat untuk Citations
        $('#totalPublicationsCitation').text(totalPublications);
        $('#totalCitations').text(totalCitations);
    }

    // Event Handlers untuk Filters
    $('#applyPublicationChartYearRangeLecturer').on('click', function() {
        const startYear = $('#startYearPublicationChartLecturer').val();
        const endYear = $('#endYearPublicationChartLecturer').val();
        const filterSource = $('#filterSourcePublicationChartLecturer').val();

        updatePublicationStats(filterSource, startYear, endYear);
    });

    $('#applyCitationChartYearRangeLecturer').on('click', function() {
        const startYear = $('#startYearCitationChartLecturer').val();
        const endYear = $('#endYearCitationChartLecturer').val();
        const filterSource = $('#filterSourceCitationChartLecturer').val();

        updateCitationStats(filterSource, startYear, endYear);
    });

    // Inisialisasi Statistik Singkat dengan Default Data
    $(document).ready(function() {
        updatePublicationStats('all', null, null);
        updateCitationStats('all', null, null);
    });
</script>


{{-- <script>
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
</script> --}}



{{-- <script>
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
</script> --}}
