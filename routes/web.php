    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\DocumentCategoryController;

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
});