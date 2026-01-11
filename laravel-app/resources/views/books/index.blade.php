<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Książki w bibliotece</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            background-color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-title {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
        }
        .backButton {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-block;
        }
        .backButton:hover {
            background-color: #5a6268;
        }
        main {
            padding: 2rem;
            flex: 1;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }
        h1 {
            color: #ef3b2d;
            margin-bottom: 2rem;
        }
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
        }
        .book-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .book-cover {
            width: 100%;
            height: 300px;
            object-fit: cover;
            background-color: #e5e7eb;
        }
        .book-cover-placeholder {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            font-weight: bold;
        }
        .book-info {
            padding: 1rem;
        }
        .book-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .book-author {
            color: #666;
            margin-bottom: 0.5rem;
        }
        .book-rating {
            color: #ef3b2d;
            font-weight: 600;
        }
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }
        .empty-state a {
            color: #ef3b2d;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-title">Książki w bibliotece</div>
        <a href="{{ route('main') }}" class="backButton">Powrót</a>
    </header>

    <main>
        <h1>Wszystkie książki ({{ $books->count() }})</h1>

        @if($books->isEmpty())
            <div class="empty-state">
                <p>Nie masz jeszcze żadnych książek w bibliotece.</p>
                <p><a href="{{ route('books.create') }}">Dodaj pierwszą książkę</a></p>
            </div>
        @else
            <div class="books-grid">
                @foreach($books as $book)
                    <div class="book-card">
                        @if($book->cover_path)
                            <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" class="book-cover">
                        @else
                            <div class="book-cover-placeholder">
                                {{ strtoupper(substr($book->title, 0, 1)) }}
                            </div>
                        @endif
                        
                        <div class="book-info">
                            <div class="book-title">{{ $book->title }}</div>
                            <div class="book-author">{{ $book->author }}</div>
                            @if($book->rating)
                                <div class="book-rating">★ {{ $book->rating }}/10</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
