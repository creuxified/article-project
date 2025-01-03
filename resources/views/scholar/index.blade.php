{{-- <div class="container">
    <h1 class="mb-4">Scholar Publications</h1>
    <p>Current User ID: {{ auth()->user()->id }}</p>
    <p>Scholar ID: {{ auth()->user()->scholar }}</p>

    <!-- Notifikasi Sukses -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form untuk Author ID -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('scholar.scrape') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="text" class="form-control @error('author_id') is-invalid @enderror" id="author_id" name="author_id" value="{{ auth()->user()->scholar }}" placeholder="Masukkan Author ID">
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
                                        <a href="{{ $publication->link }}" target="_blank">View</a>
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
</div> --}}

{{-- <!DOCTYPE html>
<html>
<head>
    <title>Scraped Data</title>
</head>
<body>
    <h1>Scraped Data</h1>

    <!-- Form untuk menginput ID User -->
    <form action="{{ route('scrape') }}" method="GET">
        <label for="author_id">Input ID User</label>
        <input type="text" name="author_id" id="author_id" placeholder="Masukkan ID User" required>
        <button type="submit">Scrape</button>
    </form>

    <!-- Menampilkan data profil -->
    @if (isset($dataScrapping['profile']))
        <h2>Profile Information:</h2>
        <p>Name: {{ $dataScrapping['profile']['name'] }}</p>
        <p>Affiliation: {{ $dataScrapping['profile']['affiliation'] }}</p>
        <p>Email Verified: {{ $dataScrapping['profile']['email_verified'] }}</p>
        <p>Interests: {{ $dataScrapping['profile']['interests'] }}</p>
        <p>Photo: <img src="{{ $dataScrapping['profile']['photo_url'] }}" alt="Profile Photo"></p>
    @endif

    <!-- Menampilkan data "Cited by" -->
    @if (isset($dataScrapping['cited_by']))
        <h2>Cited By Information:</h2>
        <ul>
            <li>Citations (All): {{ $dataScrapping['cited_by']['citations_all'] }}</li>
            <li>Citations (Since 2019): {{ $dataScrapping['cited_by']['citations_since_2019'] }}</li>
            <li>h-index (All): {{ $dataScrapping['cited_by']['h_index_all'] }}</li>
            <li>h-index (Since 2019): {{ $dataScrapping['cited_by']['h_index_since_2019'] }}</li>
            <li>i10-index (All): {{ $dataScrapping['cited_by']['i10_index_all'] }}</li>
            <li>i10-index (Since 2019): {{ $dataScrapping['cited_by']['i10_index_since_2019'] }}</li>
        </ul>
    @endif

    <!-- Menampilkan data grafik -->
    @if (isset($dataScrapping['chart']))
        <h2>Article Count Over Years:</h2>
        <ul>
            @foreach ($dataScrapping['chart'] as $item)
                <li>Year: {{ $item['year'] }} - Count: {{ $item['count'] }}</li>
            @endforeach
        </ul>
    @endif

        <!-- Displaying Articles Data -->
        @if (isset($dataScrapping['articles']))
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Article Data</h5>
                <table id="articlesTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Year</th>
                            <th>Citations</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataScrapping['articles'] as $article)
                            <tr>
                                <td>{{ $article['title'] }}</td>
                                <td>{{ $article['year'] }}</td>
                                <td>{{ $article['citations'] }}</td>
                                <td><a href="{{ $article['url'] }}" target="_blank" class="btn btn-link">View Article</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#articlesTable').DataTable();
            });
        </script>
    @endif
</body>
</html> --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholar Publications</title>
    <p>Current User ID: {{ auth()->user()->id }}</p>
    <p>Scholar ID: {{ auth()->user()->scholar }}</p>
    <!-- Add your CSS, Tailwind or other styling frameworks -->
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <!-- Notification success -->
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

          <!-- Delete Data Button -->
          <form action="{{ route('scholar.deleteData') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all your data?');">
            @csrf
            @method('DELETE') <!-- Specify that this is a DELETE request -->
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete Data</button>
        </form>

        <!-- Form untuk scrape data dari scholar -->
        <div class="mb-4">
            <form action="{{ route('scholar.scrape') }}" method="POST">
                @csrf
                <div class="flex items-center space-x-4">
                    <label for="author_id" class="font-bold">Author ID</label>
                    <input type="text" name="author_id" id="author_id" class="p-2 border rounded" value="{{ auth()->user()->scholar }}"
                        placeholder="Enter Author ID" required>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Scrape</button>
                </div>
            </form>
        </div>

        <!-- Menampilkan data "Cited by" -->
        @if (session('scrapedData') && isset(session('scrapedData')['cited_by']))
            <h2>Cited By Information:</h2>
            <ul>
                <li>Citations (All): {{ session('scrapedData')['cited_by']['citations_all'] }}</li>
                <li>Citations (Since 2019): {{ session('scrapedData')['cited_by']['citations_since_2019'] }}</li>
                <li>h-index (All): {{ session('scrapedData')['cited_by']['h_index_all'] }}</li>
                <li>h-index (Since 2019): {{ session('scrapedData')['cited_by']['h_index_since_2019'] }}</li>
                <li>i10-index (All): {{ session('scrapedData')['cited_by']['i10_index_all'] }}</li>
                <li>i10-index (Since 2019): {{ session('scrapedData')['cited_by']['i10_index_since_2019'] }}</li>
            </ul>
        @endif

        <!-- Menampilkan data grafik -->
        @if (session('scrapedData') && isset(session('scrapedData')['chart']))
            <h2>Article Count Over Years:</h2>
            <ul>
                @foreach (session('scrapedData')['chart'] as $item)
                    <li>Year: {{ $item['year'] }} - Count: {{ $item['count'] }}</li>
                @endforeach
            </ul>
        @endif



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
                                            <a href="{{ $publication->link }}" target="_blank">View</a>
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
</body>

</html>
