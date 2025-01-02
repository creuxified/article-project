<div class="container">
    <h1 class="mb-4">Scopus Publications</h1>
    <p>Current User ID: {{ auth()->user()->id }}</p>
    <p>Scopus ID: {{ auth()->user()->scopus }}</p>

    <!-- Notifikasi Sukses -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form untuk Author ID -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('scopus.scrape') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="hidden" class="form-control @error('author_id') is-invalid @enderror" id="author_id" name="author_id" value="{{ auth()->user()->scopus }}" placeholder="Masukkan Author ID">
                    @error('author_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Scrape Publications</button>
            </form>
        </div>
    </div>

    <!-- Tabel Data Publikasi -->
    <div class="card">
        <div class="card-header">
            <h2>Publications</h2>
        </div>
        <div class="card-body">
            @if ($publications->isEmpty())
                <p>Tidak ada data publikasi yang tersedia.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Author</th>
                            <th>Title</th>
                            <th>Journal</th>
                            <th>Publication Date</th>
                            <th>Citations</th>
                            <th>Source</th>
                            <th>Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($publications as $index => $publication)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $publication->author_name }}</td>
                                <td>{{ $publication->title }}</td>
                                <td>{{ $publication->journal_name }}</td>
                                <td>{{ $publication->publication_date }}</td>
                                <td>{{ $publication->citations }}</td>
                                <td>{{ $publication->source }}</td>
                                <td>
                                    @if ($publication->link)
                                        <a href="https://doi.org/{{ $publication->link }}" target="_blank">View</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
