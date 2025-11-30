    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\DocumentCategoryController;
    use App\Http\Controllers\DocumentController;
    use App\Http\Controllers\ScholarshipCategoryController;
    use App\Http\Controllers\ScholarshipController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\MenteeController;

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
});


// Route Resource Admin (Dilindungi oleh middleware 'role:admin')
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () 
{
    
    // Route Dashboard Admin (dipakai oleh AdminController)
    // URL: /admin/dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard'); 
    
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
    Route::resource('users', UserController::class)->only(['index']);

    // Route Khusus Aksi Mentor
    Route::post('users/mentor/{mentor}/approve', [UserController::class, 'approveMentor'])->name('users.mentor.approve');
    Route::post('users/mentor/{mentor}/reject', [UserController::class, 'rejectMentor'])->name('users.mentor.reject');

    // Route Khusus Aksi User Status
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleUserStatus'])->name('users.toggle-status');

});