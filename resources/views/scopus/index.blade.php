<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Scopus Author</title>
</head>
<body>
    <h1>Search Scopus by Author ID</h1>
    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p style="color: red;">{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form action="{{ route('scopus.fetch') }}" method="POST">
        @csrf
        <input type="text" name="author_id" placeholder="Enter Author ID" required>
        <button type="submit">Search</button>
    </form>
</body>
</html>
