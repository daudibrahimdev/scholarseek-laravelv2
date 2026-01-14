    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\DocumentCategoryController;
    use App\Http\Controllers\DocumentController;
    use App\Http\Controllers\ScholarshipCategoryController;
    use App\Http\Controllers\ScholarshipController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\MenteeController;
    use App\Http\Controllers\MentorApplicationController;
    use App\Http\Controllers\MentorController;
    use App\Http\Controllers\LearningSessionController;
    use App\Http\Controllers\BookingController;
    use App\Http\Controllers\CheckoutController;   

    use App\Models\UserPackage;
    

    Route::get('/', function () {
    return view('welcome');
});

// Route utama Jetstream, berfungsi sebagai gerbang otorisasi pasca-login.
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    // Gerbang Redirection Role (Memanggil AdminController@index untuk memilah user)
    // URL: /home
    Route::get('/home', [AdminController::class, 'index'])->name('home');

    // Route Dashboard default Jetstream (pertahankan)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Group Route untuk MENTEE
// URL: /mentee/*
Route::middleware(['auth', 'role:mentee'])->prefix('mentee')->name('mentee.')->group(function () {
    
    // Route Dashboard Mentee (TARGET REDIRECTION)
    // Nama route: mentee.index
    Route::get('/', [MenteeController::class, 'index'])->name('index'); 
    
    // ... route mentee lainnya
    Route::post('/sessions/book', [BookingController::class, 'store'])->name('sessions.book.store');
    // Route untuk halaman daftar paket
    Route::get('/packages', [MenteeController::class, 'packages'])->name('packages.index'); 
    Route::middleware(['auth'])->post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/consultations', [MenteeController::class, 'consultationsIndex'])->name('consultations.index');

    Route::get('/mentors', [MenteeController::class, 'indexMentors'])->name('mentors.index');


    // untuk pemilihan mentor
    // 1. Halaman Pilih Mentor (GET)
    Route::get('/mentor/assign/{user_package_id}', [MenteeController::class, 'showMentorSelection'])->name('mentor.assign.form');
    
    // 2. Proses Simpan Pilihan Mentor (POST)
    Route::post('/mentor/assign', [MenteeController::class, 'assignMentor'])->name('mentor.assign.store');




    Route::get('/booking/{user_package_id}', [BookingController::class, 'showBookingForm'])->name('sessions.booking.form');
    Route::post('/matchmaking/cancel/{id}', [MenteeController::class, 'cancelMatchmaking'])->name('matchmaking.cancel');
    Route::get('/sessions/upcoming', [MenteeController::class, 'upcomingSessions'])->name('sessions.upcoming');
    Route::get('/requests/create', [BookingController::class, 'create'])->name('bookings.create');
    // checkout
    
    Route::get('/checkout/{package_id}', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('/checkout/success/{transaction_id}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/confirm/{transaction_id}', [CheckoutController::class, 'confirmPayment'])->name('checkout.confirm');

    // untuk history
    // Contoh di web.php
    Route::get('/sessions/history', [MenteeController::class, 'history'])->name('sessions.history');
    Route::post('/sessions/history/{id}/hide', [MenteeController::class, 'hide'])->name('sessions.hide');

    // history transaksi
    Route::get('/transactions', [MenteeController::class, 'transactionHistory'])->name('transactions.index');
    Route::get('/transactions/{id}/invoice', [MenteeController::class, 'showInvoice'])->name('transactions.invoice');

    // cancel transaksi
    Route::post('/checkout/cancel/{transaction_id}', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

    // Rating
    Route::post('/reviews/store', [MenteeController::class, 'storeReview'])->name('reviews.store');

    // all about profile
    Route::get('/profile/edit', [MenteeController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [MenteeController::class, 'updateProfile'])->name('profile.update');

    // SCHOLARSHIPSS
    Route::get('/scholarships', [MenteeController::class, 'scholarshipIndex'])->name('scholarships.index');


    // Route ini akan menampilkan FORM pengajuan permintaan
Route::get('/requests/{userPackageId}/create', [SessionRequestController::class, 'create'])->name('session.request.create');

// Route ini akan menerima data POST dari form permintaan
Route::post('/requests', [SessionRequestController::class, 'store'])->name('session.request.store');
});


// Route Resource Admin (Dilindungi oleh middleware 'role:admin')
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () 
{
    
    // Route Dashboard Admin (dipakai oleh AdminController)
    // URL: /admin/dashboard : dikoment dulu supaya tidak bentrok dengan jetstream
    // Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard'); 
    
    // Route Resource untuk Kategori Dokumen
    // URL: /admin/document-categories
    Route::resource('document-categories', DocumentCategoryController::class)
         ->names('document.categories'); 

    // Route Resource untuk Dokume
    // url: /admin/documents
         Route::resource('documents', DocumentController::class)
         ->names('documents');

    // Route Resource untuk Kategori Beasiswa
    // URL: /admin/scholarship-categories
    Route::resource('scholarship-categories', ScholarshipCategoryController::class)
         ->names('scholarship.categories');

    // Route Resource untuk Beasiswa Utama
    // URL: /admin/scholarships
    Route::resource('scholarships', ScholarshipController::class)
         ->names('scholarship');

    // Route Resource untuk Pengguna (Hanya index)
    Route::resource('users', UserController::class)->only(['index'])->names('users');

    // Route Khusus Aksi Mentor
    Route::post('users/mentor/{mentor}/approve', [UserController::class, 'approveMentor'])->name('users.mentor.approve');
    Route::post('users/mentor/{mentor}/reject', [UserController::class, 'rejectMentor'])->name('users.mentor.reject');

    // Route Khusus Aksi User Status
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleUserStatus'])->name('users.toggle-status');

    // KHUSUS KONSULTASI
    

});

  


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified', // <-- Memastikan email sudah diverifikasi
])->group(function () {
    // ... /home, /dashboard, dll.

    // Route Pendaftaran Mentor
    Route::get('/mentor/register', [MentorApplicationController::class, 'showForm'])->name('mentor.register.form');
    Route::post('/mentor/register', [MentorApplicationController::class, 'store'])->name('mentor.register.store');
});



