<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

<body class= "bg-gray-900">
    <div class="flex min-h-screen">
        <!-- Include Sidebar -->
        @include('components.user-navbar')

        <!-- Main Content -->
        <main class="flex-1 ml-64">
            <header class="bg-gray-800 p-4">
                <div class="flex justify-between fa-align-right p-4 rounded-md">
                    <!-- Profile Dropdown -->
                    <div class="relative mr-4">
                        <button id="profileDropdownButton" type="button" class="flex items-center bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl text-white font-medium rounded-lg text-sm px-5 py-2.5 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-800">
                            <svg class="w-5 h-5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M12 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4ZM4 21h16v-1a7 7 0 0 0-14 0v1Z" />
                            </svg>
                            @if(Auth::user()->role_id == 5) University Admin @endif
                            @if(Auth::user()->role_id == 4) Faculty Admin @endif
                            @if(Auth::user()->role_id == 3) Program Admin @endif
                            @if(Auth::user()->role_id == 2) Lecturer @endif
                            <svg class="w-4 h-4 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Content -->
                        <div id="profileDropdownMenu" class="hidden absolute right-0 mt-2 w-56 rounded-lg shadow-lg z-50">
                        <!-- Edit Profile Button -->
                        <ul class="p-2">
                            <li>
                                <a href="/user-profile-edit/{{ Auth::user()->username }}" class="bg-gray-700 block px-6 py-2 text-gray-600 dark:text-gray-300 hover:bg-blue-100 hover:dark:bg-gray-700 rounded {{ request()->is('user-profile-edit/*') ? 'bg-blue-200 dark:bg-gray-700' : '' }} text-center">
                                    <i class="fas fa-user-edit mr-3"></i> Edit Profile
                                </a>
                            </li>
                        </ul>

                        <!-- Logout Button -->
                        <form action="{{ route('logout') }}" method="POST" class="p-2">
                            @csrf
                            <button type="submit" class="w-full text-center bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                                <i class="fas fa-sign-out-alt mr-2"></i> Log Out
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
