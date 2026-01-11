<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szukaj książek</title>
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
        .search-form {
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
        }
        .search-input {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
            font-size: 1rem;
        }
        .search-button {
            background-color: #ef3b2d;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 1rem;
        }
        .search-button:hover {
            background-color: #c22f28;
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
            margin-bottom: 1rem;
        }
        .import-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.9rem;
            width: 100%;
        }
        .import-button:hover {
            background-color: #218838;
        }
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-title">Szukaj książek w OpenLibrary</div>
        <a href="{{ route('main') }}" class="backButton">Powrót</a>
    </header>

    <main>
        <h1>Wyszukaj książki</h1>

        <form action="{{ route('books.search.api') }}" method="GET" class="search-form">
            <input type="text" name="query" class="search-input" placeholder="Wpisz tytuł, autora lub ISBN..." value="{{ $query ?? '' }}" required>
            <button type="submit" class="search-button">Szukaj</button>
        </form>

        @if(session('error'))
            <div class="alert alert-danger" style="padding: 0.75rem 1.25rem; margin-bottom: 1rem; border: 1px solid #f5c6cb; border-radius: 0.25rem; color: #721c24; background-color: #f8d7da;">
                {{ session('error') }}
            </div>
        @endif

        @if(isset($books) && count($books) > 0)
            <h2 style="margin-bottom: 1rem;">Wyniki wyszukiwania ({{ count($books) }})</h2>
            <div class="books-grid">
                @foreach($books as $book)
                    <div class="book-card">
                        @if(isset($book['cover_i']))
                            <img src="https://covers.openlibrary.org/b/id/{{ $book['cover_i'] }}-M.jpg" 
                                 alt="{{ $book['title'] ?? 'Book cover' }}" class="book-cover">
                        @else
                            <div class="book-cover-placeholder">
                                {{ strtoupper(substr($book['title'] ?? '?', 0, 1)) }}
                            </div>
                        @endif
                        
                        <div class="book-info">
                            <div class="book-title">{{ $book['title'] ?? 'Brak tytułu' }}</div>
                            <div class="book-author">
                                {{ isset($book['author_name']) ? implode(', ', array_slice($book['author_name'], 0, 2)) : 'Nieznany autor' }}
                            </div>
                            
                            <form action="{{ route('books.import') }}" method="POST">
                                @csrf
                                <input type="hidden" name="title" value="{{ $book['title'] ?? '' }}">
                                <input type="hidden" name="author" value="{{ isset($book['author_name']) ? $book['author_name'][0] : '' }}">
                                @if(isset($book['cover_i']))
                                    <input type="hidden" name="cover_url" value="https://covers.openlibrary.org/b/id/{{ $book['cover_i'] }}-L.jpg">
                                @endif
                                <button type="submit" class="import-button">+ Dodaj do biblioteki</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif(isset($query))
            <div class="empty-state">
                <p>Nie znaleziono książek dla zapytania: "{{ $query }}"</p>
                <p>Spróbuj wyszukać coś innego.</p>
            </div>
        @else
            <div class="empty-state">
                <p>Wpisz tytuł, autora lub ISBN, aby wyszukać książki.</p>
            </div>
        @endif
    </main>
</body>
</html>
