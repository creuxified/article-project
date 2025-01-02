<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h2>Study Program List</h2>
                </div>
                <div class="col">
                    <a href="{{ route('study-program.add') }}" class="btn btn-primary btn-sm float-end">Add Study Program</a>
                </div>
            </div>
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

            <table id="studyProgramsTable" class="table table-bordered">
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
                                <a href="{{ route('study-program.edit', ['id' => $study_program->id]) }}" class="btn btn-warning btn-sm">Edit</a>
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

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    document.addEventListener('livewire:load', function () {
        const table = $('#studyProgramsTable').DataTable({
            responsive: true,
            stateSave: true,
        });

        Livewire.hook('message.processed', (message, component) => {
            table.destroy(); // Hancurkan tabel sebelumnya
            $('#studyProgramsTable').DataTable({ // Inisialisasi ulang
                responsive: true,
                stateSave: true,
            });
        });
    });
</script>
@endpush
