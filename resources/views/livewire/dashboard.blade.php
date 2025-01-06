<section
    class="bg-gradient-to-br from-blue-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 antialiased min-h-screen flex flex-col justify-between">
    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    ul
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        </script>
    @endif

    <h4 class="font-bold">ID Prodi {{ $userProgramId }}</h4>

    @if (auth()->user()->role_id == 2)
        <div class="grid grid-cols-2 w-full gap-3">
            <div class="bg-blue-100 p-4 rounded shadow text-center ">
                <h4 class="font-bold">Jumlah Publikasi</h4>
                <p id="" class="text-4xl font-semibold">{{ $totalPublicationUsers }}</p>
            </div>
            <div class="bg-blue-100 p-4 rounded shadow text-center">
                <h4 class="font-bold">Jumlah Sitasi</h4>
                <p id="" class="text-4xl font-semibold">{{ $totalCitationUsers }}</p>
            </div>
        </div>
    @endif

    @if (auth()->user()->role_id == 3)
        <div class="grid grid-cols-3 w-full gap-3">
            <div class="bg-blue-100 p-4 rounded shadow text-center">
                <h4 class="font-bold">Total Dosen</h4>
                <p id="" class="text-4xl font-semibold">{{ $userCount }}</p>
            </div>
            <div class="bg-blue-100 p-4 rounded shadow text-center">
                <h4 class="font-bold">Jumlah Publikasi</h4>
                <p id="" class="text-4xl font-semibold">{{ $totalPublicationUsers }}</p>
            </div>
            <div class="bg-blue-100 p-4 rounded shadow text-center">
                <h4 class="font-bold">Jumlah Sitasi</h4>
                <p id="" class="text-4xl font-semibold">{{ $totalCitationUsers }}</p>
            </div>
        </div>
    @endif

    @if (auth()->user()->role_id == 4)
        <div class="grid grid-cols-4 w-full gap-3">
            <div class="bg-blue-100 p-4 rounded shadow text-center">
                <h4 class="font-bold">Total Lecturer</h4>
                <p id="" class="text-4xl font-semibold">{{ $totalLecturer }}</p>
            </div>
            <div class="bg-blue-100 p-4 rounded shadow text-center">
                <h4 class="font-bold">Total Study Program</h4>
                <p id="" class="text-4xl font-semibold">{{ $totalStudyProgram }}</p>
            </div>
            <div class="bg-blue-100 p-4 rounded shadow text-center">
                <h4 class="font-bold">Jumlah Publikasi</h4>
                <p id="" class="text-4xl font-semibold">{{ $totalPublicationUsers }}</p>
            </div>
            <div class="bg-blue-100 p-4 rounded shadow text-center">
                <h4 class="font-bold">Jumlah Sitasi</h4>
                <p id="" class="text-4xl font-semibold">{{ $totalCitationUsers }}</p>
            </div>
        </div>
    @endif

    {{-- HIGHCHARTS ADMIN FAKULTAS --}}
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

        <div class="mb-4">
            <label for="publicationChartFaclutyFilter" class="font-bold">Filter by Faculty:</label>
            <select id="publicationChartFaclutyFilter" class="p-2 border rounded">
                <option value="all">All Faculty</option>
                @foreach ($faculties as $faculty)
                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="publicationChartStudyProgramFilter" class="font-bold">Filter by Study Program:</label>
            <select id="publicationChartStudyProgramFilter" class="p-2 border rounded">
                <option value="all">All Study Program</option>
                <!-- The options for study programs will be populated dynamically based on faculty selection -->
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
            <button id="applyPublicationChartYearRange" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">Apply Year
                Range</button>
        </div>

        <div id="publicationChartContainer" class="mt-4"></div>
    </div>



    <!-- Publications Table -->
    <div class="overflow-x-auto mt-8">
        <table id="publicationTable" class="table-auto w-full border-collapse border border-gray-300">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white">
                <tr>
                    <th class="px-6 py-3">#</th>
                    @if (auth()->user()->role_id == 4)
                        <th class="px-6 py-3">Lecturer</th>
                        <th class="px-6 py-3">Study Program</th>
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
                        <td class="px-6 py-4">{{ $publication->lecturer }}</td>
                        <td class="px-6 py-4">{{ $publication->study_program }}</td>
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



    {{-- @livewire('scraper.publication-chart') --}}
    {{-- @livewire('scraper.citation-chart') --}}

    <div class="max-w-screen-xl px-4 py-8 mx-auto lg:px-6 sm:py-16 lg:py-24 bg-primary-600">



    </div>

    <div class="max-w-screen-xl px-4 py-8 mx-auto lg:px-6 sm:py-16 lg:py-24">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-4xl font-extrabold leading-tight tracking-tight text-gray-900 sm:text-5xl dark:text-white">
                Welcome to <br>
                <span class="text-blue-600 dark:text-blue-400">UNS Citation Management</span>
            </h2>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                The best platform to help you manage academic references efficiently and easily.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-16">
            <div
                class="text-center p-6 bg-gray-800 shadow-md rounded-lg transition-transform transform hover:scale-105 hover:shadow-xl hover:bg-blue-50 dark:hover:bg-blue-900">
                <div
                    class="w-16 h-16 mx-auto flex items-center justify-center bg-blue-100 dark:bg-blue-700 rounded-full">
                    <i class="fa-solid fa-bars-progress fa-2xl" style="color: #ffffff;"></i>
                </div>
                <h3 class="mt-6 text-xl font-semibold text-white">Easy Management</h3>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your references quickly and easily using our
                    advanced tools.</p>
            </div>

            <div
                class="text-center p-6 bg-gray-800 shadow-md rounded-lg transition-transform transform hover:scale-105 hover:shadow-xl hover:bg-blue-50 dark:hover:bg-blue-900">
                <div
                    class="w-16 h-16 mx-auto flex items-center justify-center bg-blue-100 dark:bg-blue-700 rounded-full">
                    <i class="fa-solid fa-cloud fa-2xl" style="color: #ffffff;"></i>
                </div>
                <h3 class="mt-6 text-xl font-semibold text-white">Cloud Synchronization</h3>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Access your references from anywhere with
                    synchronization features.</p>
            </div>

            <div
                class="text-center p-6 bg-gray-800 shadow-md rounded-lg transition-transform transform hover:scale-105 hover:shadow-xl hover:bg-blue-50 dark:hover:bg-blue-900">
                <div
                    class="w-16 h-16 mx-auto flex items-center justify-center bg-blue-100 dark:bg-blue-700 rounded-full">
                    <i class="fa-solid fa-clock fa-2xl" style="color: #ffffff;"></i>
                </div>
                <h3 class="mt-6 text-xl font-semibold text-white">Time Management</h3>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Save time with the citation management automation
                    feature.</p>
            </div>
        </div>
    </div>



    <footer class="bg-gray-100 dark:bg-gray-800 py-6">
        <div class="max-w-screen-xl mx-auto text-center">
            <p class="text-gray-600 dark:text-gray-400">&copy; 2025 Manajemen Sitasi UNS. All rights reserved.</p>
        </div>
    </footer>


