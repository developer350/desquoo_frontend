<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserAddressController;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\GoogleLoginController;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/office-design', [FrontendController::class, 'officeDesign'])->name('office-design');
Route::get('/blog', [FrontendController::class, 'blogs'])->name('blog');
Route::get('/blog/{slug}', [FrontendController::class, 'blogArticle'])->name('blogArticle');
Route::get('/refund-policy', [FrontendController::class, 'refund'])->name('refund');
Route::get('/terms-and-conditions', [FrontendController::class, 'termsandcondition'])->name('termsandcondition');
Route::get('/privacy-policy', [FrontendController::class, 'privacy'])->name('privacy');
Route::get('/bulk-order', [FrontendController::class, 'bulkOrder'])->name('bulkOrder');

// Route::get('/smartdesk-landing', [FrontendController::class, 'smartdesklanding'])->name('smartdesk-landing');

// category routes
Route::get('/product-category/{slug}', [ProductController::class, 'categoryDetail'])->name('category-detail');
Route::get('/get-category', [ProductController::class, 'getCategory'])->name('get-category');
Route::get('/product-category/{slug}/{subcategory}', [ProductController::class, 'subCategoryDetail'])->name('sub-category-detail');

// product routes
Route::get('/shop', [ProductController::class, 'index'])->name('product-listing');
Route::get('/product/{slug}', [ProductController::class, 'productDetail'])->name('product-detail');
Route::get('/get-product-variant', [ProductController::class, 'getProductVariant'])->name('get-product-variant');
Route::get('get-product-glb', [ProductController::class, 'getVariantGlb'])->name('get-variant-glb');
Route::get('/get-variant-description', [ProductController::class, 'getVariantDescription'])->name('get-variant-description');
Route::get('/get-variant-info', [ProductController::class, 'getVariantInfo'])->name('get-variant-info');
Route::post('search-products', [ProductController::class, 'searchProducts'])->name('search-products');

// forms
Route::post('/visit-form', [EnquiryController::class, 'visitForm'])->name('visit-form')->middleware(ProtectAgainstSpam::class);
Route::post('/enquiry-form', [EnquiryController::class, 'enquiryForm'])->name('enquiry-form')->middleware(ProtectAgainstSpam::class);
Route::post('/rate-product', [EnquiryController::class, 'rateProduct'])->name('rate-product')->middleware(ProtectAgainstSpam::class);
Route::post('notify-me', [EnquiryController::class, 'notifyMe'])->name('notify-me')->middleware(ProtectAgainstSpam::class);
Route::post('/blog-comment', [EnquiryController::class, 'blogComment'])->name('blog-comment')->middleware(ProtectAgainstSpam::class);
Route::post('/newsletter-submit', [EnquiryController::class, 'newsletterSubmit'])->name('newsletter-submit')->middleware(ProtectAgainstSpam::class);
Route::post('/question-form', [EnquiryController::class, 'questionForm'])->name('question-form')->middleware(ProtectAgainstSpam::class);

// pincode check
Route::post('/check-pincode', [CheckoutController::class, 'checkPincode'])->name('check-pincode');

// CART
Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
Route::post('custom-add-to-cart', [CartController::class, 'customAddToCart'])->name('custom-add-to-cart');
Route::post('update-cart', [CartController::class, 'updateCart'])->name('update-cart');
Route::post('remove-from-cart', [CartController::class, 'removeFromCart'])->name('remove-from-cart');
Route::post('remove-addon-from-cart', [CartController::class, 'removeAddonFromCart'])->name('remove-addon-from-cart');
Route::get('change-variant', [CartController::class, 'changeVariant'])->name('change-variant');
Route::get('variant-info', [CartController::class, 'getProductVariantInfo'])->name('variant-info');
Route::post('change-cart-variant', [CartController::class, 'changeCartVariant'])->name('change-cart-variant');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginAction'])->name('login.post')->middleware(ProtectAgainstSpam::class);

    // signup
    Route::get('/signup', [AuthController::class, 'signup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signupAction'])->name('signup.post')->middleware(ProtectAgainstSpam::class);

    // otp
    Route::get('/otp', [AuthController::class, 'otp'])->name('otp');
    Route::post('/otp', [AuthController::class, 'otpAction'])->name('verify.otp');
    Route::post('resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');
});

Route::middleware('auth')->group(function () {
    // order routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::get('user-address', [CheckoutController::class, 'userAddress'])->name('user-address');

    Route::post('/place-order', [CheckoutController::class, 'checkout'])->name('place-order');
    Route::post('razorpay-payment', [CheckoutController::class, 'razorpayPayment'])->name('razorpay-payment');
    Route::get('/order-confirmation/{uuid}', [CheckoutController::class, 'orderConfirmation'])->name('order-confirmation');
    Route::get('/order-failed', [CheckoutController::class, 'orderFailed'])->name('order-failed');

    Route::get('/my-account', [DashboardController::class, 'myAccount'])->name('my-account');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    //address
    Route::resource('addresses', UserAddressController::class);
    Route::get('/get-states', [UserAddressController::class, 'getStates'])->name('addresses.getStates');
    Route::get('/get-cities', [UserAddressController::class, 'getCities'])->name('addresses.getCities');
});

// reviews google
Route::get('/auth/google-review', [GoogleLoginController::class, 'login'])->name('google-review.login');
Route::get('/auth/google-review/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('google-review.callback');

Route::get('order-invoice/{uuid}', [DashboardController::class, 'orderInvoice'])->name('order-invoice');
