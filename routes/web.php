<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BorrowingController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\DatabaseSeederController;

// ULTRA-SIMPLE ADMIN CREATION ROUTE - WORKS EVEN WITHOUT MIGRATIONS
Route::get('/create-admin-simple', function () {
    try {
        // Connect to database
        $pdo = new PDO("pgsql:host=" . env('DB_HOST') . ";port=" . env('DB_PORT') . ";dbname=" . env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD'));
        
        // Hash password
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        
        // Create admin user with raw SQL
        $sql = "INSERT INTO users (name, email, email_verified_at, password, role, created_at, updated_at) 
                VALUES (:name, :email, NOW(), :password, :role, NOW(), NOW())
                ON CONFLICT (email) DO UPDATE SET 
                name = EXCLUDED.name,
                password = EXCLUDED.password,
                role = EXCLUDED.role,
                updated_at = NOW()";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'name' => 'Administrator',
            'email' => 'admin@libraflow.com',
            'password' => $hashedPassword,
            'role' => 'admin'
        ]);
        
        return "<h1>✅ Admin Created Successfully!</h1>
               <p><strong>Email:</strong> admin@libraflow.com</p>
               <p><strong>Password:</strong> admin123</p>
               <p><a href='/login'>Go to Login Page</a></p>";
               
    } catch (Exception $e) {
        return "<h1>❌ Error Creating Admin</h1>
               <p>Error: " . $e->getMessage() . "</p>
               <p>Please check your database connection and ensure migrations have been run.</p>";
    }
})->name('create-admin-simple');

