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

            <form wire:submit.prevent="update">
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
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" wire:model="password">
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" wire:model="status" required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Role</label>
                    <select id="role_id" class="form-control" wire:model="role_id" onchange="toggleFields()">
                        <option value="1">Guest</option>
                        <option value="2">Dosen</option>
                        <option value="3">Admin Program Studi</option>
                        <option value="4">Admin Fakultas</option>
                        <option value="5">Admin Universitas</option>
                    </select>
                </div>

                <!-- Faculty Dropdown (Visible only for Dosen, Admin Prodi, Admin Fakultas) -->
                <div class="mb-3" id="faculty-field" style="display: none;">
                    <label for="faculty_id" class="form-label">Faculty ID</label>
                    <select id="faculty_id" class="form-control" wire:model="faculty_id">
                        <option value="">Select Faculty</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Program Dropdown (Visible only for Dosen and Admin Prodi) -->
                <div class="mb-3" id="program-field" style="display: none;">
                    <label for="program_id" class="form-label">Program ID</label>
                    <select id="program_id" class="form-control" wire:model="program_id">
                        <option value="">Select Program</option>
                        @foreach($studyPrograms as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Scholar (Visible only for Dosen) -->
                <div class="mb-3" id="scholar-field" style="display: none;">
                    <label for="scholar" class="form-label">Scholar</label>
                    <input type="text" class="form-control" id="scholar" wire:model="scholar">
                </div>

                <!-- Scopus (Visible only for Dosen) -->
                <div class="mb-3" id="scopus-field" style="display: none;">
                    <label for="scopus" class="form-label">Scopus</label>
                    <input type="text" class="form-control" id="scopus" wire:model="scopus">
                </div>

                <!-- Revision (Visible for all roles) -->
                <div class="mb-3">
                    <label for="revision" class="form-label">Revision</label>
                    <textarea class="form-control" id="revision" wire:model="revision"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update User</button>
            </form>
        </div>
    </div>
</div>

<script>
    // JavaScript function to show/hide fields based on role selection
    function toggleFields() {
        const roleId = document.getElementById('role_id').value;

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
</script>
