<section class="bg-white dark:bg-gray-900">
    <div class="max-w-2xl px-4 py-8 mx-auto lg:py-16">
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
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Edit Profile</h2>
        </div>
        <form wire:submit.prevent="editProfile">
            <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                    <input type="name" name="name" id="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Full Name" wire:model='name'>
                        <div class="text-red-600 text-sm font-medium">
                            @error('name')
                            {{ $message }}
                            @enderror
                        </div>
                </div>
                <div class="w-full">
                    <label for="username"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                    <input type="username" name="username" id="username"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="username" wire:model='username'>
                    </input>
                    <div class="text-red-600 text-sm font-medium">
                        @error('username')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="w-full">
                    <label for="email"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="text" name="email" id="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Email" wire:model='email'>
                    </input>
                    <div class="text-red-600 text-sm font-medium">
                        @error('email')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="w-full">
                    <label for="faculty"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Faculty</label>
                        <select id="faculty"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            wire:model="selectedFaculty">
                            <option value={{ Auth::user()->faculty_id}} selected>{{ Auth::user()->faculty->name }}</option>
                            @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                        <div class="text-red-600 text-sm font-medium">
                            @error('selectedFaculty')
                            {{ $message }}
                            @enderror
                        </div>
                </div>
                <div>
                    <label for="selectedProgram"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Study Program</label>
                    <select @if ($user->status == 2) disabled @endif id="selectedProgram" class="bg-gray-50 borderborder-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        wire:model='selectedProgram'>
                        @if (Auth::user()->program->faculty_id != Auth::user()->faculty_id)
                            <option selected>Select Study Program</option>
                        @else
                        <option value={{ Auth::user()->program_id}} selected>{{ Auth::user()->program->name }}</option>

                        @endif
                        @foreach ($studyPrograms as $program)
                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                    <div class="text-red-600 text-sm font-medium">
                        @error('selectedProgram')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                @if(Auth::user()->role_id == 2)
                <div class="w-full">
                    <label for="scopus" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Scopus ID</label>
                    <input type="text" name="scopus" id="scopus" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-6 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Scopus ID" wire:model='scopus'>
                    </input>
                    <div class="text-red-600 text-sm font-medium">
                        @error('scopus')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="w-full">
                    <label for="scholar" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Scholar ID</label>
                    <input type="text" name="scholar" id="scholar"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="{{ $user->scholar }}" wire:model='scholar'>
                    </input>
                    <div class="text-red-600 text-sm font-medium">
                        @error('scholar')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                @endif
            </div>
            <div class="flex items-center space-x-4">
                <button @if ($user->status == 2) disabled @endif type="submit" class="text-white bg-primary-700
                    {{ $user->status == 2 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-primary-800' }} focus:ring-4
                    focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
                    dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    Update Profile
                </button>
                <button @if ($user->status == 2) disabled @endif type="button" class="text-red-600 inline-flex
                    items-center {{ $user->status == 2 ? 'opacity-50 cursor-not-allowed' : ' hover:text-white border
                    border-red-600 hover:bg-red-600' }} focus:ring-4 focus:outline-none focus:ring-red-300
                    font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500
                    dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                    <svg class="w-5 h-5 mr-1 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Delete Account
                </button>
            </div>
        </form>
    </div>
</section>
