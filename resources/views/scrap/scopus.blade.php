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
    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <!-- Highcharts JS -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
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
            margin-bottom: 30px;
        }

        .search-container h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .input-group {
            display: flex;
            width: 100%;
            max-width: 600px;
        }

        .input-group input[type="text"] {
            flex: 1;
            padding: 10px 20px;
            font-size: 1.2rem;
            border-radius: 30px;
            border: none;
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .input-group button {
            background-color: #2563eb;
            border: none;
            color: #fff;
            border-radius: 30px;
            padding: 10px 20px;
            margin-left: 10px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .input-group button:hover {
            background-color: #1d4ed8;
        }

        .container-content {
            margin: 20px auto;
            padding: 20px;
            max-width: 100%; /* Make the content container take full width */
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .card {
            background: white;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            width: 100%; /* Ensure the card takes full width of its container */
        }

        .card-header {
            font-size: 1.5rem;
            font-weight: bold;
            background-color: #1e293b;
            color: #fff;
            padding: 10px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .card-body {
            padding: 20px;
        }

        .table {
            width: 100%; /* Ensure the table takes up 100% of the card width */
            color: #fff;
        }

        .table th,
        .table td {
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
            padding: 12px 18px;
        }

        .pagination {
            justify-content: center;
        }

        .pagination .page-item {
            margin: 0 2px;
        }

        .pagination .page-item a {
            color: #fff;
        }

        .pagination .page-item.active a {
            background-color: #2563eb;
            color: white;
        }

        .pagination .page-item:hover a {
            background-color: #1d4ed8;
        }

        .charts-container {
            display: flex;
            flex-direction: column;
        }

        .chart-container {
            width: 100%;
            height: 400px;
            margin-bottom: 20px;
        }

        .left-column,
        .right-column {
            flex: 1;
            padding: 20px;
        }

        .left-column {
            width: 60%; /* Left column will occupy 60% of the container */
        }

        .right-column {
            width: 100%; /* Right column will occupy 40% of the container */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .left-column,
            .right-column {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="search-container">
        <h1>Scrape Data dari Scopus</h1>
        <p>Current User ID: {{ auth()->user()->id }}</p>

        <form action="{{ url('/scrap/scopus') }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="text" id="scopus_id" name="scopus_id" required placeholder="Masukkan Scopus ID">
                <button type="submit">Scrape</button>
            </div>
        </form>
    </div>

    @if(session('status'))
        <div style="color: green; font-weight: bold;">
            <strong>{{ session('status') }}</strong>
        </div>
    @endif

    @if(session('error'))
        <div style="color: red; font-weight: bold;">
            <strong>{{ session('error') }}</strong>
        </div>
    @endif

    <div class="container">
        <div class="content-wrapper d-flex">
            <div class="left-column">
                <!-- Citations Chart Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Number of Citations per Publication</h5>
                        <div id="citationsChart"></div>
                    </div>
                </div>

                <!-- Publications Chart Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Number of Publications per Year</h5>
                        <div id="publicationsChart"></div>
                    </div>
                </div>
            </div>

            <div class="right-column">
                <!-- Publications Data Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Publication Data</h5>
                        <table id="publikasiTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Journal Name</th>
                                    <th>Publication Date</th>
                                    <th>Citations</th>
                                    <th>DOI</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($publications as $pub)
                                    <tr>
                                        <td>{{ $pub->title }}</td>
                                        <td>{{ $pub->journal_name }}</td>
                                        <td>{{ $pub->publication_date }}</td>
                                        <td>{{ $pub->citations }}</td>
                                        <td>{{ $pub->doi }}</td>
                                        <td><a href="https://doi.org/{{ $pub->doi }}" target="_blank">Link Article</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#publikasiTable').DataTable();
        });

        Highcharts.chart('citationsChart', {
            chart: { type: 'column' },
            title: { text: '' },
            xAxis: {
                categories: [
                    @foreach($publications as $pub)
                        '{{ $pub->title }}',
                    @endforeach
                ],
                title: { text: 'Publications' }
            },
            yAxis: {
                min: 0,
                title: { text: 'Citations' }
            },
            series: [{
                name: 'Citations',
                data: [
                    @foreach($publications as $pub)
                        {{ $pub->citations }},
                    @endforeach
                ]
            }]
        });

        let publicationData = {};

        @foreach($publications as $pub)
            (function() {
                let date = new Date('{{ $pub->publication_date }}');
                let year = date.getFullYear();
                publicationData[year] = (publicationData[year] || 0) + 1;
            })();
        @endforeach

        let years = Object.keys(publicationData);
        let publications = Object.values(publicationData);

        Highcharts.chart('publicationsChart', {
            chart: { type: 'line' },
            title: { text: '' },
            xAxis: {
                categories: years,
                title: { text: 'Year' }
            },
            yAxis: {
                min: 0,
                title: { text: 'Number of Publications' }
            },
            series: [{
                name: 'Publications',
                data: publications
            }]
        });
    </script>

</body>

</html>
