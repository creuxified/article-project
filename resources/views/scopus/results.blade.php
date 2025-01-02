<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scopus Author Results</title>
</head>
<body>
    <h1>Scopus Author Information</h1>
    <a href="{{ route('scopus.index') }}">Back to Search</a>
    @if ($author)
        <p><strong>Name:</strong> {{ $author['preferred-name']['given-name'] ?? 'N/A' }} {{ $author['preferred-name']['surname'] ?? '' }}</p>
        <p><strong>Affiliation:</strong> {{ $author['affiliation-current']['affiliation-name'] ?? 'N/A' }}</p>
        <p><strong>Document Count:</strong> {{ $author['coredata']['document-count'] ?? '0' }}</p>
        <p><strong>Cited By Count:</strong> {{ $author['coredata']['cited-by-count'] ?? '0' }}</p>
        <p><strong>Subjects:</strong></p>
        <ul>
            @foreach ($author['subject-area'] ?? [] as $subject)
                <li>{{ $subject['$'] ?? 'N/A' }} ({{ $subject['@abbrev'] ?? 'N/A' }})</li>
            @endforeach
        </ul>
    @else
        <p>No author data found.</p>
    @endif
</body>
</html>
