<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SignerController;

Route::redirect('/', '/login');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
});
Route::get('/verify-signature', [DocumentController::class, 'verify'])->name('verify.signature');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DocumentController::class, 'dashboard'])->name('dashboard');
    
    // Signer Management
    Route::resource('signer', SignerController::class);
    
    Route::get('/document/create', [DocumentController::class, 'create'])->name('document.create');
    Route::post('/document/upload', [DocumentController::class, 'upload'])->name('document.upload');
    Route::get('/document/{document}/position', [DocumentController::class, 'position'])->name('document.position');
    Route::post('/document/{document}/position', [DocumentController::class, 'savePosition'])->name('document.save-position');
    Route::post('/document/{document}/stamp', [DocumentController::class, 'stamp'])->name('document.stamp');
    Route::get('/document/{document}/download', [DocumentController::class, 'download'])->name('document.download');
    Route::delete('/document/{document}', [DocumentController::class, 'destroy'])->name('document.destroy');
});