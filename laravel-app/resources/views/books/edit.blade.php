<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj książkę</title>
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
        .user-name {
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
            max-width: 600px;
            margin: 0 auto;
            width: 100%;
        }
        .card {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #ef3b2d;
            margin-top: 0;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
            box-sizing: border-box;
            font-size: 1rem;
        }
        input[type="file"] {
            padding: 0.5rem;
        }
        .submitButton {
            background-color: #ef3b2d;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
        }
        .submitButton:hover {
            background-color: #c22f28;
        }
        .alert {
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .alert ul {
            margin: 0;
            padding-left: 1.5rem;
        }
        .current-cover {
            margin-top: 0.5rem;
            max-width: 200px;
        }
        .current-cover img {
            width: 100%;
            border-radius: 0.25rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="user-name">Edytuj książkę</div>
        <a href="{{ route('main') }}" class="backButton">Powrót</a>
    </header>

    <main>
        <div class="card">
            <h1>Formularz edycji książki</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Wystąpiły błędy:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Tytuł książki *</label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        required 
                        minlength="1"
                        maxlength="255"
                        title="Tytuł książki jest wymagany (max 255 znaków)"
                        value="{{ old('title', $book->title) }}">
                </div>

                <div class="form-group">
                    <label for="author">Autor *</label>
                    <input 
                        type="text" 
                        id="author" 
                        name="author" 
                        required 
                        minlength="1"
                        maxlength="255"
                        title="Autor jest wymagany (max 255 znaków)"
                        value="{{ old('author', $book->author) }}">
                </div>

                <div class="form-group">
                    <label for="rating">Ocena (0-10)</label>
                    <input type="number" id="rating" name="rating" min="0" max="10" step="0.1" value="{{ old('rating', $book->rating) }}">
                </div>

                <div class="form-group">
                    <label for="reading_status">Status czytania *</label>
                    <select id="reading_status" name="reading_status" required style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 0.25rem; font-size: 1rem;">
                        <option value="planuje" {{ old('reading_status', $book->reading_status) == 'planuje' ? 'selected' : '' }}>Planuję przeczytać</option>
                        <option value="czytam" {{ old('reading_status', $book->reading_status) == 'czytam' ? 'selected' : '' }}>Czytam</option>
                        <option value="porzucona" {{ old('reading_status', $book->reading_status) == 'porzucona' ? 'selected' : '' }}>Porzucona</option>
                        <option value="przeczytana" {{ old('reading_status', $book->reading_status) == 'przeczytana' ? 'selected' : '' }}>Przeczytana</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cover">Okładka książki</label>
                    @if($book->cover_path)
                        <div class="current-cover">
                            <p style="font-size: 0.9rem; color: #666; margin-bottom: 0.5rem;">Obecna okładka:</p>
                            <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}">
                        </div>
                    @endif
                    <input type="file" id="cover" name="cover" accept="image/*" style="margin-top: 0.5rem;">
                    <p style="font-size: 0.85rem; color: #666; margin-top: 0.25rem;">Zostaw puste, aby zachować obecną okładkę</p>
                </div>

                <button type="submit" class="submitButton">Zaktualizuj książkę</button>
            </form>
        </div>
    </main>
</body>
</html>
