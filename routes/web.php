<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceArchiveController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicesAttachmentsController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoicesReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified', 'checkStatus'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('invoices', InvoiceController::class);
Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);
Route::resource('InvoicesAttachments', InvoicesAttachmentsController::class);
Route::resource('Archive', InvoiceArchiveController::class);

Route::controller(InvoiceController::class)->group(function () {
    Route::get('/edit_invoice/{id}', 'edit');
    Route::get('Status_show/{id}', 'show')->name('Status_show');
    Route::post('status_update/{id}', 'status_update')->name('status_update');
    Route::get('invoice_paid', 'invoice_paid')->name('invoice_paid');
    Route::get('invoice_unpaid', 'invoice_unpaid')->name('invoice_unpaid');
    Route::get('invoice_partial', 'invoice_partial')->name('invoice_partial');
    Route::get('print_invoice/{id}',  'print_invoice');
    Route::get('export_invoices', 'export');
    Route::get('markAsReadAll', 'markAsReadAll')->name('markAsReadAll'); //read all notifications
    Route::get('/category/{id}', 'getproducts');
});

Route::controller(InvoicesDetailsController::class)->group(function () {
    Route::get('/InvoicesDetails/{id}', 'edit');
    Route::post('readNotify',  'readNotify')->name('readNotify');
    Route::get('view_file/{invoice_number}/{file_name}',  'open_file');
    Route::get('download/{invoice_number}/{file_name}', 'get_file');
    Route::post('delete_file', 'destroy')->name('delete_file');
});

Route::controller(InvoicesReportController::class)->group(function () {
    Route::get('invoices_report',  'index')->name('invoices_report');
    Route::post('search_invoices', 'search_invoices')->name('search_invoices');
});

Route::controller(CustomerReportController::class)->group(function () {
    Route::get('customers_report', 'index')->name('customers_report');
    Route::post('search_customers', 'search_customers')->name('search_customers');
});

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});

require __DIR__ . '/auth.php';
Route::get('/{page}', [AdminController::class, 'index']);
