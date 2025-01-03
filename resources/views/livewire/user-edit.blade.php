<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Edit User</h2>
        </div>
        <div class="card-body">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            @if(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="editProfile">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" wire:model="username" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" wire:model="email" required>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" wire:model="name" required>
                </div>

                <div class="mb-3">
                    <label for="selectedRole" class="form-label">Role</label>
                    <select id="selectedRole" class="form-control" wire:model="selectedRole" onchange="toggleFields()">
                        <option value="1">Guest</option>
                        <option value="2" {{ Auth::user()->role_id == 3 || Auth::user()->role_id == 4 || Auth::user()->role_id == 5 ? '' : 'disabled' }}>Dosen</option>
                        <option value="3" {{ Auth::user()->role_id == 4 || Auth::user()->role_id == 5 ? '' : 'disabled' }}>Admin Program Studi</option>
                        <option value="4" {{ Auth::user()->role_id == 5 ? '' : 'disabled' }}>Admin Fakultas</option>
                    </select>
                </div>

                <!-- Faculty Dropdown (Visible only for Dosen, Admin Prodi, Admin Fakultas) -->
                <div class="mb-3" id="faculty-field" style="display: {{ $showFaculty ? 'block' : 'none' }};">
                    <label for="selectedFaculty" class="form-label">Faculty</label>
                    <select id="selectedFaculty" class="form-control" wire:model="selectedFaculty">
                        <option>Select Faculty</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Program Dropdown (Visible only for Dosen and Admin Prodi) -->
                <div class="mb-3" id="program-field" style="display: {{ $showProgram ? 'block' : 'none' }};">
                    <label for="selectedProgram" class="form-label">Study Program</label>
                    <select id="selectedProgram" class="form-control" wire:model="selectedProgram">
                        <option>Select Program</option>
                        @foreach($studyPrograms as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Scholar (Visible only for Dosen) -->
                <div class="mb-3" id="scholar-field" style="display: {{ $showScholar ? 'block' : 'none' }};">
                    <label for="scholar" class="form-label">Scholar</label>
                    <input type="text" class="form-control" id="scholar" wire:model="scholar">
                </div>

                <!-- Scopus (Visible only for Dosen) -->
                <div class="mb-3" id="scopus-field" style="display: {{ $showScopus ? 'block' : 'none' }};">
                    <label for="scopus" class="form-label">Scopus</label>
                    <input type="text" class="form-control" id="scopus" wire:model="scopus">
                </div>

                <button type="submit" class="btn btn-primary">Update User</button>
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
