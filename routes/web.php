<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\AstrologerController;
use App\Http\Controllers\Admin\AstrologerSkillController;
use App\Http\Controllers\Admin\AstrologerLanguageController;
use App\Http\Controllers\Admin\AstrologerAvailabilityController;
use App\Http\Controllers\Admin\AstrologerPricingController;
use App\Http\Controllers\Admin\AstrologerDocumentController;
use App\Http\Controllers\Admin\AstrologerBankDetailController;
use App\Http\Controllers\Admin\AstrologerReviewController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\WalletOfferController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\AppointmentSettingController;
use App\Http\Controllers\Admin\ProductGalleryController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\PageController as ControllersPageController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    // Guest routes (login) - only accessible to non-authenticated users
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');
    });

    // Protected admin routes - require authentication and admin privileges
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/example', [AdminController::class, 'example'])->name('admin.example');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

        Route::match(['get', 'post'], 'users/list', [UserController::class, 'list'])->name('admin.users.list');
        Route::get('users/trashed', [UserController::class, 'trashed'])->name('admin.users.trashed');
        Route::get('users/trashed-list', [UserController::class, 'trashedList'])->name('admin.users.trashed.list');
        Route::delete('users/{id}/force', [UserController::class, 'forceDestroy'])->name('admin.users.forceDestroy');
        Route::put('users/{user}/status', [UserController::class, 'toggleStatus'])->name('admin.users.status');
        Route::resource('users', UserController::class, [
            'as' => 'admin'
        ]);

        // Admin Astrologer Management
        Route::match(['get', 'post'], 'astrologers/list', [AstrologerController::class, 'list'])->name('admin.astrologers.list');
        Route::get('astrologers/trashed', [AstrologerController::class, 'trashed'])->name('admin.astrologers.trashed');
        Route::get('astrologers/trashed-list', [AstrologerController::class, 'trashedList'])->name('admin.astrologers.trashed.list');
        Route::delete('astrologers/{id}/force', [AstrologerController::class, 'forceDestroy'])->name('admin.astrologers.forceDestroy');

        Route::resource('astrologers', AstrologerController::class, [
            'as' => 'admin'
        ]);
        Route::resource('astrologers.skills', AstrologerSkillController::class, [
            'as' => 'admin'
        ]);
        Route::resource('astrologers.languages', AstrologerLanguageController::class, [
            'as' => 'admin'
        ]);
        Route::resource('astrologers.availability', AstrologerAvailabilityController::class, [
            'as' => 'admin'
        ]);
        Route::resource('astrologers.pricing', AstrologerPricingController::class, [
            'as' => 'admin'
        ]);
        Route::resource('astrologers.documents', AstrologerDocumentController::class, [
            'as' => 'admin'
        ]);
        Route::resource('astrologers.bank-details', AstrologerBankDetailController::class, [
            'as' => 'admin'
        ]);
        Route::resource('astrologers.reviews', AstrologerReviewController::class, [
            'as' => 'admin'
        ])->only(['index', 'show']);
        Route::post('astrologers/{astrologer}/services', [AstrologerController::class, 'updateServices'])->name('admin.astrologers.services.update');

        // Admin Category Management
        Route::match(['get', 'post'], 'categories/list', [CategoryController::class, 'list'])->name('admin.categories.list');
        Route::resource('categories', CategoryController::class, [
            'as' => 'admin'
        ]);

        // Admin Pages Management
        Route::resource('pages', PageController::class, [
            'as' => 'admin'
        ]);
        Route::put('pages/{page}/toggle-status', [PageController::class, 'toggleStatus'])->name('admin.pages.toggle-status');
        Route::post('pages/create-defaults', [PageController::class, 'createDefaults'])->name('admin.pages.create-defaults');

        Route::match(['get', 'post'], 'banners/list', [BannerController::class, 'list'])->name('admin.banners.list');
        Route::match(['get', 'post'], 'wallet-offers/list', [WalletOfferController::class, 'list'])->name('admin.wallet-offers.list');

        // Admin Wallet Offers
        Route::resource('wallet-offers', WalletOfferController::class, [
            'as' => 'admin'
        ]);
        Route::post('wallet-offers/{wallet_offer}/toggle-status', [WalletOfferController::class, 'toggleStatus'])->name('admin.wallet-offers.toggle-status');

        // Admin Banner Management
        Route::resource('banners', BannerController::class, [
            'as' => 'admin'
        ]);

        // Admin Astrologer Reviews
        Route::get('astrologer-reviews', [AstrologerReviewController::class, 'index'])->name('admin.astrologer_reviews.index');
        Route::get('astrologer-reviews/create', [AstrologerReviewController::class, 'create'])->name('admin.astrologer_reviews.create');
        Route::post('astrologer-reviews', [AstrologerReviewController::class, 'store'])->name('admin.astrologer_reviews.store');
        Route::delete('astrologer-reviews/{id}', [AstrologerReviewController::class, 'destroy'])->name('admin.astrologer_reviews.destroy');
        Route::get('astrologer-reviews/list', [AstrologerReviewController::class, 'list'])->name('admin.astrologer_reviews.list');
        Route::get('astrologer-reviews/{id}/edit', [AstrologerReviewController::class, 'edit'])->name('admin.astrologer_reviews.edit');
        Route::patch('astrologer-reviews/{id}', [AstrologerReviewController::class, 'update'])->name('admin.astrologer_reviews.update');

        // Admin Product Management
        Route::match(['get', 'post'], 'products/list', [ProductController::class, 'list'])->name('admin.products.list');
        Route::resource('products', ProductController::class, [
            'as' => 'admin'
        ]);

        // Admin Product Gallery Management
        Route::get('products/{product}/gallery', [ProductGalleryController::class, 'index'])->name('admin.products.gallery.index');
        Route::post('products/{product}/gallery', [ProductGalleryController::class, 'store'])->name('admin.products.gallery.store');
        Route::delete('products/{product}/gallery/{image}', [ProductGalleryController::class, 'destroy'])->name('admin.products.gallery.destroy');
        Route::get('products/{product}/gallery/images', [ProductGalleryController::class, 'getImages'])->name('admin.products.gallery.images');

        // Admin Video Management
        Route::match(['get', 'post'], 'videos/list', [VideoController::class, 'list'])->name('admin.videos.list');
        Route::resource('videos', VideoController::class, [
            'as' => 'admin'
        ]);

        // Admin Appointment Management
        Route::get('appointments', [AppointmentController::class, 'index'])->name('admin.appointments.index');
        Route::match(['get', 'post'], 'appointments/list', [AppointmentController::class, 'list'])->name('admin.appointments.list');
        Route::get('appointments/statistics', [AppointmentController::class, 'statistics'])->name('admin.appointments.statistics');
        Route::get('appointments/export', [AppointmentController::class, 'export'])->name('admin.appointments.export');
        Route::get('appointments/{id}', [AppointmentController::class, 'show'])->name('admin.appointments.show');
        Route::post('appointments/{id}/status', [AppointmentController::class, 'updateStatus'])->name('admin.appointments.updateStatus');
        Route::post('appointments/{id}/assign-astrologer', [AppointmentController::class, 'assignAstrologer'])->name('admin.appointments.assignAstrologer');
        Route::delete('appointments/{id}', [AppointmentController::class, 'destroy'])->name('admin.appointments.destroy');

        // Admin Appointment Settings
        Route::get('appointment-settings', [AppointmentSettingController::class, 'index'])->name('admin.appointment-settings.index');
        Route::put('appointment-settings', [AppointmentSettingController::class, 'update'])->name('admin.appointment-settings.update');
        Route::get('appointment-settings/reset', [AppointmentSettingController::class, 'reset'])->name('admin.appointment-settings.reset');
        Route::post('appointment-settings/{id}/toggle', [AppointmentSettingController::class, 'toggle'])->name('admin.appointment-settings.toggle');

    });
});

// Log Viewer Routes (Public - No Authentication Required)
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');

// Public Pages Routes (No Authentication Required)
Route::get('page/{slug}', [ControllersPageController::class, 'show'])->name('page.show');
Route::get('terms', [ControllersPageController::class, 'terms'])->name('page.terms');
Route::get('privacy', [ControllersPageController::class, 'privacy'])->name('page.privacy');
Route::get('refund', [ControllersPageController::class, 'refund'])->name('page.refund');
