<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Add User</h2>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="submit">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" class="form-control" wire:model="username">
                    @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" class="form-control" wire:model="email">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" class="form-control" wire:model="name">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" class="form-control" wire:model="password">
                    @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" class="form-control" wire:model="status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Role</label>
                    <select id="role_id" class="form-control" wire:model="role_id" onchange="toggleFields()">
                        <option value="1">Guest</option>
                        <option value="2">Dosen</option>
                        <option value="3">Admin Prodi</option>
                        <option value="4">Admin Fakultas</option>
                        <option value="5">Admin Universitas</option>
                    </select>
                    @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Faculty Dropdown (Visible for Dosen, Admin Prodi, Admin Fakultas, and Admin Universitas) -->
                <div class="mb-3" id="facultyField" style="display:none;">
                    <label for="faculty_id" class="form-label">Faculty</label>
                    <select id="faculty_id" class="form-control" wire:model="faculty_id">
                        <option value="">Select Faculty</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                    @error('faculty_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Program Dropdown (Visible for Dosen and Admin Prodi) -->
                <div class="mb-3" id="programField" style="display:none;">
                    <label for="program_id" class="form-label">Program</label>
                    <select id="program_id" class="form-control" wire:model="program_id">
                        <option value="">Select Program</option>
                        @foreach($studyPrograms as $program)
                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                        @endforeach
                    </select>
                    @error('program_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Scholar Field (Visible for Dosen) -->
                <div class="mb-3" id="scholarField" style="display:none;">
                    <label for="scholar" class="form-label">Scholar</label>
                    <input type="text" class="form-control" id="scholar" wire:model="scholar">
                </div>

                <!-- Scopus Field (Visible for Dosen) -->
                <div class="mb-3" id="scopusField" style="display:none;">
                    <label for="scopus" class="form-label">Scopus</label>
                    <input type="text" class="form-control" id="scopus" wire:model="scopus">
                </div>

                <!-- Revision Field (Visible for Guest) -->
                <div class="mb-3" id="revisionField" style="display:none;">
                    <label for="revision" class="form-label">Revision</label>
                    <input type="text" class="form-control" id="revision" wire:model="revision">
                </div>

                <button type="submit" class="btn btn-primary">Add User</button>
            </form>
        </div>
    </div>
</div>

<script>
    // JavaScript function to toggle visibility of fields based on selected role
    function toggleFields() {
        var role = document.getElementById('role_id').value;

        // Hide all fields initially
        document.getElementById('facultyField').style.display = 'none';
        document.getElementById('programField').style.display = 'none';
        document.getElementById('scholarField').style.display = 'none';
        document.getElementById('scopusField').style.display = 'none';
        document.getElementById('revisionField').style.display = 'none';

        // Show fields based on the selected role
        if (role == '2') { // Dosen
            document.getElementById('facultyField').style.display = 'block';
            document.getElementById('programField').style.display = 'block';
            document.getElementById('scholarField').style.display = 'block';
            document.getElementById('scopusField').style.display = 'block';
        } else if (role == '3') { // Admin Prodi
            document.getElementById('facultyField').style.display = 'block';
            document.getElementById('programField').style.display = 'block';
        } else if (role == '4') { // Admin Fakultas
            document.getElementById('facultyField').style.display = 'block';
        } else if (role == '1') { // Guest
            document.getElementById('revisionField').style.display = 'block';
        }
    }
</script>
