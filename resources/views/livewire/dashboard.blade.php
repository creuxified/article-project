<section>
    <div class="max-w-screen-xl px-4 py-8 mx-auto lg:px-6 sm:py-16 lg:py-24">
        <!-- Welcome Card -->
        <div class="bg-gray-800 p-6 rounded-lg shadow mb-6">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-4xl font-extrabold leading-tight tracking-tight text-gray-900 sm:text-5xl dark:text-white">
                    Welcome to <br>
                    <span class="text-blue-600 dark:text-blue-400">UNS Citation Management</span>
                </h2>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                    The best platform to help you manage academic references efficiently and easily.
                </p>
            </div>
        </div>

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
        @if (auth()->user()->role_id != 2)
            <div class="bg-gray-800 p-6 rounded-lg shadow mb-6">
                <!-- Filter Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <!-- Filter by Source -->
                    <div class="bg-blue-100 p-4 rounded shadow">
                        <label for="publicationChartSourceFilter" class="font-bold text-gray-700">Filter by Source:</label>
                        <select id="publicationChartSourceFilter" class="p-2 border rounded w-full">
                            <option value="all">All Sources</option>
                            <option value="Scopus">Scopus</option>
                            <option value="Google Scholar">Google Scholar</option>
                        </select>
                    </div>

                    <!-- Filter by Faculty -->
                    <div class="bg-blue-100 p-4 rounded shadow">
                        <label for="publicationChartFaclutyFilter" class="font-bold text-gray-700">Filter by Faculty:</label>
                        <select id="publicationChartFaclutyFilter" class="p-2 border rounded w-full">
                            @if (auth()->user()->role_id == 4 || auth()->user()->role_id == 3)
                                @php
                                    // Ambil ID fakultas pengguna yang sedang login
                                    $facultyId = auth()->user()->faculty_id;
                                    $faculty = \App\Models\Faculty::find($facultyId); // Ganti dengan model yang sesuai
                                @endphp

                                @if ($faculty)
                                    <!-- Menampilkan fakultas yang sesuai dengan faculty_id user yang login -->
                                    <option value="{{ $faculty->id }}" selected>{{ $faculty->name }}</option>
                                @else
                                    <!-- Jika fakultas tidak ditemukan -->
                                    <option value="none">Faculty not found</option>
                                @endif
                            @else
                                <!-- Jika role bukan 4 (misalnya role_id == 5), tampilkan semua fakultas -->
                                <option value="all">All Faculty</option>
                                @foreach ($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Filter by Study Program -->
                    <div class="bg-blue-100 p-4 rounded shadow">
                        <label for="publicationChartStudyProgramFilter" class="font-bold text-gray-700">Filter by Study Program: </label>
                        <select id="publicationChartStudyProgramFilter" class="p-2 border rounded w-full">
                            @if (auth()->user()->role_id == 3)
                                @php
                                    // Ambil program_id pengguna yang sedang login
                                    $userProgramId = auth()->user()->program_id;
                                    $userStudyPrograms = \App\Models\study_program::where('id', $userProgramId)->get();
                                @endphp

                                @if ($userStudyPrograms->count() > 0)
                                    <!-- Menampilkan program studi sesuai dengan program_id pengguna -->
                                    <option value="all">All Study Programs</option>
                                    @foreach ($userStudyPrograms as $program)
                                        <option value="{{ $program->id }}" selected>{{ $program->name }}</option>
                                    @endforeach
                                @else
                                    <!-- Jika tidak ada program studi ditemukan untuk program_id pengguna -->
                                    <option value="none">No Study Program Found</option>
                                @endif

                            @elseif (auth()->user()->role_id == 4)
                                @php
                                    // Ambil faculty_id pengguna yang sedang login
                                    $facultyId = auth()->user()->faculty_id;
                                    $userStudyPrograms = \App\Models\study_program::where('faculty_id', $facultyId)->get();
                                @endphp

                                <!-- Menampilkan program studi sesuai fakultas pengguna dan masih bisa memilih -->
                                <option value="all">All Study Programs</option>
                                @foreach ($userStudyPrograms as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach

                            @elseif (auth()->user()->role_id == 5)
                                <!-- Untuk role_id == 5, tampilkan semua program studi dari semua fakultas -->
                                <option value="all">All Study Programs</option>
                                @foreach ($study_programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>


                    <!-- Year Range Filter -->
                    <div class="bg-blue-100 p-4 rounded shadow col-span-1 md:col-span-2 lg:col-span-3">
                        <label for="startPublicationChartYear" class="font-bold text-gray-700">Start Year:</label>
                        <input type="number" id="startPublicationChartYear" class="p-2 border rounded w-full" placeholder="Start Year" min="1900" max="2100">

                        <label for="endPublicationChartYear" class="font-bold text-gray-700 mt-2">End Year:</label>
                        <input type="number" id="endPublicationChartYear" class="p-2 border rounded w-full" placeholder="End Year" min="1900" max="2100">

                        <button id="applyPublicationChartYearRange" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 w-full">Apply Year Range</button>
                    </div>
                </div>

                <!-- Highcharts Container for Chart -->
                <div id="publicationChartContainer" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow mb-6">
                </div>
            </div>
        @endif

        <!-- Chart Cards -->

            @livewire('scraper.publication-chart')
        <div class="mt-6">
            @livewire('scraper.citation-chart')
        </div>

    {{-- <div class="max-w-screen-xl px-4 py-8 mx-auto lg:px-6 sm:py-16 lg:py-24 bg-primary-600"> --}}

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
        var userRoleId = @json(auth()->user()->role_id); // Mengambil role pengguna yang sedang login
        var userFacultyId = @json(auth()->user()->faculty_id); // Mengambil faculty_id pengguna yang login
        var userProgramId = @json(auth()->user()->program_id); // Mengambil program_id pengguna yang login

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
                studyProgramSelect.append('<option value="' + program.id + '">' + program.name + '</option>');
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
                filteredData = filteredData.filter(item => item.faculty_id == selectedFaculty);
            }

            // Filter by study program
            if (selectedStudyProgram !== 'all') {
                filteredData = filteredData.filter(item => item.study_program == selectedStudyProgram);
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

        // Initial population of the filters based on user role
        if (userRoleId == 3) {
            // Role 3: Filter by user’s own program
            $('#publicationChartStudyProgramFilter').val(userProgramId); // Set program_id for role 3
            applyPublicationChartFilters(); // Ensure the filter is applied
        } else if (userRoleId == 4) {
            // Role 4: Filter by user's faculty and program, but allow selection
            loadStudyPrograms(userFacultyId);
            $('#publicationChartStudyProgramFilter').val(userProgramId); // Set program_id for role 4
            applyPublicationChartFilters(); // Ensure the filter is applied
        } else if (userRoleId == 5) {
            // Role 5: Show all programs, user can select any program
            loadStudyPrograms('all'); // Allow to choose all faculty programs
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

        // Initial application of filters when page loads
        applyPublicationChartFilters();
    });
</script>

