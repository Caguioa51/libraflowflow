<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }
        $borrowings = Borrowing::with(['book', 'user'])->orderByDesc('created_at')->paginate(10);
        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::where('status', 'available')->get();
        $users = User::all();
        return view('borrowings.create', compact('books', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);

        // Check if self-service is enabled and user is borrowing for themselves
        $isSelfService = SystemSetting::get('self_service_enabled', true) &&
                        $validated['user_id'] == auth()->id() &&
                        !auth()->user()->isAdmin();

        // Check if user can borrow more books
        $maxBooks = SystemSetting::get('max_books_per_user', 3);
        $currentBorrowings = Borrowing::where('user_id', $validated['user_id'])
            ->where('status', 'borrowed')
            ->count();

        if ($currentBorrowings >= $maxBooks) {
            return redirect()->back()->with('error', "You can only borrow up to {$maxBooks} books at a time.");
        }

        // Check if book is available and has copies
        $book = Book::find($validated['book_id']);
        $available = is_null($book->available_quantity) ? ($book->quantity ?? 1) : $book->available_quantity;
        if ($book->status !== 'available' || $available <= 0) {
            return redirect()->back()->with('error', 'This book is not available for borrowing.');
        }

        $borrowingDuration = SystemSetting::get('borrowing_duration_days', 14);

        // Use transaction to ensure borrow and inventory update happen together
        \DB::beginTransaction();
        try {
            $borrowing = Borrowing::create([
                'user_id' => $validated['user_id'],
                'book_id' => $validated['book_id'],
                'borrowed_at' => now(),
                'status' => 'borrowed',
                'due_date' => now()->addDays($borrowingDuration),
            ]);

            // Decrement available_quantity safely
            $newAvailable = max(0, ($available - 1));
            $book->available_quantity = $newAvailable;
            // If no copies remain, mark as borrowed; otherwise remain available
            $book->status = $newAvailable <= 0 ? 'borrowed' : 'available';
            $book->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to borrow book. Please try again.');
        }

        if ($isSelfService) {
            $message = 'Book borrowed successfully! You can manage your borrowings in "My Borrowing History".';
            $redirectRoute = 'borrowings.my_history'; // Redirect to user's borrowing history
        } else {
            // Admin borrowing for another user
            $userName = $validated['user_id'] == auth()->id() ? 'yourself' : User::find($validated['user_id'])->name;
            $message = "Book borrowed successfully for {$userName}! You can view their borrowing history or borrow more books.";
            $redirectRoute = 'admin.users.view_history';
        }

        return redirect()->route($redirectRoute, $validated['user_id'])->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['book', 'user']);
        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }
        return redirect()->route('borrowings.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }
        // Use transaction to mark returned and adjust inventory
        \DB::beginTransaction();
        try {
            $borrowing->update([
                'returned_at' => now(),
                'status' => 'returned',
            ]);

            $book = $borrowing->book;
            $quantity = $book->quantity ?? 1;
            $available = is_null($book->available_quantity) ? 0 : $book->available_quantity;
            $newAvailable = min($quantity, $available + 1);
            $book->available_quantity = $newAvailable;
            // If at least one copy is available, mark status available
            $book->status = $newAvailable > 0 ? 'available' : $book->status;
            $book->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process return. Please try again.');
        }

        return redirect()->route('borrowings.index')->with('success', 'Book returned successfully.');
    }

    /**
     * Mark borrowing as returned (dedicated method for "Mark as Returned" functionality)
     */
    public function markAsReturned(Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }

        if ($borrowing->status === 'returned') {
            return redirect()->back()->with('error', 'This book has already been returned.');
        }

        // Use transaction to mark returned and adjust inventory
        \DB::beginTransaction();
        try {
            $borrowing->update([
                'returned_at' => now(),
                'status' => 'returned',
            ]);

            $book = $borrowing->book;
            $quantity = $book->quantity ?? 1;
            $available = is_null($book->available_quantity) ? 0 : $book->available_quantity;
            $newAvailable = min($quantity, $available + 1);
            $book->available_quantity = $newAvailable;
            // If at least one copy is available, mark status available
            $book->status = $newAvailable > 0 ? 'available' : $book->status;
            $book->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to process return. Please try again.');
        }

        return redirect()->back()->with('success', 'Book marked as returned successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }
        $borrowing->delete();
        return redirect()->route('borrowings.index')->with('success', 'Borrowing record deleted.');
    }

    public function myHistory()
    {
        if (auth()->user()->isAdmin()) {
            // For admins, redirect to admin borrow page instead of dashboard
            return redirect()->route('borrowings.admin_borrow');
        }
        $borrowings = Borrowing::with('book')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('borrowings.my_history', compact('borrowings'));
    }

    public function report()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized.');
        }

        // Basic stats
        $totalBooks = \App\Models\Book::count();
        $borrowedBooks = \App\Models\Book::where('status', 'borrowed')->count();
        $availableBooks = \App\Models\Book::where('status', 'available')->count();

        // Calculate currently borrowed books (books that are actually borrowed, not just status)
        $currentlyBorrowed = Borrowing::where('status', 'borrowed')->count();

        // Get overdue books count
        $overdueBooks = Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->count();

        // Real-time data
        $realTimeData = [
            'today_borrowings' => Borrowing::whereDate('borrowed_at', today())->count(),
            'today_returns' => Borrowing::whereDate('returned_at', today())->count(),
            'active_users_today' => Borrowing::whereDate('borrowed_at', today())
                ->distinct('user_id')
                ->count('user_id'),
            'available_books' => $availableBooks,
            'overdue_books' => $overdueBooks,
        ];

        // Get recent borrowings for the table
        $borrowings = Borrowing::with(['book', 'user'])->orderByDesc('created_at')->paginate(20);

        return view('borrowings.report', compact(
            'totalBooks',
            'borrowedBooks',
            'availableBooks',
            'currentlyBorrowed',
            'overdueBooks',
            'realTimeData',
            'borrowings'
        ));
    }

    public function adminBorrow(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $books = Book::where('status', 'available')
            ->where('available_quantity', '>', 0)
            ->with(['author', 'category'])
            ->orderBy('title')
            ->get();

        $selectedUser = null;
        $userId = $request->query('user_id');

        // Handle POST request for student search
        if ($request->isMethod('post')) {
            $barcode = $request->input('barcode');
            $searchQuery = $request->input('search_query');

            if ($barcode) {
                // Search by barcode
                $foundUser = User::where('barcode', $barcode)->first();
                if ($foundUser) {
                    return redirect()->route('borrowings.admin_borrow', ['user_id' => $foundUser->id])
                        ->with('success', 'Student found via barcode.');
                } else {
                    return redirect()->route('borrowings.admin_borrow')
                        ->with('error', 'No student found with this barcode.')
                        ->withInput();
                }
            } elseif ($searchQuery) {
                // Search by name, email, or student ID
                $foundUser = User::where('student_id', $searchQuery)
                    ->orWhere('email', $searchQuery)
                    ->orWhere('name', 'like', '%' . $searchQuery . '%')
                    ->first();

                if ($foundUser) {
                    return redirect()->route('borrowings.admin_borrow', ['user_id' => $foundUser->id])
                        ->with('success', 'Student found via search.');
                } else {
                    return redirect()->route('borrowings.admin_borrow')
                        ->with('error', 'No student found matching your search.')
                        ->withInput();
                }
            }
        }

        if ($userId) {
            $selectedUser = User::find($userId);
            if (!$selectedUser) {
                return redirect()->route('borrowings.admin_borrow')
                    ->with('error', 'Student not found.');
            }
        }

        return view('borrowings.admin_borrow', compact('books', 'selectedUser'));
    }

    public function adminBarcodeLookup(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'barcode' => 'required|string'
        ]);

        $user = User::where('barcode', $request->barcode)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No student found with this barcode.'
            ]);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'student_id' => $user->student_id,
                'role' => $user->role,
                'profile_photo' => $user->profile_photo_url,
            ]
        ]);
    }

    public function adminUserSearch(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        $query = $request->q;

        // Search for users by student_id, email, or name
        $users = User::where('student_id', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('name', 'like', "%{$query}%")
            ->select('id', 'name', 'email', 'student_id', 'role')
            ->limit(10)
            ->get();

        if ($users->count() === 1) {
            // If only one user found, return it directly
            $user = $users->first();
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'student_id' => $user->student_id,
                'role' => $user->role,
                'profile_photo' => $user->profile_photo_url,
            ]);
        } elseif ($users->count() > 1) {
            // If multiple users found, return list for selection
            return response()->json([
                'multiple' => true,
                'users' => $users->map(function($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'student_id' => $user->student_id,
                        'role' => $user->role,
                        'profile_photo' => $user->profile_photo_url,
                    ];
                })
            ]);
        } else {
            return response()->json([
                'error' => 'No students found matching your search.'
            ], 404);
        }
    }

    // Local-only testing endpoint to allow concurrent HTTP borrow attempts without CSRF.
    // This should only be enabled in local environment for testing.
    public function testingBorrow(Request $request)
    {
        if (!app()->environment('local')) {
            return response()->json(['error' => 'Not available'], 403);
        }
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);

        // Reuse core borrow logic similar to store(), but return JSON and avoid redirects
        $userId = $validated['user_id'];
        $book = Book::find($validated['book_id']);
        $available = is_null($book->available_quantity) ? ($book->quantity ?? 1) : $book->available_quantity;
        if ($book->status !== 'available' || $available <= 0) {
            return response()->json(['success' => false, 'message' => 'Not available'], 409);
        }

        \DB::beginTransaction();
        try {
            $borrowing = Borrowing::create([
                'user_id' => $userId,
                'book_id' => $book->id,
                'borrowed_at' => now(),
                'status' => 'borrowed',
                'due_date' => now()->addDays(SystemSetting::get('borrowing_duration_days', 14)),
            ]);
            $newAvailable = max(0, ($available - 1));
            $book->available_quantity = $newAvailable;
            $book->status = $newAvailable <= 0 ? 'borrowed' : 'available';
            $book->save();
            \DB::commit();
            return response()->json(['success' => true, 'borrowing_id' => $borrowing->id], 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed'], 500);
        }
    }

    // Local-only: allow header-based token auth and bypass CSRF/session for stress testing.
    public function testingBorrowNoCsrf(Request $request)
    {
        if (!app()->environment('local')) {
            return response()->json(['error' => 'Not available'], 403);
        }
        $secret = env('STRESS_TEST_SECRET') ?: 'local-secret-123';
        $provided = $request->header('X-TEST-SECRET');
        if (!$provided || !hash_equals($secret, $provided)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);

        $userId = $validated['user_id'];
        $book = Book::find($validated['book_id']);
        $available = is_null($book->available_quantity) ? ($book->quantity ?? 1) : $book->available_quantity;
        if ($book->status !== 'available' || $available <= 0) {
            return response()->json(['success' => false,
