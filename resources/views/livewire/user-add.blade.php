<div class="antialiased flex items-center justify-center px-4">
    <div class="max-w-3xl w-full bg-white dark:bg-gray-900 shadow-lg rounded-lg">
        <!-- Header -->
        <div class="bg-gray-100 dark:bg-gray-800 text-white p-4 rounded-t-lg flex justify-between items-center">
            <h2 class="text-xl font-semibold">
                <i class="fas fa-users mr-2"></i> Add User
            </h2>
            <a href="{{ route('users-add') }}"
                class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                <i class="fas fa-list mr-2"></i> User List
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-900 p-4 rounded-lg">
            <form wire:submit.prevent="submit" class="space-y-4">
                <!-- Username -->
                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">
                        <i class="fas fa-user mr-2"></i> Username
                    </label>
                    <input type="text" id="username" wire:model="username"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Enter username">
                    @error('username')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">
                        <i class="fas fa-envelope mr-2"></i> Email
                    </label>
                    <input type="email" id="email" wire:model="email"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Enter email">
                    @error('email')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">
                        <i class="fas fa-id-card mr-2"></i> Name
                    </label>
                    <input type="text" id="name" wire:model="name"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Enter name">
                    @error('name')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">
                        <i class="fas fa-lock mr-2"></i> Password
                    </label>
                    <input type="password" id="password" wire:model="password"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Enter password">
                    @error('password')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">
                        <i class="fas fa-users-cog mr-2"></i> Role
                    </label>
                    <select id="role_id" wire:model="role_id"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="1">Guest</option>
                        <option value="2">Dosen</option>
                        @if (Auth::user()->role_id >= 4)
                            <option value="3">Admin Prodi</option>
                        @endif
                        @if (Auth::user()->role_id == 5)
                            <option value="4">Admin Fakultas</option>
                        @endif
                    </select>
                    @error('role_id')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Additional Fields -->
                <div id="facultyField" style="display:none;">
                    <label for="faculty_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">
                        <i class="fas fa-building mr-2"></i> Faculty
                    </label>
                    <select id="faculty_id" wire:model="faculty_id"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Select Faculty</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                    @error('faculty_id')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                    <i class="fas fa-save mr-2"></i> Add User
                </button>
            </form>
        </div>
    </div>
</div>
