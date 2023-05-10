<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContentsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HomeController;
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

Route::get('/about',  [ContentsController::class, 'about'])->name('about');
Route::get('/contact-us',  [ContentsController::class, 'contactUs'])->name('contact-us');
Route::get('/thank-you',  [ContentsController::class, 'thankYou'])->name('thank-you');
Route::get('/return-policy',  [ContentsController::class, 'returnPolicy'])->name('return-policy');
Route::post('contact-store', [ContentsController::class,'contactstore'])->name('contact-store');
Route::get('/shipping-policy',  [ContentsController::class, 'shippingPolicy'])->name('shipping-policy');
Route::get('/privacy-policy',  [ContentsController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-and-conditions',  [ContentsController::class, 'termandConditions'])->name('terms');
Route::get('/product/{product}/{varienId?}', [ProductController::class, 'show'])->name('product.show');
// Route::get('/cart', function () { return view('cart'); })->name('cart');
Route::get('/', function () { return view('index'); })->name('index');
Route::get('/pay', function () { return view('pay'); })->name('pay');
// Review
Route::post('/review/{post}', [ProductController::class,'review'])->name('product.review');
Route::post('/checkout/{product}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/checkout/{id?}', [CheckoutController::class, 'indexNew'])->name('checkout.store');
Route::get('/cart', [CheckoutController::class,'cartIndex'])->name('cart.index');

Route::post('/coupon', [CouponsController::class, 'store'])->name('coupon.store');
Route::delete('/coupon', [CouponsController::class, 'destroy'])->name('coupon.destroy');
Route::post('/continue-payment', [CheckoutController::class, 'continuePayment'])->name('continue.payment');
Route::patch('/checkout/{product}', [CheckoutController::class,'update'])->name('checkout.update');
Route::get('/checkout', [CheckoutController::class, 'checkoutDetails'])->name('checkout.index');
Route::post('checkout-process', \App\Http\Controllers\BuyerController::class)->name('buyer.create');
//Route::post('saveBuyer', \App\Http\Controllers\BuyerController::class,'saveBuyer')->name('save.create');
Route::post('stripeCheckout', \App\Http\Controllers\BuyerController::class)->name('stripe.checkout');
Route::get('/checkout/payment', [CheckoutController::class, 'checkoutPayment'])->name('checkout.payment');
Route::delete('/{product}', [CheckoutController::class,'destroy'])->name('checkout.destroy');
Route::get('thankyou', [CheckoutController::class, 'thankYou'])->name('checkout.thankyou');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
// Route::group(['prefix' => '/checkout'], function() {
//     Route::get('/{id?}', 'CheckoutController@indexNew')->name('checkout.index');
//     Route::post('/{product}', 'CheckoutController@store')->name('checkout.store');
//     Route::patch('/{product}', 'CheckoutController@update')->name('checkout.update');
//     Route::delete('/{product}', 'CheckoutController@destroy')->name('checkout.destroy');
// });
//Route::post('checkout-process', [CheckoutController::class,'checkoutProcess'])->name('checkoutProcess'); 
Route::get('/api-blog',[HomeController::class,'home'])->name('api-blog');
Route::get('/api-cart',[HomeController::class,'cartInfo'])->name('api-cart');
Route::get('thanks', [HomeController::class,'thanks'])->name('thanks');
Route::get('thanks-for-subscribing', [HomeController::class,'subscribe'])->name('thanks-for-subscribing');
//create-order(Drip)
Route::get('create-order', [CheckoutController::class, 'createOrder'])->name('get_order');
Route::post('submit-drip', [CheckoutController::class,'createDrip'])->name('create.drip');
//Route::get('test-drip', [HomeController::class,'dripTest'])->name('drip.test');

