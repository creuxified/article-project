<div class="container">
    <h1 class="mb-4">Google Scholar Data</h1>

    {{-- Form untuk memasukkan User ID --}}
    <form method="GET" action="{{ route('scraper.index') }}" class="mb-5">
        <div class="form-group">
            <label for="id_user_scholar">Google Scholar User ID</label>
            <input type="text" name="id_user_scholar" id="id_user_scholar" class="form-control" placeholder="Enter User ID" value="{{ request('id_user_scholar') }}">
        </div>
        <button type="submit" class="btn btn-primary mt-2">Fetch Data</button>
    </form>

    {{-- Cek apakah dataScrapping ada --}}
    @if($dataScrapping)
        {{-- Seksi Profil --}}
        <div class="card mb-4">
            <div class="card-header">Profile Information</div>
            <div class="card-body">
                <img src="{{ $dataScrapping['profile']['photo_url'] }}" alt="Profile Picture" class="img-thumbnail mb-3" style="width: 150px;">
                <p><strong>Name:</strong> {{ $dataScrapping['profile']['name'] }}</p>
                <p><strong>Affiliation:</strong> {{ $dataScrapping['profile']['affiliation'] }}</p>
                <p><strong>Email Verified:</strong> {{ $dataScrapping['profile']['email_verified'] }}</p>
                <p><strong>Interests:</strong> {{ $dataScrapping['profile']['interests'] }}</p>
            </div>
        </div>

        {{-- Seksi Cited By --}}
        <div class="card mb-4">
            <div class="card-header">Cited By Data</div>
            <div class="card-body">
                <p><strong>Citations (All):</strong> {{ $dataScrapping['cited_by']['citations_all'] }}</p>
                <p><strong>Citations (Since 2019):</strong> {{ $dataScrapping['cited_by']['citations_since_2019'] }}</p>
                <p><strong>H-Index (All):</strong> {{ $dataScrapping['cited_by']['h_index_all'] }}</p>
                <p><strong>H-Index (Since 2019):</strong> {{ $dataScrapping['cited_by']['h_index_since_2019'] }}</p>
                <p><strong>I10-Index (All):</strong> {{ $dataScrapping['cited_by']['i10_index_all'] }}</p>
                <p><strong>I10-Index (Since 2019):</strong> {{ $dataScrapping['cited_by']['i10_index_since_2019'] }}</p>
            </div>
        </div>

        {{-- Seksi Artikel --}}
        <div class="card mb-4">
            <div class="card-header">Articles</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Year</th>
                            <th>Citations</th>
                            <th>Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataScrapping['articles'] as $article)
                            <tr>
                                <td>{{ $article['title'] }}</td>
                                <td>{{ $article['year'] }}</td>
                                <td>{{ $article['citations'] }}</td>
                                <td><a href="{{ $article['url'] }}" target="_blank">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Seksi Data Chart --}}
        <div class="card">
            <div class="card-header">Chart Data</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataScrapping['chart'] as $data)
                            <tr>
                                <td>{{ $data['year'] }}</td>
                                <td>{{ $data['count'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p>No data available. Please enter a valid Google Scholar User ID.</p>
    @endif
</div>
