<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class OpenLibraryController extends Controller
{
    public function search(): View
    {
        return view('books.search');
    }

    public function searchApi(Request $request): View
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return view('books.search', ['books' => [], 'query' => '']);
        }

        try {
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->retry(2, 100) // Retry 2 times with 100ms delay
                ->get('https://openlibrary.org/search.json', [
                    'q' => $query,
                    'limit' => 20,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $books = $data['docs'] ?? [];
            } else {
                $books = [];
                session()->flash('error', 'OpenLibrary nie odpowiada. Kod błędu: ' . $response->status());
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $books = [];
            session()->flash('error', 'Nie można połączyć się z OpenLibrary. Sprawdź połączenie internetowe lub spróbuj później.');
        } catch (\Exception $e) {
            $books = [];
            session()->flash('error', 'Wystąpił błąd: OpenLibrary może być obecnie niedostępne.');
        }

        return view('books.search', [
            'books' => $books,
            'query' => $query,
        ]);
    }

    public function import(Request $request): RedirectResponse
    {
        $bookData = [
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'rating' => null,
            'reading_status' => 'planuje',
            'user_id' => auth()->id(),
            'is_favorite' => false,
        ];

        $coverUrl = $request->input('cover_url');
        if ($coverUrl) {
            try {
                $coverContents = file_get_contents($coverUrl);
                $fileName = 'book-covers/' . uniqid() . '.jpg';
                \Storage::disk('public')->put($fileName, $coverContents);
                $bookData['cover_path'] = $fileName;
            } catch (\Exception $e) {
                // If cover download fails, continue without it
            }
        }

        Book::create($bookData);

        return redirect()->route('main')->with('success', 'Książka została zaimportowana!');
    }
}
