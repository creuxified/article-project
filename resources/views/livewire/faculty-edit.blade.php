<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h2>Laravel 11.x + Livewire 3.x CRUD</h2>
                </div>
                <div class="col">
                    <a href="/faculty" wire:navigate class="btn btn-primary btn-sm float-end">Faculty List</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="update">
                <div class="mb-3">
                    <label for="name" class="form-label">Faculty Name</label>
                    <input type="text" wire:model="name" class="form-control" id="name" name="name"
                        placeholder="Enter faculty name">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>
</div>
