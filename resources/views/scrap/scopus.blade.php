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
            background: rgba(255, 255, 255, 0.1);
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

    <h1>Scrape Data dari Scopus</h1>
       <p>Current User ID: {{ auth()->user()->id }}</p>

    <form action="{{ url('/scrap/scopus') }}" method="POST">
        @csrf
        <label for="scopus_id">Masukkan Scopus ID:</label>
        <input type="text" id="scopus_id" name="scopus_id" required>
        <button type="submit">Scrape</button>
    </form>

    @if(session('status'))
        <div>
            <strong>{{ session('status') }}</strong>
        </div>
    @endif

    @if(session('error'))
        <div>
            <strong>{{ session('error') }}</strong>
        </div>
    @endif

    <h2>Data Publikasi:</h2>

    <table id="publikasiTable" class="display">
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
            @foreach($publications as $pub) <!-- Changed from $publication to $publications -->
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

    <div id="citationsChart" style="width:100%; height:400px;"></div>
    <div id="publicationsChart" style="width:100%; height:400px;"></div>

    <script>
    $(document).ready(function() {
        $('#publikasiTable').DataTable();
    });

    Highcharts.chart('citationsChart', {
        chart: { type: 'column' },
        title: { text: 'Jumlah Citations per Publikasi' },
        xAxis: {
            categories: [
                @foreach($publications as $pub) <!-- Changed from $publication to $publications -->
                    '{{ $pub->title }}',
                @endforeach
            ],
            title: { text: 'Publikasi' }
        },
        yAxis: {
            min: 0,
            title: { text: 'Citations' }
        },
        series: [{
            name: 'Citations',
            data: [
                @foreach($publications as $pub) <!-- Changed from $publication to $publications -->
                    {{ $pub->citations }},
                @endforeach
            ]
        }]
    });

    let publicationData = {};

    @foreach($publications as $pub) <!-- Changed from $publication to $publications -->
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
        title: { text: 'Jumlah Publikasi per Tahun' },
        xAxis: {
            categories: years,
            title: { text: 'Tahun' }
        },
        yAxis: {
            min: 0,
            title: { text: 'Jumlah Publikasi' }
        },
        series: [{
            name: 'Publikasi',
            data: publications
        }]
    });
    </script>

</body>


</html>
