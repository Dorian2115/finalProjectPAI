<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona główna</title>
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
        .logoutButton {
            background-color: #ef3b2d;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.2s;
        }
        .logoutButton:hover {
            background-color: #c22f28;
        }
        footer{
            margin-top: 2rem;
            text-align: center;
            padding: 2rem;
            color: #777;
        }
        main {
            padding: 2rem;
            text-align: center;
            color: #555;
            flex: 1;
        }
    </style>
</head>
<body>
    <header>
        <div class="user-name">Witaj, {{ Auth::user()->name }}</div>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="logoutButton">Wyloguj</button>
        </form>
    </header>

    <main>
        <!-- Favorites Section -->
        @if($favoriteBooks->isNotEmpty())
            <h2 style="color: #ef3b2d; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                ⭐ Ulubione książki ({{ $favoriteBooks->count() }})
            </h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
                @foreach($favoriteBooks as $book)
                    <div style="background: white; border-radius: 0.5rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; position: relative; border: 2px solid #ffd700;"
                         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.15)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0, 0, 0, 0.1)'">
                        
                        <!-- Edit icon -->
                        <a href="{{ route('books.edit', $book) }}" style="
                            position: absolute;
                            top: 0.75rem;
                            right: 0.75rem;
                            background: rgba(255, 255, 255, 0.95);
                            width: 2.5rem;
                            height: 2.5rem;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            text-decoration: none;
                            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
                            z-index: 10;
                            transition: all 0.2s;
                        " onmouseover="this.style.backgroundColor='#ef3b2d'; this.style.transform='scale(1.1)'" 
                           onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.95)'; this.style.transform='scale(1)'">
                            <span style="font-size: 1.2rem;">✏️</span>
                        </a>
                        
                        <!-- Delete button -->
                        <form action="{{ route('books.destroy', $book) }}" method="POST" style="
                            position: absolute;
                            top: 4rem;
                            right: 0.75rem;
                            z-index: 10;
                        " onsubmit="return confirm('Czy na pewno chcesz usunąć tę książkę?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="
                                background: rgba(255, 255, 255, 0.95);
                                width: 2.5rem;
                                height: 2.5rem;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                border: none;
                                cursor: pointer;
                                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
                                transition: all 0.2s;
                            " onmouseover="this.style.backgroundColor='#dc3545'; this.style.transform='scale(1.1)'" 
                               onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.95)'; this.style.transform='scale(1)'">
                                <span style="font-size: 1.2rem;">🗑️</span>
                            </button>
                        </form>
                        
                        @if($book->cover_path)
                            <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" 
                                 style="width: 100%; height: 300px; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; font-weight: bold;">
                                {{ strtoupper(substr($book->title, 0, 1)) }}
                            </div>
                        @endif
                        
                        <div style="padding: 1rem;">
                            <div style="font-weight: 600; font-size: 1.1rem; color: #333; margin-bottom: 0.5rem;">
                                {{ $book->title }}
                            </div>
                            <div style="color: #666; margin-bottom: 0.5rem;">
                                {{ $book->author }}
                            </div>
                            <div style="margin-bottom: 0.5rem;">
                                @php
                                    $statusColors = [
                                        'planuje' => '#6c757d',
                                        'czytam' => '#007bff',
                                        'porzucona' => '#dc3545',
                                        'przeczytana' => '#28a745',
                                    ];
                                    $statusLabels = [
                                        'planuje' => 'Planuję',
                                        'czytam' => 'Czytam',
                                        'porzucona' => 'Porzucona',
                                        'przeczytana' => 'Przeczytana',
                                    ];
                                @endphp
                                <span style="background-color: {{ $statusColors[$book->reading_status] }}; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.85rem; display: inline-block;">
                                    {{ $statusLabels[$book->reading_status] }}
                                </span>
                            </div>
                            @if($book->rating)
                                <div style="color: #ef3b2d; font-weight: 600; margin-bottom: 0.5rem;">
                                    ★ {{ $book->rating }}/10
                                </div>
                            @endif
                            
                            <!-- Favorite button -->
                            <form action="{{ route('books.favorite', $book) }}" method="POST" style="margin-top: 0.75rem;">
                                @csrf
                                <button type="submit" style="
                                    background: none;
                                    border: none;
                                    cursor: pointer;
                                    font-size: 1.5rem;
                                    padding: 0;
                                    transition: transform 0.2s;
                                " onmouseover="this.style.transform='scale(1.2)'" 
                                   onmouseout="this.style.transform='scale(1)'">
                                    ⭐
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Main Library Section -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 style="margin: 0;">Twoja biblioteka ({{ $books->count() }})</h1>
            <a href="{{ route('books.create') }}" style="
                background-color: #ef3b2d;
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                text-decoration: none;
                display: inline-block;
                font-weight: 600;
                transition: background-color 0.2s;
            " onmouseover="this.style.backgroundColor='#c22f28'" onmouseout="this.style.backgroundColor='#ef3b2d'">
                + Dodaj książkę
            </a>
        </div>

        @if($books->isEmpty())
            <div style="text-align: center; padding: 4rem 2rem; color: #666;">
                <p>Nie masz jeszcze żadnych książek w bibliotece.</p>
                <p>Użyj przycisku powyżej, aby dodać pierwszą książkę.</p>
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 2rem;">
                @foreach($books as $book)
                    <div style="background: white; border-radius: 0.5rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; position: relative;"
                         onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.15)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0, 0, 0, 0.1)'">
                        
                        <!-- Edit icon -->
                        <a href="{{ route('books.edit', $book) }}" style="
                            position: absolute;
                            top: 0.75rem;
                            right: 0.75rem;
                            background: rgba(255, 255, 255, 0.95);
                            width: 2.5rem;
                            height: 2.5rem;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            text-decoration: none;
                            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
                            z-index: 10;
                            transition: all 0.2s;
                        " onmouseover="this.style.backgroundColor='#ef3b2d'; this.style.transform='scale(1.1)'" 
                           onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.95)'; this.style.transform='scale(1)'">
                            <span style="font-size: 1.2rem;" id="edit-icon-{{ $book->id }}">✏️</span>
                        </a>
                        
                        <!-- Delete button -->
                        <form action="{{ route('books.destroy', $book) }}" method="POST" style="
                            position: absolute;
                            top: 4rem;
                            right: 0.75rem;
                            z-index: 10;
                        " onsubmit="return confirm('Czy na pewno chcesz usunąć tę książkę?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="
                                background: rgba(255, 255, 255, 0.95);
                                width: 2.5rem;
                                height: 2.5rem;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                border: none;
                                cursor: pointer;
                                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
                                transition: all 0.2s;
                            " onmouseover="this.style.backgroundColor='#dc3545'; this.style.transform='scale(1.1)'" 
                               onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.95)'; this.style.transform='scale(1)'">
                                <span style="font-size: 1.2rem;">🗑️</span>
                            </button>
                        </form>
                        
                        @if($book->cover_path)
                            <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" 
                                 style="width: 100%; height: 300px; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; font-weight: bold;">
                                {{ strtoupper(substr($book->title, 0, 1)) }}
                            </div>
                        @endif
                        
                        <div style="padding: 1rem;">
                            <div style="font-weight: 600; font-size: 1.1rem; color: #333; margin-bottom: 0.5rem;">
                                {{ $book->title }}
                            </div>
                            <div style="color: #666; margin-bottom: 0.5rem;">
                                {{ $book->author }}
                            </div>
                            <div style="margin-bottom: 0.5rem;">
                                @php
                                    $statusColors = [
                                        'planuje' => '#6c757d',
                                        'czytam' => '#007bff',
                                        'porzucona' => '#dc3545',
                                        'przeczytana' => '#28a745',
                                    ];
                                    $statusLabels = [
                                        'planuje' => 'Planuję',
                                        'czytam' => 'Czytam',
                                        'porzucona' => 'Porzucona',
                                        'przeczytana' => 'Przeczytana',
                                    ];
                                @endphp
                                <span style="background-color: {{ $statusColors[$book->reading_status] }}; color: white; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.85rem; display: inline-block;">
                                    {{ $statusLabels[$book->reading_status] }}
                                </span>
                            </div>
                            @if($book->rating)
                                <div style="color: #ef3b2d; font-weight: 600; margin-bottom: 0.5rem;">
                                    ★ {{ $book->rating }}/10
                                </div>
                            @endif
                            
                            <!-- Favorite button -->
                            <form action="{{ route('books.favorite', $book) }}" method="POST" style="margin-top: 0.75rem;">
                                @csrf
                                <button type="submit" style="
                                    background: none;
                                    border: none;
                                    cursor: pointer;
                                    font-size: 1.5rem;
                                    padding: 0;
                                    transition: transform 0.2s;
                                " onmouseover="this.style.transform='scale(1.2)'" 
                                   onmouseout="this.style.transform='scale(1)'">
                                    {{ $book->is_favorite ? '⭐' : '☆' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Biblioteka</p>
    </footer>
</body>
</html>