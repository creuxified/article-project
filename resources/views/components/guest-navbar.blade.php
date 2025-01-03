<nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <!-- Logo and Text Section -->
      <div class="flex items-center space-x-3 rtl:space-x-reverse">
        <!-- Logo -->
        <img class="w-12 h-12" src="{{ asset('images/logo_UNS.png') }}" alt="UNS Logo">

        <!-- Text -->
        <div class="flex flex-col">
          <span class="text-3xl font-bold text-gray-900 dark:text-white">UNS</span>
          <span class="text-sm font-medium text-gray-400">Citation Management</span>
        </div>
      </div>

      <!-- Buttons Section -->
      <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse w-1/4 justify-start">
        <button disabled type="button" class="text-gray-900 bg-gradient-to-r from-teal-200 to-lime-200 hover:bg-gradient-to-l hover:from-teal-200 hover:to-lime-200 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-teal-700 font-medium rounded-lg text-sm px-4 py-2 text-center me-2 w-40 flex items-center justify-center">
          <svg class="w-5 h-5 text-gray-800 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
          </svg>
          Guest Account
        </button>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
            Log out
          </button>
        </form>
      </div>
    </div>
  </nav>
