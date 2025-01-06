<section>
    <!-- Card untuk Highcharts Publications Diagram -->
    <div class="card bg-gray-800 shadow-lg rounded-lg overflow-hidden w-full max-w-9xl p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

            <!-- Filter by Source -->
            <div class="bg-blue-100 p-4 rounded shadow">
                <label for="filterSourcePublicationChartLecturer" class="font-bold text-gray-700">Filter by Source:</label>
                <select id="filterSourcePublicationChartLecturer" class="p-2 border rounded w-full">
                    <option value="all">All Sources</option>
                    <option value="Scopus">Scopus</option>
                    <option value="Google Scholar">Google Scholar</option>
                </select>
            </div>

            <!-- Year Range Filter -->
            <div class="bg-blue-100 p-4 rounded shadow col-span-1 md:col-span-2 lg:col-span-3">
                <label for="startYearPublicationChartLecturer" class="font-bold text-gray-700">Start Year:</label>
                <input type="number" id="startYearPublicationChartLecturer" class="p-2 border rounded w-full" placeholder="Start Year" min="1900" max="2100">

                <label for="endYearPublicationChartLecturer" class="font-bold text-gray-700 mt-2">End Year:</label>
                <input type="number" id="endYearPublicationChartLecturer" class="p-2 border rounded w-full" placeholder="End Year" min="1900" max="2100">

                <button id="applyPublicationChartYearRangeLecturer" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 w-full">Apply Year Range</button>
            </div>
        </div>
        <div id="containerPublicationChartLecturer" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6"></div>
    </div>

    @if (auth()->user()->role_id == 3)
        <!-- Card untuk Highcharts Citation Chart -->
        <div class="card bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-9xl p-4">
            <div class="mb-4">
                <label for="publicationChartSourceFilter" class="font-bold">Filter by Source:</label>
                <select id="publicationChartSourceFilter" class="p-2 border rounded">
                    <option value="all">All Sources</option>
                    <option value="Scopus">Scopus</option>
                    <option value="Google Scholar">Google Scholar</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="publicationChartLecturerFilter" class="font-bold">Filter by Lecturer:</label>
                <select id="publicationChartLecturerFilter" class="p-2 border rounded">
                    <option value="all">All Lecturer</option>
                    @foreach ($publications->pluck('user.name')->unique() as $name)
                        <option value="{{ $name }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Year Range Filter for Citations -->
            <div class="mb-4">
                <label for="startPublicationChartYear" class="font-bold">Start Year:</label>
                <input type="number" id="startPublicationChartYear" class="p-2 border rounded" placeholder="Start Year" min="1900" max="2100">
                <label for="endPublicationChartYear" class="font-bold">End Year:</label>
                <input type="number" id="endPublicationChartYear" class="p-2 border rounded" placeholder="End Year" min="1900" max="2100">
                <button id="applyPublicationChartYearRange" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">
                    Apply Year Range
                </button>
            </div>

            <div id="publicationChartContainer" class="mt-4"></div>
        </div>
    @endif
</section>



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
</script>


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
