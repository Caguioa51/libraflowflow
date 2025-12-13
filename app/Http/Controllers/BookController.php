<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Author;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Book::with(['category', 'author'])
            ->whereNotNull('author_id')
            ->whereNotNull('category_id');

        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhereHas('author', function($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%");
                  })
                  ->orWhere('genre', 'like', "%$search%");
            });
        }

        if ($categoryId = request('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $books = $query->paginate(12); // Increased per page for better grid layout
        $categories = Category::has('books')->withCount('books')->get();

        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')->with('error', 'Unauthorized.');
        }
        $categories = Category::all();
        $authors = Author::all();
        return view('books.create', compact('categories', 'authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')->with('error', 'Unauthorized.');
        }

        // Handle new author creation
        $authorId = $request->author_id;
        if ($request->filled('new_author') && !$request->filled('author_id')) {
            $author = Author::create(['name' => $request->new_author]);
            $authorId = $author->id;
        }

        // Handle new category creation
        $categoryId = $request->category_id;
        if ($request->filled('new_category') && !$request->filled('category_id')) {
            $category = Category::create(['name' => $request->new_category]);
            $categoryId = $category->id;
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'available_quantity' => 'nullable|integer|min:0',
        ]);

        // Add the resolved IDs
        $validated['author_id'] = $authorId;
        $validated['category_id'] = $categoryId;

        // Validate that we have valid IDs
        if (!$validated['author_id'] || !$validated['category_id']) {
            return redirect()->back()->withErrors(['author' => 'Please select an existing author/category or enter a new one.'])->withInput();
        }

        // Enforce invariant: available_quantity equals quantity on create
        $validated['available_quantity'] = $validated['quantity'];

        Book::create($validated);
        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load(['category', 'author']);
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')->with('error', 'Unauthorized.');
        }
        $categories = Category::all();
        $authors = Author::all();
        return view('books.edit', compact('book', 'categories', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')->with('error', 'Unauthorized.');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'genre' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'available_quantity' => 'nullable|integer|min:0',
        ]);
        if (isset($validated['quantity']) && !isset($validated['available_quantity'])) {
            // keep existing available quantity but cap at new quantity
            $validated['available_quantity'] = min($book->available_quantity ?? $validated['quantity'], $validated['quantity']);
        }
        if (isset($validated['available_quantity']) && isset($validated['quantity']) && $validated['available_quantity'] > $validated['quantity']) {
            $validated['available_quantity'] = $validated['quantity'];
        }
        $book->update($validated);
        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('books.index')->with('error', 'Unauthorized.');
        }
        $book->delete();
        return redirect()->route('books.index')->with('book_deleted', 'Book "' . $book->title . '" has been permanently deleted.');
    }

    /**
     * Reserve a book for the authenticated user.
     */
    public function reserve(Book $book)
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Admins cannot reserve books.');
        }

        // Check if user already has this book reserved
        $existingReservation = \App\Models\BookReservation::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->where('status', 'active')
            ->exists();

        if ($existingReservation) {
            return redirect()->back()->with('error', 'You have already reserved this book.');
        }

        // Check if book is available for reservation
        if ($book->available_quantity <= 0) {
            return redirect()->back()->with('error', 'This book is not available for reservation.');
        }

        // Check user's reservation limit
        $userReservations = \App\Models\BookReservation::where('user_id', auth()->id())
            ->where('status', 'active')
            ->count();

        $maxReservations = auth()->user()->isStudent() ? 2 : 3;
        if ($userReservations >= $maxReservations) {
            return redirect()->back()->with('error', "You can only have {$maxReservations} active reservations at a time.");
        }

        // Create reservation
        \App\Models\BookReservation::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'reserved_at' => now(),
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Book reserved successfully! You will be notified when it becomes available.');
    }

    /**
     * Cancel a book reservation.
     */
    public function cancelReservation(Book $book)
    {
        $reservation = \App\Models\BookReservation::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->where('status', 'active')
            ->first();

        if (!$reservation) {
            return redirect()->back()->with('error', 'No active reservation found for this book.');
        }

        $reservation->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Book reservation cancelled successfully.');
    }

    /**
     * Display user's reservations.
     */
    public function myReservations()
    {
        $reservations = \App\Models\BookReservation::with(['book', 'book.category', 'book.author'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('books.reservations', compact('reservations'));
    }
}