// DEBUG ROUTE - Test basic functionality
Route::get('/debug-test', function () {
    try {
        // Test basic database connection
        $pdo = new PDO("pgsql:host=" . env('DB_HOST') . ";port=" . env('DB_PORT') . ";dbname=" . env('DB_DATABASE'), env('DB_USERNAME'), env('DB_PASSWORD'));
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $userCount = $stmt->fetch()['count'];
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM books");
        $bookCount = $stmt->fetch()['count'];
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM borrowings");
        $borrowingCount = $stmt->fetch()['count'];
        
        return "<h1>✅ Database Connection Test</h1>
               <p><strong>Users:</strong> {$userCount}</p>
               <p><strong>Books:</strong> {$bookCount}</p>
               <p><strong>Borrowings:</strong> {$borrowingCount}</p>
               <p><strong>Status:</strong> Database is working!</p>";
               
    } catch (Exception $e) {
        return "<h1>❌ Database Connection Error</h1>
               <p>Error: " . $e->getMessage() . "</p>
               <p>Please check your database configuration.</p>";
    }
})->name('debug-test');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return Auth::check()
        ? view('dashboard')
        : redirect()->route('login');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/download-data', [ProfileController::class, 'downloadData'])->name('profile.download_data');
    // QR code feature removed: previously provided a per-user QR code view.

    Route::resource('books', BookController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('borrowings', BorrowingController::class);
    Route::patch('borrowings/{borrowing}/return', [BorrowingController::class, 'update'])->name('borrowings.return');
    
    // FIXED: Both POST and GET routes for mark-as-returned
    Route::post('borrowings/{borrowing}/mark-as-returned', [BorrowingController::class, 'markAsReturned'])->name('borrowings.mark-as-returned');
    Route::get('borrowings/{borrowing}/mark-as-returned', [BorrowingController::class, 'markAsReturned'])->name('borrowings.mark-as-returned-get');
    
    Route::get('/my-borrowings', [BorrowingController::class, 'myHistory'])->name('borrowings.my_history');
    Route::get('/admin/report', [BorrowingController::class, 'report'])->name('borrowings.report');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

    // Book reservation system
    Route::post('/books/{book}/reserve', [BookController::class, 'reserve'])->name('books.reserve');
    Route::delete('/books/{book}/reserve', [BookController::class, 'cancelReservation'])->name('books.cancel_reservation');
    Route::get('/my-reservations', [BookController::class, 'myReservations'])->name('books.my_reservations');

    // Self-service routes
    // Redirect old self-checkout to books page
    Route::get('/self-checkout', function() {
        return redirect()->route('books.index');
    })->name('borrowings.self_checkout');

    Route::post('/borrowings/{borrowing}/renew', [BorrowingController::class, 'renew'])->name('borrowings.renew');
    Route::post('/borrowings/{borrowing}/pay-fine', [BorrowingController::class, 'payFine'])->name('borrowings.pay_fine');
});

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {

    Route::get('/admin/borrow', [\App\Http\Controllers\BorrowingController::class, 'adminBorrow'])->name('borrowings.admin_borrow');
    Route::post('/admin/borrow', [\App\Http\Controllers\BorrowingController::class, 'adminBorrow'])->name('borrowings.admin_borrow.post');
    Route::post('/admin/borrow/barcode-lookup', [\App\Http\Controllers\BorrowingController::class, 'adminBarcodeLookup'])->name('borrowings.admin_barcode_lookup');
    Route::post('/admin/borrow/user-search', [\App\Http\Controllers\BorrowingController::class, 'adminUserSearch'])->name('borrowings.admin_user_search');
    Route::get('/admin/update-fines', [\App\Http\Controllers\BorrowingController::class, 'updateFines'])->name('borrowings.update_fines');
    Route::post('/admin/update-fines', [\App\Http\Controllers\BorrowingController::class, 'updateFines'])->name('borrowings.update_fines.post');
    // Announcements management for admins
    Route::get('/admin/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('admin.announcements.index');
    Route::post('/admin/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('admin.announcements.store');
    // Admin settings (SystemSettingsController expects an admin settings page)
    Route::get('/admin/settings', [\App\Http\Controllers\SystemSettingsController::class, 'index'])->name('admin.settings');
    Route::post('/admin/settings', [\App\Http\Controllers\SystemSettingsController::class, 'update'])->name('admin.settings.update');
    // User management for admins
    Route::get('/admin/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{user}/borrow', [\App\Http\Controllers\Admin\UserManagementController::class, 'borrowForUser'])->name('admin.users.borrow_for_user');
    Route::get('/admin/users/{user}/history', [\App\Http\Controllers\Admin\UserManagementController::class, 'viewHistory'])->name('admin.users.view_history');
    Route::post('/admin/users/update-student-id', [\App\Http\Controllers\Admin\UserManagementController::class, 'updateStudentId'])->name('admin.users.update_student_id');
    Route::post('/admin/users/update-rfid', [\App\Http\Controllers\Admin\UserManagementController::class, 'updateRfid'])->name('admin.users.update_rfid');
    // Barcode management for admins
    Route::get('/admin/barcode-scan', [\App\Http\Controllers\Admin\BarcodeController::class, 'scan'])->name('admin.barcode.scan');
    Route::post('/admin/barcode-lookup', [\App\Http\Controllers\Admin\BarcodeController::class, 'lookup'])->name('admin.barcode.lookup');
    Route::post('/admin/assign-barcode', [\App\Http\Controllers\Admin\BarcodeController::class, 'assign'])->name('admin.barcode.assign');

    // RFID management for admins
    Route::get('/admin/rfid/scan', [\App\Http\Controllers\Admin\RfidController::class, 'scan'])->name('admin.rfid.scan');
    Route::post('/admin/rfid/lookup', [\App\Http\Controllers\Admin\RfidController::class, 'lookup'])->name('admin.rfid.lookup');
    Route::post('/admin/rfid/assign', [\App\Http\Controllers\Admin\RfidController::class, 'assign'])->name('admin.rfid.assign');
});

require __DIR__.'/auth.php';
require __DIR__.'/test.php';

// Testing endpoint (requires authentication and CSRF). Controller still enforces local environment.
Route::middleware('auth')->post('/testing/borrow', [\App\Http\Controllers\BorrowingController::class, 'testingBorrow'])->name('testing.borrow.auth');

// Local-only token-based testing endpoint: ensure API route is also registered so route:list shows it.
// Keep the API-only route in routes/api.php, but register a forwarding route here under the 'api' prefix
// to make sure php artisan route:list --path=api can find it when using the built-in server.
Route::prefix('api')->middleware('api')->group(function () {
    // Ensure this testing route bypasses CSRF middleware (page-expired). It is still
    // guarded inside the controller by app()->environment('local') and X-TEST-SECRET.
    Route::post('/testing/borrow/no-csrf', [\App\Http\Controllers\BorrowingController::class, 'testingBorrowNoCsrf'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
});

// Database Seeder Routes
require __DIR__.'/database-seeder.php';
