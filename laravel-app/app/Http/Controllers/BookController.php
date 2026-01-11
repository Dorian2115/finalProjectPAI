<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(): View
    {
        $books = Book::where('user_id', auth()->id())->latest()->get();
        return view('books.index', compact('books'));
    }

    public function create(): View
    {
        return view('books.create');
    }

    public function store(StoreBookRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('book-covers', 'public');
            $validated['cover_path'] = $path;
        }

        $validated['user_id'] = auth()->id();
        Book::create($validated);

        return redirect()->route('main')->with('success', 'Książka została dodana!');
    }

    public function edit(Book $book): View
    {
        if ($book->user_id !== auth()->id()) {
            abort(403);
        }
        return view('books.edit', compact('book'));
    }

    public function update(StoreBookRequest $request, Book $book): RedirectResponse
    {
        if ($book->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('book-covers', 'public');
            $validated['cover_path'] = $path;
        }

        $book->update($validated);

        return redirect()->route('main')->with('success', 'Książka została zaktualizowana!');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->user_id !== auth()->id()) {
            abort(403);
        }

        $book->delete();

        return redirect()->route('main')->with('success', 'Książka została usunięta!');
    }

    public function toggleFavorite(Book $book): RedirectResponse
    {
        if ($book->user_id !== auth()->id()) {
            abort(403);
        }

        $book->update(['is_favorite' => !$book->is_favorite]);

        return back();
    }
}
