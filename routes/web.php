<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthOtpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});




// Route::middleware('auth:admin')->group(function () {
//     Route::get('/admin/applicationrequest', [AdminController::class, 'applicationRequest']);
//     Route::get('/admin/type', [AdminController::class, 'getType']);
//     Route::get('/admin/issues', [AdminController::class, 'list']);
//     Route::get('/admin/details/{id}', [AdminController::class, 'getDetails']);
//     Route::get('/admin/dashboard', [AdminController::class, 'getDashboard']);
//     Route::get('/admin/profile/{id}', [AdminController::class, 'getProfile']);
//     Route::get('/admin/delete/{id}', [AdminController::class, 'deleteComplaint']);
//     Route::get('/admin/imageDelete', [AdminController::class, 'deleteImage']);
//     Route::post('/admin/details/{id}', [AdminController::class, 'updateDetails']);
//     Route::post('/admin/applicationrequest/{id}', [AdminController::class, 'updateApplicationRequest']);
//     Route::post('/admin/type', [AdminController::class, 'addType']);
//     Route::post('/{issueid}/addcomment', [AdminController::class, 'addComment']);
//     Route::post('/{issueid}/{commentid}/{userid}/replycomment', [AdminController::class, 'replyComment']);
// });

//Route::get('/admin/logout', [AdminController::class, 'logout']);


Route::middleware('auth')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('issues', IssueController::class);
    //Route::get('/issues', [ComplaintController::class, 'getComplaintsByUser']);
    //Route::get('/issue/{id}', [ComplaintController::class, 'show']);
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    //Route::get('/profile/{id}', [UserController::class, 'profile']);
    //Route::post('/issue', [ComplaintController::class, 'store']);
    //Route::post('/issues/create', [ComplaintController::class, 'create'])->name('issues.create');
    //Route::get('/issues/{id}', [ComplaintController::class, 'details'])->name('issues.details');
    Route::get('/issues/{id}/comments', [CommentController::class, 'index']);
    Route::post('/issues/{id}/comments', [CommentController::class, 'store']);
    // Comments
    Route::post('/issues/{id}/comments', [CommentController::class, 'addComment'])->name('comments.addComment');

    Route::get('/issues/{id}/comments/{commentId}/reply', [CommentController::class, 'showReply'])->name('comments.showReply');
    Route::post('/issues/{id}/comments/{commentId}/reply', [CommentController::class, 'reply'])->name('comments.reply');

});

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/signup', [AuthController::class, 'signupForm'])->name('auth.signup.form');
Route::post('/signup', [AuthController::class, 'signup'])->name('auth.signup');





//login
// Route::get('/login', function() {
//     return view('citizen.login');
// })->name('otp.login');

//Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Route::get('/signup', function() {
//     return view('citizen.signup');
// })->name('signup');

// Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');

Route::get('/verify', function() {
    return view('citizen.verify');
})->name('verify');

Route::post('/verify', [AuthController::class, 'verify'])->name('verify.post');

Route::get('/forgot-password', function() {
    return view('citizen.forget-password');
})->name('password.forgot');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.forgot.post');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/change-password', [App\Http\Controllers\HomeController::class, 'changePassword'])->name('change-password');
Route::post('/change-password', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('update-password');

Route::controller(AuthOtpController::class)->group(function(){
    Route::get('/otp/login', 'login')->name('otp.login');
    Route::post('/otp/generate', 'generate')->name('otp.generate');
    Route::get('/otp/verification/{user_id}', 'verification')->name('otp.verification');
    Route::post('/otp/login', 'loginWithOtp')->name('otp.getlogin');

    // Add this route for registering with OTP
    Route::get('/otp/registration-verification/{user_id}', [AuthOtpController::class, 'registrationVerification'])->name('otp.registration.verification');
    Route::post('/otp/register', [AuthOtpController::class, 'registerWithOtp'])->name('otp.register');
});


