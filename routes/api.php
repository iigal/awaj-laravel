use App\Http\Controllers\MobileController;

Route::post('/register', [MobileController::class, 'register']);
Route::post('/login', [MobileController::class, 'login']);
Route::post('/send-otp', [MobileController::class, 'sendOtp']);
Route::post('/upload-image', [MobileController::class, 'uploadImage']);
Route::get('/public-complaints', [MobileController::class, 'allPublicComplaints']);
