<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h2>Laravel 11.x + Livewire 3.x CRUD</h2>
                </div>
                <div class="col">
                    <a href="{{ route('programs-index') }}" class="btn btn-primary btn-sm float-end">Study Program List</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Form untuk menambahkan Study Program -->
            <form wire:submit.prevent="saveStudyProgram">
                <div class="mb-3">
                    <label for="name" class="form-label">Study Program Name</label>
                    <input type="text" wire:model="name" class="form-control" id="name" name="name" placeholder="Enter study program name">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="selectedFaculty" class="form-label">Faculty</label>
                    <select wire:model="selectedFaculty" id="selectedFaculty" class="form-control">
                        <option selected>Select Faculty</option>
                        @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedFaculty') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-success">Save</button>
            </form>
        </div>
    </div>
</div>
