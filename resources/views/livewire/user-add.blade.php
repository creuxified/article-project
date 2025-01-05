<div class="antialiased flex items-center justify-center px-4">
    <div class="max-w-3xl w-full bg-white dark:bg-gray-900 shadow-lg rounded-lg">
        <!-- Header -->
        <div class="bg-gray-100 dark:bg-gray-800 text-white p-4 rounded-t-lg flex justify-between items-center">
            <h2 class="text-xl font-semibold">
                <i class="fas fa-users mr-2"></i> Add User
            </h2>
            <a href="/user-database/{{ Auth::user()->username }}"
                class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors duration-200">
                <i class="fas fa-list mr-2"></i> User List
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-900 p-4 rounded-lg">
            <form wire:submit.prevent="submit" class="space-y-4">
                <!-- Username -->
                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900 ">
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
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">
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
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">
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
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">
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
                    <label for="selectedRole" class="block mb-2 text-sm font-medium text-gray-900 ">
                        <i class="fas fa-users-cog mr-2"></i> Role
                    </label>
                    <select id="selectedRole" wire:model="selectedRole"
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
                <!-- Faculty Dropdown (Visible only for Dosen, Admin Prodi, Admin Fakultas) -->
                <div class="mb-3" id="faculty-field" style="display: {{ $showFaculty ? 'block' : 'none' }};">
                    <label for="selectedFaculty" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-600">
                        <i class="fas fa-school mr-2"></i> Faculty
                    </label>
                    <select id="selectedFaculty" class="form-control w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        wire:model="selectedFaculty">
                        <option>Select Faculty</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Program Dropdown (Visible only for Dosen and Admin Prodi) -->
                <div class="mb-3" id="program-field" style="display: {{ $showProgram ? 'block' : 'none' }};">
                    <label for="selectedProgram" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-600">
                        <i class="fas fa-book-open mr-2"></i> Study Program
                    </label>
                    <select id="selectedProgram" class="form-control w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        wire:model="selectedProgram">
                        <option>Select Program</option>
                        @foreach($studyPrograms as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Scholar (Visible only for Dosen) -->
                <div class="mb-3" id="scholar-field" style="display: {{ $showScholar ? 'block' : 'none' }};">
                    <label for="scholar" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-600">
                        <i class="fas fa-graduation-cap mr-2"></i> Scholar
                    </label>
                    <input type="text" wire:model="scholar" id="scholar"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

                <!-- Scopus (Visible only for Dosen) -->
                <div class="mb-3" id="scopus-field" style="display: {{ $showScopus ? 'block' : 'none' }};">
                    <label for="scopus" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-600">
                        <i class="fas fa-search mr-2"></i> Scopus
                    </label>
                    <input type="text" wire:model="scopus" id="scopus"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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

<script>
    // JavaScript function to show/hide fields based on role selection
    function toggleFields() {
        const roleId = document.getElementById('selectedRole').value;

        // Hide all fields by default
        document.getElementById('faculty-field').style.display = 'none';
        document.getElementById('program-field').style.display = 'none';
        document.getElementById('scholar-field').style.display = 'none';
        document.getElementById('scopus-field').style.display = 'none';

        // Show fields based on selected role
        if (roleId == 2) { // Dosen
            document.getElementById('faculty-field').style.display = 'block';
            document.getElementById('program-field').style.display = 'block';
            document.getElementById('scholar-field').style.display = 'block';
            document.getElementById('scopus-field').style.display = 'block';
        } else if (roleId == 3) { // Admin Prodi
            document.getElementById('faculty-field').style.display = 'block';
            document.getElementById('program-field').style.display = 'block';
        } else if (roleId == 4) { // Admin Fakultas
            document.getElementById('faculty-field').style.display = 'block';
        }
    }

    // Run the toggleFields function on page load to handle pre-selected roles
    document.addEventListener('DOMContentLoaded', function() {
        toggleFields(); // Check and display fields based on pre-selected role
    });

    Livewire.on('roleChanged', (roleId) => {
        document.getElementById('faculty-field').style.display =
            [2, 3, 4].includes(Number(roleId)) ? 'block' : 'none';
        document.getElementById('program-field').style.display =
            [2, 3].includes(Number(roleId)) ? 'block' : 'none';
        document.getElementById('scholar-field').style.display =
            (roleId == 2) ? 'block' : 'none';
        document.getElementById('scopus-field').style.display =
            (roleId == 2) ? 'block' : 'none';
    });

</script>
