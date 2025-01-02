<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h2>Study Program List</h2>
                </div>
                <div class="col">
                    <!-- Link ke halaman tambah Study Program -->
                    <a href="{{ route('study-program.add') }}" class="btn btn-primary btn-sm float-end">Add Study Program</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Tampilkan pesan sukses atau error -->
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

            <!-- Tabel untuk daftar Study Program -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Faculty</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($study_programs as $study_program)
                        <tr>
                            <td>{{ $study_program->name }}</td>
                            <td>{{ $study_program->faculty->name ?? 'N/A' }}</td>
                            <td>
                                <!-- Tombol Edit -->
                                <a href="{{ route('study-program.edit', ['id' => $study_program->id]) }}" class="btn btn-warning btn-sm">Edit</a>

                                <!-- Tombol Hapus -->
                                <button class="btn btn-danger btn-sm" wire:click="delete({{ $study_program->id }})">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No study programs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
