<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\AstrologerController;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AstrologerSlotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('user')->group(function () {
    // Public routes (no authentication required)
    Route::post('signup', [UserController::class, 'signup']);
    Route::post('login', [UserController::class, 'login']);
    Route::post('send-otp', [UserController::class, 'sendOtp']);
    Route::post('check-otp-status', [UserController::class, 'checkOtpStatus']);
    Route::post('verify-otp', [UserController::class, 'verifyOtp']);

    // Protected routes (authentication required)
    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [UserController::class, 'getProfile']);
        Route::post('profile', [UserController::class, 'updateProfile']);
        Route::post('profile/birth-details', [UserController::class, 'updateBirthDetails']);
        Route::post('profile/current-address', [UserController::class, 'updateCurrentAddress']);
        Route::post('device-token', [UserController::class, 'storeDeviceToken']);
        Route::post('logout', [UserController::class, 'logout']);
        Route::delete('account', [UserController::class, 'deleteAccount']);
    });
});

Route::prefix('locations')->group(function () {
    // Public routes (no authentication required)
    Route::get('countries', [LocationController::class, 'countries']);
    Route::get('states', [LocationController::class, 'states']);
    Route::get('cities', [LocationController::class, 'cities']);
    Route::get('search-city', [LocationController::class, 'searchCity']);
});

Route::prefix('astrologer')->group(function () {
    // Public routes (no authentication required)
    Route::post('login', [AstrologerController::class, 'login']);
    Route::post('send-otp', [AstrologerController::class, 'sendOtp']);
    Route::post('check-otp-status', [AstrologerController::class, 'checkOtpStatus']);
    Route::post('verify-otp', [AstrologerController::class, 'verifyOtp']);
    Route::post('create', [AstrologerController::class, 'create']);

    // Protected routes (authentication required)
    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [AstrologerController::class, 'getProfile']);
        Route::post('profile', [AstrologerController::class, 'updateProfile']);
        Route::post('logout', [AstrologerController::class, 'logout']);
        Route::post('{astrologer}/review', [AstrologerController::class, 'addReview']);
    });
});

Route::prefix('files')->group(function () {
    // Public routes (no authentication required)
    Route::post('upload', [FileUploadController::class, 'uploadFile']);
    Route::post('upload-multiple', [FileUploadController::class, 'uploadMultipleFiles']);
    Route::post('delete', [FileUploadController::class, 'deleteFile']);
    Route::post('delete-multiple', [FileUploadController::class, 'deleteMultipleFiles']);
    Route::get('info', [FileUploadController::class, 'getFileInfo']);
    Route::get('exists', [FileUploadController::class, 'checkFileExists']);
});

Route::get('home', [HomeController::class, 'home']);
Route::get('astrologers', [HomeController::class, 'getAstrologers']);

// Product routes
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/search', [ProductController::class, 'search']);
    Route::get('/price-range', [ProductController::class, 'byPriceRange']);
    Route::get('/in-stock', [ProductController::class, 'inStock']);
    Route::get('/{id}', [ProductController::class, 'show']);
});

// Banner routes
Route::prefix('banners')->group(function () {
    Route::get('/', [BannerController::class, 'index']);
    Route::get('/{id}', [BannerController::class, 'show']);
    Route::post('/click', [BannerController::class, 'click']);
});

// Wallet routes
Route::prefix('wallet')->group(function () {
    // Public routes (no authentication required)
    Route::get('offers', [WalletController::class, 'getOffers']);
    Route::post('calculate-offer', [WalletController::class, 'calculateOffer']);

    // Protected routes (authentication required)
    Route::middleware('auth:api')->group(function () {
        Route::get('balance', [WalletController::class, 'getBalance']);
        Route::post('add-money', [WalletController::class, 'addMoney']);
        Route::get('transactions', [WalletController::class, 'getTransactions']);
        Route::get('transactions/{id}', [WalletController::class, 'getTransaction']);
    });
});

// Appointment routes
Route::prefix('appointments')->group(function () {
    // Public routes (no authentication required)
    Route::get('settings', [AppointmentController::class, 'getSettings']);

    // Protected routes (authentication required)
    Route::middleware('auth:api')->group(function () {
        // User appointment routes
        Route::post('instant', [AppointmentController::class, 'createInstant']);
        Route::post('scheduled', [AppointmentController::class, 'createScheduled']);
        Route::get('user', [AppointmentController::class, 'getUserAppointments']);
        Route::get('user/{id}', [AppointmentController::class, 'show']);
        Route::post('user/{id}/cancel', [AppointmentController::class, 'cancel']);
        Route::post('user/{id}/rate', [AppointmentController::class, 'rate']);

        // Astrologer appointment routes
        Route::get('astrologer', [AppointmentController::class, 'getAstrologerAppointments']);
        Route::post('astrologer/{id}/accept', [AppointmentController::class, 'accept']);
        Route::post('astrologer/{id}/start', [AppointmentController::class, 'start']);
        Route::post('astrologer/{id}/complete', [AppointmentController::class, 'complete']);
        Route::post('astrologer/{id}/cancel', [AppointmentController::class, 'cancel']);
    });
});

// Astrologer slots routes
Route::prefix('astrologer-slots')->group(function () {
    Route::get('{astrologer_id}/available-slots', [AstrologerSlotController::class, 'getAvailableSlots']);
    Route::get('{astrologer_id}/availability', [AstrologerSlotController::class, 'getAvailability']);
    Route::get('{astrologer_id}/schedule', [AstrologerSlotController::class, 'getSchedule']);
    Route::get('{astrologer_id}/pricing', [AstrologerSlotController::class, 'getPricing']);
});