</section>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#publicationTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "pageLength": 5 // Default 10 rows per page
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

        var faculties = @json($faculties); // Data fakultas yang sudah ada
        var study_programs = @json($study_programs); // Data program studi yang sudah ada

        // Function to load study programs based on faculty selection
        function loadStudyPrograms(facultyId) {
            console.log('Selected Faculty ID: ' + facultyId);
            // Kosongkan select untuk program studi
            var studyProgramSelect = $('#publicationChartStudyProgramFilter');
            studyProgramSelect.empty();
            studyProgramSelect.append('<option value="all">All Study Program</option>');

            // Jika "All Faculty" dipilih, kembalikan ke kondisi default
            if (facultyId === 'all') {
                return; // Tidak melakukan perubahan apapun
            }

            // Cari program studi yang sesuai dengan faculty_id
            var filteredPrograms = study_programs.filter(function(program) {
                return program.faculty_id == facultyId; // Pastikan faculty_id benar
            });

            // Menambahkan program studi yang sesuai ke dalam select
            filteredPrograms.forEach(function(program) {
                studyProgramSelect.append('<option value="' + program.name + '">' + program.name + '</option>');
            });
        }

        var allPublicationChartData = @json($publications); // Semua data publikasi
        console.log(allPublicationChartData)

        // Function to update the Highchart
        function updatePublicationChart(filteredData) {
            var years = [...new Set(filteredData.map(item => {
                return item.publication_date ? new Date(item.publication_date).getFullYear() : 'Tidak Diketahui';
            }))].sort((a, b) => a === 'Tidak Diketahui' ? 1 : (b === 'Tidak Diketahui' ? -1 : a - b));

            // Group publications by year
            var publicationsPerYear = years.map(year => {
                return filteredData.filter(item => {
                    return item.publication_date && new Date(item.publication_date).getFullYear() === year;
                }).length;
            });

            Highcharts.chart('publicationChartContainer', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Publications per Year'
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
                series: [{
                    name: 'Publications',
                    data: publicationsPerYear
                }]
            });
        }

        // Apply filters based on selected options
        function applyPublicationChartFilters() {
            var selectedSource = $('#publicationChartSourceFilter').val();
            var selectedFaculty = $('#publicationChartFaclutyFilter').val();
            var selectedStudyProgram = $('#publicationChartStudyProgramFilter').val();
            var startYear = $('#startPublicationChartYear').val();
            var endYear = $('#endPublicationChartYear').val();

            var filteredData = allPublicationChartData;

            // Filter by source
            if (selectedSource !== 'all') {
                filteredData = filteredData.filter(item => item.source === selectedSource);
            }

            // Filter by faculty
            if (selectedFaculty !== 'all') {
                // filteredData = filteredData.filter(item => item.faculty === selectedFaculty);
                filteredData = filteredData.filter(item => item.faculty_id = selectedFaculty);
            }

            // Filter by study program
            if (selectedStudyProgram !== 'all') {
                filteredData = filteredData.filter(item => item.study_program === selectedStudyProgram);
            }

            // Filter by year range
            if (startYear && endYear && startYear <= endYear) {
                filteredData = filteredData.filter(item => {
                    var publicationYear = item.publication_date ? new Date(item.publication_date).getFullYear() : null;
                    return publicationYear >= startYear && publicationYear <= endYear;
                });
            } else if (startYear || endYear) {
                alert('Please provide a valid year range.');
                return;
            }

            updatePublicationChart(filteredData);
        }

        // Event listeners for filters
        $('#publicationChartSourceFilter').on('change', function() {
            applyPublicationChartFilters();
        });

        $('#publicationChartFaclutyFilter').on('change', function() {
            var facultyId = $(this).val(); // Ambil ID fakultas yang dipilih
            loadStudyPrograms(facultyId); // Memuat program studi berdasarkan fakultas yang dipilih
            applyPublicationChartFilters(); // Memperbarui chart dengan filter yang diterapkan
        });

        $('#publicationChartStudyProgramFilter').on('change', function() {
            applyPublicationChartFilters();
        });

        $('#applyPublicationChartYearRange').on('click', function() {
            applyPublicationChartFilters();
        });

        // Initial application of filters
        applyPublicationChartFilters();
    });
</script>
