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


    // untuk pemilihan mentor
    // 1. Halaman Pilih Mentor (GET)
    Route::get('/mentor/assign/{user_package_id}', [MenteeController::class, 'showMentorSelection'])->name('mentor.assign.form');
    
    // 2. Proses Simpan Pilihan Mentor (POST)
    Route::post('/mentor/assign', [MenteeController::class, 'assignMentor'])->name('mentor.assign.store');




    Route::get('/booking/{user_package_id}', [BookingController::class, 'showBookingForm'])->name('sessions.booking.form');


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
    Route::get('/finance', [MentorController::class, 'indexFinance'])->name('finance.index');
    Route::get('/reviews', [MentorController::class, 'indexReviews'])->name('reviews.index');
    
    // ... route mentor lainnya (profil, jadwal)
    Route::resource('sessions', LearningSessionController::class)
         ->names('sessions');

    // Route Schedule Mentor
    Route::get('/schedule', [LearningSessionController::class, 'index'])->name('schedule.index');

    // Route Edit dan Update Profil Mentor
    Route::get('/profile', [MentorController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [MentorController::class, 'updateProfile'])->name('profile.update');
});



    