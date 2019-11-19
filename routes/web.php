<?php

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
Route::get('/', 'HomeController@index')->name('dashboard');
// Route::get('a', function(){
// 	return view('unused.welcome');
// });

Route::get('markAsRead', function(){
	auth()->user()->unreadNotifications->markAsRead();
})->name('notification.read');

Route::get('signin', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('signin', 'Auth\LoginController@login');
Route::post('signout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('signup', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Auth::routes(['verify' => true]);

Route::group(['middleware' => 'auth'], function(){
	Route::get('email', 'UserController@emailEdit')->name('email.edit');
	Route::put('email', 'UserController@emailUpdate')->name('email.update');
});

Route::group(['middleware' => 'verified'], function(){
	// Route::get('lapangan', 'HomeController@auth')->name('auth.dashboard');

	Route::get('user/profile', 'UserController@profile')->name('user.profile');
	Route::put('user/profile', 'UserController@updateprofile')->name('user.profile.update');
	Route::get('user/password/reset', 'UserController@passwordreset')->name('user.password.reset');
	Route::put('user/password/reset', 'UserController@passwordupdate')->name('user.password.update');
	
	Route::group(['prefix' => 'manage'], function(){
		Route::resource('CS', 'ManageCSController')->parameters(['CS' => 'CS']);
		Route::group(['prefix' =>  'CS/{CS}'], function(){
			Route::resource('service', 'CustomerServiceController')->parameters(['lik' => 'service'])->except([
				'index', 'edit', 'update', 'show'
			]);
		});
	});

	// New
	Route::group(['prefix' => 'pembayaran'], function(){
		Route::get('/', 'PembayaranController@index')->name('PO.pembayaran.index');
		Route::get('{order}', 'PembayaranController@show')->name('PO.pembayaran.show');
		Route::get('{order}/payment', 'PembayaranController@edit')->name('PO.pembayaran.edit');
		Route::put('{order}', 'PembayaranController@update')->name('PO.pembayaran.update');
		Route::delete('{order}/cancel', 'PembayaranController@cancel')->name('PO.pembayaran.cancel');
	});
	//

	Route::resource('lapangan', 'LapanganController');

	Route::group(['prefix' =>  'lapangan/{lapangan}'], function(){
		Route::delete('image/remove', 'LapanganController@imageRemove')->name('lapangan.image.remove');

		Route::resource('olahraga', 'LapanganOlahragaController')->except(
			['index']
		);

		Route::group(['prefix' => 'pemesanan'], function(){
			Route::get('/', 'DaftarPemesananController@index')->name('pemesanan.index');
		});

		Route::group(['prefix' => 'olahraga/{olahraga}'], function(){
			Route::delete('image/remove', 'LapanganOlahragaController@imageRemove')->name('olahraga.image.remove');
			Route::get('discount', 'LapanganOlahragaController@discountManage')->name('olahraga.discount.manage');
			Route::get('discount/create', 'LapanganOlahragaController@discountAdd')->name('olahraga.discount.add');
			Route::post('discount', 'LapanganOlahragaController@discountStore')->name('olahraga.discount.store');
			Route::get('discount/{discount}', 'LapanganOlahragaController@discountEdit')->name('olahraga.discount.edit');
			Route::put('discount/{discount}', 'LapanganOlahragaController@discountUpdate')->name('olahraga.discount.update');
			Route::delete('discount/{discount}', 'LapanganOlahragaController@discountRemove')->name('olahraga.discount.destroy');
			Route::get('review', 'LapanganOlahragaController@review')->name('olahraga.review.index');
			Route::post('review', 'ReviewController@add')->name('olahraga.review.add');
			
			//New
			Route::group(['prefix' => 'pembayaran'], function(){
				Route::get('/', 'DaftarPembayaranController@index')->name('pembayaran.index');
				Route::get('{order}', 'DaftarPembayaranController@show')->name('pembayaran.show');
				Route::put('{order}/accept', 'DaftarPembayaranController@accept')->name('pembayaran.accept');
				Route::put('{order}/deny', 'DaftarPembayaranController@deny')->name('pembayaran.deny');
			});
			Route::post('review/{review}', 'ReviewController@reply')->name('olahraga.review.reply');
			Route::get('review/{review}', 'ReviewController@edit')->name('olahraga.review.edit');
			Route::put('review/{review}', 'ReviewController@update')->name('olahraga.review.update');
			//
			Route::resource('order', 'OrderController')->only(
				['create', 'store']
			);
		});

	});
});