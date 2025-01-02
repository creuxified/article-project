<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Edit Study Program</h2>
        </div>
        <div class="card-body">
            <!-- Form Update Study Program -->
            <form wire:submit.prevent="update">
                <!-- Input Nama Program Studi -->
                <div class="mb-3">
                    <label for="name" class="form-label">Study Program Name</label>
                    <input type="text" id="name" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Select Fakultas -->
                <div class="mb-3">
                    <label for="selectedFaculty" class="form-label">Faculty</label>
                    <select id="selectedFaculty" wire:model="selectedFaculty" class="form-control @error('selectedFaculty') is-invalid @enderror">
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedFaculty')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tombol Submit -->
                <div class="d-flex justify-content-end">
                    <a href="{{ route('programs-index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
