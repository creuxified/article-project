<div class="antialiased flex justify-center px-4">
    <div class="card bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-9xl p-3">
        <div class="card-header text-black p-4 rounded-t-lg">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-semibold">Publication Data</h1>
            </div>
        </div>

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
                <button id="applyPublicationChartYearRangeLecturer"
                    class="bg-blue-500 text-white px-4 py-2 rounded ml-2">
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
                    const year = publication.publication_date ? new Date(publication.publication_date)
                        .getFullYear() : null;
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
