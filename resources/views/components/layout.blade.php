<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalToggleButton = document.querySelector('[data-modal-toggle="crud-modal"]');
            const modal = document.getElementById('crud-modal');

            // Toggle modal visibility
            modalToggleButton?.addEventListener('click', () => {
                modal.classList.toggle('hidden');
            });

            // Close modal when clicking the close button
            const closeButton = modal.querySelector('[data-modal-toggle="crud-modal"]');
            closeButton?.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });

        document.addEventListener('livewire:load', function () {
            Livewire.on('alert', (data) => {
                Swal.fire({
                    icon: data.type, // 'success', 'error', 'warning', etc.
                    title: 'Notification',
                    text: data.message,
                    confirmButtonText: 'OK',
                });
            });
        });
    </script>
    <script>
        // Toggle visibility of the dropdown menu
        document.addEventListener('DOMContentLoaded', () => {
            const dropdownButton = document.getElementById('profileDropdownButton');
            const dropdownMenu = document.getElementById('profileDropdownMenu');

            dropdownButton.addEventListener('click', () => {
                dropdownMenu.classList.toggle('hidden');
            });

            // Optional: Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        });
    </script>
    @vite('resources/css/app.css')
    @livewireStyles()
</head>

<body class="bg-gray-100 dark:bg-gray-900">
    <div class="flex min-h-screen">
        <!-- Include Sidebar -->
        @include('components.user-navbar')

        <!-- Main Content -->
        <main class="flex-1 ml-64">
            <header class="bg-white shadow dark:bg-gray-800 p-4">
                <div class="flex justify-between fa-align-right p-4 rounded-md">
                    <!-- Profile Dropdown -->
                    <div class="relative mr-4">
                        <button id="profileDropdownButton" type="button" class="flex items-center bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl text-white font-medium rounded-lg text-sm px-5 py-2.5 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800">
                            <svg class="w-5 h-5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M12 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4ZM4 21h16v-1a7 7 0 0 0-14 0v1Z" />
                            </svg>
                            Profile
                            <svg class="w-4 h-4 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Content -->
                        <div id="profileDropdownMenu" class="hidden absolute right-0 mt-2 w-56 rounded-lg shadow-lg">
                            <!-- Role Information -->
                            <div class="p-4 text-sm text-gray-700 dark:text-gray-300">
                                @if(Auth::user()->role_id == 5)
                                    <button disabled type="button" class="w-full text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2 flex items-center justify-start">
                                        <svg class="w-5 h-5 text-gray-800 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                        University Admin
                                    </button>
                                @endif
                                @if(Auth::user()->role_id == 4)
                                    <button disabled type="button" class="w-full text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center flex items-center justify-start">
                                        <svg class="w-5 h-5 text-gray-800 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                        Faculty Admin
                                    </button>
                                @endif
                                @if(Auth::user()->role_id == 3)
                                    <button disabled type="button" class="w-full text-white bg-gradient-to-r from-purple-500 to-pink-500 hover:bg-gradient-to-l focus:ring-4 focus:outline-none focus:ring-purple-200 dark:focus:ring-purple-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2 flex items-center justify-start">
                                        <svg class="w-5 h-5 text-gray-800 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                        Program Admin
                                    </button>
                                @endif
                                @if(Auth::user()->role_id == 2)
                                    <button disabled type="button" class="w-full text-white bg-gradient-to-br from-pink-500 to-orange-400 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-pink-200 dark:focus:ring-pink-800 font-medium rounded-lg text-sm px-4 py-2 text-center mb-2 flex items-center justify-start">
                                        <svg class="w-5 h-5 text-gray-800 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                        Lecturer
                                    </button>
                                @endif
                            </div>
                            <hr class="border-gray-200">
                            <!-- Logout Button -->
                            <form action="{{ route('logout') }}" method="POST" class="p-2">
                                @csrf
                                <button type="submit" class="w-full text-center bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                                    Log Out
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            </header>
            <div class="p-6">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts()
</body>

</html>