Route::middleware(['auth', 'role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
    
    // Route Dashboard Mentor (TARGET REDIRECTION)
    // Nama route: mentor.dashboard.index
    Route::get('/dashboard', [MentorController::class, 'index'])->name('dashboard.index'); 
    
    // debugging route daftar mentee
    Route::get('/mentees', [MentorController::class, 'listAssignedMentees'])->name('mentees.index');
    // untuk reject dan approve order mentee
    Route::post('/mentees/{id}/approve', [MentorController::class, 'approveMentee'])->name('mentees.approve');
    Route::post('/mentees/{id}/reject', [MentorController::class, 'rejectMentee'])->name('mentees.reject');
    Route::get('/finance', [MentorController::class, 'indexFinance'])->name('finance.index');
    Route::get('/reviews', [MentorController::class, 'indexReviews'])->name('reviews.index');
    
    Route::resource('sessions', LearningSessionController::class)
         ->names('sessions');

    // Route Schedule Mentor
    Route::get('/schedule', [MentorController::class, 'indexSchedule'])->name('schedule.index');
    Route::post('/sessions/{id}/cancel', [MentorController::class, 'cancelSession'])->name('sessions.cancel');
    Route::get('/sessions/history', [MentorController::class, 'historySchedule'])->name('sessions.history');

    // Route Edit dan Update Profil Mentor
    Route::get('/profile', [MentorController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [MentorController::class, 'updateProfile'])->name('profile.update');

    // Halaman daftar mentee yang mencari mentor
    Route::get('/matchmaking', [MentorController::class, 'matchmakingIndex'])->name('matchmaking.index');
    
    // Aksi untuk mengambil/klaim mentee tersebut
    Route::post('/matchmaking/{id}/claim', [MentorController::class, 'claimMentee'])->name('matchmaking.claim');

});



    