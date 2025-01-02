<nav class="bg-white border-gray-200 dark:bg-gray-900">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <a href="https://flowbite.com/" class="flex items-center rtl:space-x-reverse">
          <img class="w-12 h-12 mr-3" src="{{ asset('images/logo_UNS.png') }}" alt="logo">
          <div class="flex flex-col">
              <span class="text-3xl font-bold whitespace-nowrap dark:text-white">UNS</span>
              <span class="text-xl font-semibold whitespace-nowrap dark:text-white">Citation Management</span>
          </div>
      </a>

      <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
        <!-- Role buttons -->
        @if(Auth::user()->role_id == 5)
          <button disabled type="button" class="text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl ...">University Admin</button>
        @endif
        <!-- More role buttons here -->

        @csrf
        <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Log out</button>
        </form>
      </div>

      <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-cta">
        <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
          <li>
            <a href="/dashboard/{{ Auth::user()->username }}"
               class="block py-2 px-3 md:p-0 {{ Request::is('dashboard/*') ? 'text-blue-700 font-bold md:dark:text-blue-500' : 'text-gray-900 hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500' }}"
               aria-current="page">
              Dashboard
            </a>
          </li>
          @if(Auth::user()->role_id != 2)
            <li>
              <a href="/request-role/{{ Auth::user()->username }}"
                 class="block py-2 px-3 md:p-0 {{ Request::is('request-role/*') ? 'text-blue-700 font-bold md:dark:text-blue-500' : 'text-gray-900 hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500' }}">
                Request
              </a>
            </li>
            <li>
              <a href="/user-database/{{ Auth::user()->username }}"
                 class="block py-2 px-3 md:p-0 {{ Request::is('user-database/*') ? 'text-blue-700 font-bold md:dark:text-blue-500' : 'text-gray-900 hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500' }}">
                User Database
              </a>
            </li>
            <li>
              <a href="/scrap-data/{{ Auth::user()->username }}"
                 class="block py-2 px-3 md:p-0 {{ Request::is('scrap-data/*') ? 'text-blue-700 font-bold md:dark:text-blue-500' : 'text-gray-900 hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500' }}">
                Scrap Data
              </a>
            </li>
          @endif
          <li>
            <a href="/user-profile-edit/{{ Auth::user()->username }}"
               class="block py-2 px-3 md:p-0 {{ Request::is('user-profile-edit/*') ? 'text-blue-700 font-bold md:dark:text-blue-500' : 'text-gray-900 hover:text-blue-700 dark:text-white md:dark:hover:text-blue-500' }}">
              Edit Profile
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
