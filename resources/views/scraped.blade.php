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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Highcharts CSS (Optional for custom styling) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Highcharts JS -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(to bottom right, #1e2836, #121928);
            color: #fff;
        }

        footer {
            margin-top: auto;
            background-color: #1e293b;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        .search-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin: auto;
            max-width: 600px;
            padding: 40px 20px;
        }

        .search-container h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .search-container form {
            width: 200%;
            position: relative;
        }

        .search-container input[type="text"] {
            width: 100%;
            padding: 10px 20px;
            font-size: 1.2rem;
            border-radius: 30px;
            border: none;
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .search-container button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background-color: #2563eb;
            border: none;
            color: #fff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .search-container button:hover {
            background-color: #1d4ed8;
        }

        .container-content {
            margin: 20px auto;
            padding: 20px;
            max-width: 900px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .profile-photo {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .profile-photo img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .content-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px auto;
            max-width: 1200px;
        }

        .left-column,
        .right-column {
            flex: 1;
            min-width: 300px;
        }

        .card {
            background: white;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        /* Style for consistent ":" formatting */
        .card-body p {
            font-size: 1.1rem;
            margin: 8px 0;
        }

        .card-body p strong {
            color: #fff;
        }

        .table,
        .table-bordered,
        .table-hover {
            background-color: transparent !important; /* Membuat background tabel transparan */
            color: #fff; /* Menjaga warna teks agar tetap terlihat */
        }

        .table th,
        .table td {
            border: 1px solid rgba(255, 255, 255, 0.2); /* Menggunakan border putih transparan */
            text-align: center;
            padding: 12px 18px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.1); /* Warna latar belakang baris ganjil */
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: rgba(255, 255, 255, 0.2); /* Warna latar belakang baris genap */
        }

        .pagination {
            justify-content: center;
        }

        .pagination .page-item {
            margin: 0 2px;
        }

        .pagination .page-item a {
            color: #2563eb;
        }

        .pagination .page-item.active a {
            background-color: #2563eb;
            color: white;
        }

        .pagination .page-item:hover a {
            background-color: #1d4ed8;
        }

        .table-container {
            margin-top: 40px;
        }



    </style>
</head>

<body>
    <div class="search-container">
        <h1>Scrap Data</h1>
       <p>Current User ID: {{ auth()->user()->id }}</p>
        <form action="{{ route('scrape') }}" method="GET">
            <input type="text" name="id_user_scholar" id="id_user_scholar" placeholder="Enter User ID or Name" required>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sync"></i>
            </button>
        </form>
    </div>

    <div class="container">
        <div class="content-wrapper">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Displaying Profile Data -->
                @if (isset($dataScrapping['profile']))
                    <div class="card shadow-sm mb-4">
                        <div class="card-body d-flex flex-column align-items-center">
                            <div class="profile-photo">
                                <img src="{{ $dataScrapping['profile']['photo_url'] }}" alt="Profile Photo">
                            </div>
                            <div>
                                <h5 class="card-title">Profile Information</h5>
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <td style="text-align: left;"><strong>Name:</strong></td>
                                            <td style="text-align: left;">{{ $dataScrapping['profile']['name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;"><strong>Affiliation:</strong></td>
                                            <td style="text-align: left;">{{ $dataScrapping['profile']['affiliation'] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;"><strong>Email Verified:</strong></td>
                                            <td style="text-align: left;">{{ $dataScrapping['profile']['email_verified'] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;"><strong>Interests:</strong></td>
                                            <td style="text-align: left;">{{ $dataScrapping['profile']['interests'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Displaying "Cited by" Data -->
                @if (isset($dataScrapping['cited_by']))
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Citation Information</h5>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Method</th>
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
                                    text: ''
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

                    <!-- Displaying "Cited By" Graph -->
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
                                text: ''
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

                <!-- Displaying Article Count per Year -->
                @if (isset($dataScrapping['chart']))
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Articles per Year</h5>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Year</th>
                                        <th>Article Count</th>
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
            </div>

            <!-- Right Column -->
            <div class="right-column">
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
            </div>
        </div>
    </div>

    <footer>
        &copy; 2025 Scraped Data Platform. All rights reserved.
    </footer>

    <!-- Bootstrap JS -->
    <script>
        $(document).ready(function() {
            // Pastikan DataTable hanya diinisialisasi jika belum ada
            if (!$.fn.dataTable.isDataTable('#articlesTable')) {
                $('#articlesTable').DataTable({
                    pagingType: 'full_numbers',
                    language: {
                        paginate: {
                            first: 'First',
                            last: 'Last',
                            next: 'Next',
                            previous: 'Previous'
                        }
                    }
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
