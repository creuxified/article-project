<div class="flex h-screen bg-gray-900">
    <!-- Sidebar -->
    <div class="bg-gray-800 w-64 hidden md:block shadow-lg fixed inset-y-0 left-0 z-10">
        <div class="flex flex-col h-full">
            <!-- Logo Section -->
            <div class="p-6 flex items-center space-x-3 rtl:space-x-reverse">
                <img class="w-12 h-12" src="{{ asset('images/logo_UNS.png') }}" alt="logo">
                <div>
                    <span class="text-3xl font-bold whitespace-nowrap text-white">UNS</span>
                    <span class="text-sm font-medium whitespace-nowrap text-gray-400 ">Citation Management</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 mt-6">
                <ul class="space-y-2">
                    <li>
                        <a href="/dashboard/{{ Auth::user()->username }}"
                            class="block px-6 py-2 text-gray-600 dark:text-gray-300 hover:bg-blue-100 hover:dark:bg-gray-700 rounded {{ request()->is('dashboard/*') ? 'bg-blue-200 dark:bg-gray-700' : '' }}">
                            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="/user-profile-edit/{{ Auth::user()->username }}"
                            class="block px-6 py-2 text-gray-600 dark:text-gray-300 hover:bg-blue-100 hover:dark:bg-gray-700 rounded {{ request()->is('user-profile-edit/*') ? 'bg-blue-200 dark:bg-gray-700' : '' }}">
                            <i class="fas fa-user-edit mr-3"></i> Edit Profile
                        </a>
                    </li>
                    <li>
                        <a href="/scraper"
                            class="block px-6 py-2 text-gray-600 dark:text-gray-300 hover:bg-blue-100 hover:dark:bg-gray-700 rounded {{ request()->is('scrap-data/*') ? 'bg-blue-200 dark:bg-gray-700' : '' }}">
                            <i class="fas fa-cogs mr-3"></i> Scrap Data
                        </a>
                    </li>

                    @if (Auth::user()->role_id != 2)
                        <li>
                            <a href="/request-role/{{ Auth::user()->username }}"
                                class="block px-6 py-2 text-gray-600 dark:text-gray-300 hover:bg-blue-100 hover:dark:bg-gray-700 rounded {{ request()->is('request-role/*') ? 'bg-blue-200 dark:bg-gray-700' : '' }}">
                                <i class="fas fa-hand-paper mr-3"></i> Request
                            </a>
                        </li>
                        <li>
                            <a href="/user-database/{{ Auth::user()->username }}"
                                class="block px-6 py-2 text-gray-600 dark:text-gray-300 hover:bg-blue-100 hover:dark:bg-gray-700 rounded {{ request()->is('user-database/*') ? 'bg-blue-200 dark:bg-gray-700' : '' }}">
                                <i class="fas fa-users mr-3"></i> User Database
                            </a>
                        </li>

                    @endif
                    @if (Auth::user()->role_id == 5)
                        <li>
                            <a href="/faculty"
                                class="block px-6 py-2 text-gray-600 dark:text-gray-300 hover:bg-blue-100 hover:dark:bg-gray-700 rounded {{ request()->is('faculty') ? 'bg-blue-200 dark:bg-gray-700' : '' }}">
                                <i class="fas fa-chalkboard-teacher mr-3"></i> Faculty Management
                            </a>
                        </li>
                        <li>
                            <a href="/programs"
                                class="block px-6 py-2 text-gray-600 dark:text-gray-300 hover:bg-blue-100 hover:dark:bg-gray-700 rounded {{ request()->is('programs') ? 'bg-blue-200 dark:bg-gray-700' : '' }}">
                                <i class="fas fa-clipboard-list mr-3"></i> Programs Management
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1">
        <!-- Responsive Toggle Button -->
        <div class="bg-gray-100 dark:bg-gray-800 p-4 md:hidden flex justify-between items-center">
            <button id="sidebarToggle" class="text-gray-600 dark:text-gray-300 hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <span class="text-xl font-bold text-gray-900 dark:text-white">UNS Citation Management</span>
        </div>
    </div>
</div>

<script>
    // Sidebar Toggle Script
    document.getElementById('sidebarToggle').addEventListener('click', () => {
        const sidebar = document.querySelector('.md\\:block');
        sidebar.classList.toggle('hidden');
    });
</script>

<!-- FontAwesome CDN -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
