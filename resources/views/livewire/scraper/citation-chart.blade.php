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
    <div id="containerCitationChartLecturer" class="mt-4"></div>
</div>

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
