 <section>
 <!-- Highcharts Citations Diagrams -->
    <div class="card bg-gray-800 shadow-lg rounded-lg overflow-hidden w-full max-w-9xl p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

            <!-- Filter by Source -->
            <div class="bg-blue-100 p-4 rounded shadow">
                <label for="filterSourceCitationChartLecturer" class="font-bold text-gray-700">Filter by Source:</label>
                <select id="filterSourceCitationChartLecturer" class="p-2 border rounded w-full">
                    <option value="all">All Sources</option>
                    <option value="Scopus">Scopus</option>
                    <option value="Google Scholar">Google Scholar</option>
                </select>
            </div>

            <!-- Year Range Filter -->
            <div class="bg-blue-100 p-4 rounded shadow col-span-1 md:col-span-2 lg:col-span-3">
                <label for="startYearCitationChartLecturer" class="font-bold text-gray-700">Start Year:</label>
                <input type="number" id="startYearCitationChartLecturer" class="p-2 border rounded w-full" placeholder="Start Year" min="1900" max="2100">

                <label for="endYearCitationChartLecturer" class="font-bold text-gray-700 mt-2">End Year:</label>
                <input type="number" id="endYearCitationChartLecturer" class="p-2 border rounded w-full" placeholder="End Year" min="1900" max="2100">

                <button id="applyCitationChartYearRangeLecturer" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 w-full">Apply Year Range</button>
            </div>
        </div>
        <div id="containerCitationChartLecturer" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6"></div>
    </div>
</section>

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
