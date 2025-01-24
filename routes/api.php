<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\MobileController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\JwtTokenAuth;
use Illuminate\Support\Facades\Route;

Route::post('/register', [MobileController::class, 'register']);
Route::post('/login', [MobileController::class, 'login']);
Route::post('/relogin', action: [MobileController::class, 'relogin']);
Route::post('/send-otp', [MobileController::class, 'sendOtp']);
Route::post('/upload-image', [MobileController::class, 'uploadImage']);
Route::get('/public-complaints', [MobileController::class, 'allPublicComplaints']);

// Route::middleware(JwtTokenAuth::class)->group(function () {

Route::get('/token-validate', function () { });

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'apiIndex']); // List all categories (API)
    Route::post('/', [CategoryController::class, 'apiStore']); // Store new category (API)
    Route::get('/{category}', [CategoryController::class, 'apiShow']); // Show specific category details (API)
    Route::put('/{category}', [CategoryController::class, 'apiUpdate']); // Update category (API)
    Route::delete('/{category}', [CategoryController::class, 'apiDestroy']); // Delete category (API)
});
Route::prefix('issues')->group(function () {
    Route::get('/', [IssueController::class, 'listIssues']); // Store new category (API)    
    Route::get('/count', [IssueController::class, 'getIssueCount']); // Store new category (API)    
    Route::post('/list', [IssueController::class, 'listIssuesWithFilter']); // Store new category (API)    
    Route::get('/{issue_id}', [IssueController::class, 'listIssueImage']); // Store new category (API)    
    Route::post('/', [IssueController::class, 'createIssue']); // Store new category (API)    
    Route::post('/image', [IssueController::class, 'createIssueWithImage']); // Store new category (API)    
    Route::post('/comments', [CommentController::class, 'getCommentsByIssueId']); // Store new category (API)    
    Route::post('/comments/create', [CommentController::class, 'addCommentByIssueId']); // Store new category (API)    
});
// });



// Route::get('/', [CategoryController::class, 'index']);