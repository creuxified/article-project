<section class="bg-gradient-to-br from-blue-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 antialiased min-h-screen flex items-center justify-center">
    <div class="max-w-3xl w-full bg-white dark:bg-gray-900 shadow-lg rounded-lg p-8">
        @if (session()->has('message'))
        <div class="flex p-4 mb-4 text-sm text-green-800 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
            role="alert">
            <svg aria-hidden="true" class="w-5 h-5 inline mr-3" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V7h2v2z"></path>
            </svg>
            <div class="flex-1">{{ session('message') }}</div>
            <button type="button"
                class="ml-2 inline-flex items-center justify-center w-4 h-4 text-green-500 hover:bg-green-200 rounded-full focus:outline-none"
                wire:click="$set('message', null)" aria-label="Close">
                <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Role Request</h2>
            <button type="button" disabled
                class="px-3 py-2 text-xs font-medium text-center text-gray-800
                @if($user->status == 2)
                bg-green-500 text-white
                @elseif($user->status == 3)
                bg-dim.red text-white
                @else
                bg-gray-300 cursor-not-allowed opacity-50
                @endif rounded-lg">

                @if($user->status == 2)
                Requested
                @elseif($user->status == 3)
                Rejected
                @else
                Not Requested
                @endif
            </button>
        </div>

        <form wire:submit.prevent="sendRequest">
            <div class="grid gap-6 mb-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300"><i class="fas fa-user"></i></span>
                        <input disabled type="name" id="name"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg pl-10 focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Full Name" wire:model="name">
                    </div>
                </div>

                <!-- Faculty and Email -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="faculty" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Faculty</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300"><i class="fas fa-building"></i></span>
                            <input disabled type="text" id="faculty"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg pl-10 focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Faculty" wire:model="faculty">
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300"><i class="fas fa-envelope"></i></span>
                            <input disabled type="text" id="email"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg pl-10 focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Email" wire:model="email">
                        </div>
                    </div>
                </div>

                <!-- Study Program and Role Request -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="selectedProgram"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Study Program</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300"><i class="fas fa-graduation-cap"></i></span>
                            <select id="selectedProgram" wire:model="selectedProgram"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg pl-10 focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="0">Choose your Study Program</option>
                                @foreach ($studyPrograms as $program)
                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedProgram')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div>
                        <label for="selectedRole" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role Request</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300"><i class="fas fa-user-tag"></i></span>
                            <select id="selectedRole" wire:model="selectedRole"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg pl-10 focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected>Choose role request</option>
                                @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedRole')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <!-- Scopus ID and Scholar ID -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="scopus" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Scopus ID</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300"><i class="fas fa-id-card"></i></span>
                            <input type="text" id="scopus" wire:model="scopus"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg pl-10 focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Scopus ID">
                            @error('scopus')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div>
                        <label for="scholar" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Scholar ID</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300"><i class="fas fa-id-badge"></i></span>
                            <input type="text" id="scholar" wire:model="scholar"
                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg pl-10 focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Scholar ID">
                            @error('scholar')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">Send Request</button>
                <button type="button" class="px-5 py-2 text-sm font-medium text-red-600 border border-red-600 rounded-lg hover:bg-red-600 hover:text-white focus:outline-none focus:ring-4 focus:ring-red-300">Delete Account</button>
            </div>
        </form>
    </div>
</section>
