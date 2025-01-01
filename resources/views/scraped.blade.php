<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraped Data</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Highcharts CSS (Optional for custom styling) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Highcharts JS -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Scraped Data</h1>

        <!-- Form untuk menginput ID User -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Input ID User</h5>
                <form action="{{ route('scrape') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="id_user" id="id_user" class="form-control"
                        placeholder="Masukkan ID User" required>
                    <button type="submit" class="btn btn-primary">Scrape</button>
                </form>
            </div>
        </div>

        <!-- Menampilkan data profil -->
        @if (isset($dataScrapping['profile']))
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Profile Information</h5>
                    <p><strong>Name:</strong> {{ $dataScrapping['profile']['name'] }}</p>
                    <p><strong>Affiliation:</strong> {{ $dataScrapping['profile']['affiliation'] }}</p>
                    <p><strong>Email Verified:</strong> {{ $dataScrapping['profile']['email_verified'] }}</p>
                    <p><strong>Interests:</strong> {{ $dataScrapping['profile']['interests'] }}</p>
                    <p><strong>Photo:</strong> <img src="{{ $dataScrapping['profile']['photo_url'] }}"
                            alt="Profile Photo" class="img-thumbnail" style="max-width: 150px;"></p>
                </div>
            </div>
        @endif



        <!-- Menampilkan data "Cited by" -->
        @if (isset($dataScrapping['cited_by']))
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Cited By Information</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Metric</th>
                                <th>All</th>
                                <th>Since 2019</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Citations</td>
                                <td>{{ $dataScrapping['cited_by']['citations_all'] }}</td>
                                <td>{{ $dataScrapping['cited_by']['citations_since_2019'] }}</td>
                            </tr>
                            <tr>
                                <td>h-index</td>
                                <td>{{ $dataScrapping['cited_by']['h_index_all'] }}</td>
                                <td>{{ $dataScrapping['cited_by']['h_index_since_2019'] }}</td>
                            </tr>
                            <tr>
                                <td>i10-index</td>
                                <td>{{ $dataScrapping['cited_by']['i10_index_all'] }}</td>
                                <td>{{ $dataScrapping['cited_by']['i10_index_since_2019'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Menampilkan Grafik Cited By -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Cited By Metrics (Graph)</h5>
                    <div id="citedByChart"></div>
                </div>
            </div>

            <script>
                Highcharts.chart('citedByChart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Cited By Metrics (Citations, h-index, i10-index)'
                    },
                    xAxis: {
                        categories: ['Citations', 'h-index', 'i10-index']
                    },
                    yAxis: {
                        title: {
                            text: 'Count'
                        }
                    },
                    series: [{
                        name: 'All',
                        data: [
                            {{ $dataScrapping['cited_by']['citations_all'] }},
                            {{ $dataScrapping['cited_by']['h_index_all'] }},
                            {{ $dataScrapping['cited_by']['i10_index_all'] }}
                        ]
                    }, {
                        name: 'Since 2019',
                        data: [
                            {{ $dataScrapping['cited_by']['citations_since_2019'] }},
                            {{ $dataScrapping['cited_by']['h_index_since_2019'] }},
                            {{ $dataScrapping['cited_by']['i10_index_since_2019'] }}
                        ]
                    }]
                });
            </script>
        @endif

        <!-- Menampilkan data grafik -->
        @if (isset($dataScrapping['chart']))
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Article Count Over Years</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataScrapping['chart'] as $item)
                                <tr>
                                    <td>{{ $item['year'] }}</td>
                                    <td>{{ $item['count'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Menampilkan data grafik (Article Count Over Years) -->
        @if (isset($dataScrapping['chart']))
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Article Count Over Years</h5>
                    <div id="articleChart"></div>
                </div>
            </div>

            <script>
                Highcharts.chart('articleChart', {
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: 'Article Count Over Years'
                    },
                    xAxis: {
                        categories: [
                            @foreach ($dataScrapping['chart'] as $item)
                                '{{ $item['year'] }}',
                            @endforeach
                        ]
                    },
                    yAxis: {
                        title: {
                            text: 'Number of Articles'
                        }
                    },
                    series: [{
                        name: 'Articles',
                        data: [
                            @foreach ($dataScrapping['chart'] as $item)
                                {{ $item['count'] }},
                            @endforeach
                        ]
                    }]
                });
            </script>
        @endif

        <!-- Menampilkan Data Artikel Publikasi (DataTable) -->
        @if (isset($dataScrapping['articles']))
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Data Artikel</h5>
                    <table id="articlesTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Tahun</th>
                                <th>Sitasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataScrapping['articles'] as $article)
                                <tr>
                                    <td>{{ $article['title'] }}</td>
                                    <td>{{ $article['year'] }}</td>
                                    <td>{{ $article['citations'] }}</td>
                                    <td><a href="{{ $article['url'] }}" target="_blank">Lihat Artikel</a></td>
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

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
