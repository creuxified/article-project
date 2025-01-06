<section
    class="bg-gradient-to-br from-blue-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 antialiased min-h-screen flex flex-col justify-between">
    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        </script>
    @endif

    <h4 class="font-bold">ID Prodi {{ $userProgramId }}</h4>

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


    @livewire('scraper.publication-chart')
    @livewire('scraper.citation-chart')

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
