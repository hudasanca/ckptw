<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(array('before'=>'guest'), function(){
	Route::get('register', array(
		'as' => 'register',
		'uses' => 'UserController@register'
	));
	Route::post('register', [
		'as'=>'user.store',
		'uses'=>'UserController@store'
	]);
	Route::post('register/validate', [
		'as' => 'register.validate',
		'uses'=>'UserController@validate'
	]);
	Route::get('signin', array(
		'as' => 'signin',
		'uses' => 'CkptwController@signinPage'
	));
	Route::post('signin', array(
		'as' => 'auth',
		'before' => 'csrf',
		'uses' => 'CkptwController@signinAuth'
	));
	Route::get('forgot-password', [
		'as' => 'forgot.password',
		'uses' => 'UserController@forgotPassword'
	]);
});

Route::group(array('before'=>'auth'), function(){
	Route::get('signout', ['as'=>'signout',function(){
		Auth::logout();
		return Redirect::route('root');
	}]);

	Route::get('setting', [
		'as' => 'setting',
		'uses' => 'CkptwController@setting'
	]);
	
	Route::put('setting',[
		'as' => 'setting.update',
		'uses' => 'UserController@update'
	]);

	Route::post('poll', [
		'as' => 'poll',
		'uses' => 'UserController@poll'
	]);

	Route::get('get/photos/from/instagram', [
		'as'	=>	'instagram.get.photos',
		'uses'	=>	'CkptwController@getPhotosFromInstagram'
	]);

	Route::get('instagram-connect', [
		'as'	=>	'instagram.connect',
		'uses' => 'CkptwController@instagramConnect'
	]);

	Route::post('registration/resend-email', [
		'as' => 'resend.email',
		'uses' => 'UserController@resendEmail'
	]);

	Route::post('registration/skip', array(
		'as'=>'registration.skip',
		'uses'=>'UserController@skip'
	));

	Route::get('get/notification', [
		'as' => 'notif.read',
		'uses' => 'NotificationController@readNotif'
	]);

	Route::get('get/notifications/all', [
		'as' => 'notif.index',
		'uses' => 'NotificationController@index'
	]);

	Route::get('follow/{username}', [
		'as' => 'follow',
		'uses' => 'UserController@follow'
	]);

	Route::get('unfollow/{username}', [
		'as' => 'unfollow',
		'uses' => 'UserController@unfollow'
	]);

	Route::get('{username}/status/{id}/love', [
		'as' => 'love',
		'uses' => 'StatusController@love'
	]);

	Route::get('{username}/status/{id}/unlove', [
		'as' => 'unlove',
		'uses' => 'StatusController@undoLove'
	]);

	Route::get('{username}/status/{id}/delete/confirm',[
		'as' => '{username}.status.delete.confirm',
		'uses' => 'StatusController@deleteConfirmation'
	]);

	Route::get('{username}/status/{id}/comment/{comment_id}/love',[
		'as' => 'comment.love',
		'uses' => 'CommentController@love'
	]);

	Route::get('{username}/status/{id}/comment/{comment_id}/unlove',[
		'as' => 'comment.unlove',
		'uses' => 'CommentController@undoLove'
	]);

	Route::get('{username}/status/{id}/comment/{comment_id}/delete/confirm',[
		'as' => '{username}.status.comment.delete.confirm',
		'uses' => 'CommentController@deleteConfirmation'
	]);

	Route::get('tes', function(){
		// return View::make('instagram.tes');
		// $arrayStr = [
		// 	'Wahid',
		// 	'Lendis',
		// 	'Huda'
		// ];
		// $out = print_r($arrayStr);
		// return '<pre>'.$out.'</pre>';
	});

});

Route::get('/', [
	'as' => 'root',
	'uses' => 'CkptwController@index'
]);


Route::get('discover', [
	'as' => 'discover',
	'uses' => 'CkptwController@discover'
]);

Route::get('discover/search',[
	'as' => 'discover.search',
	'uses' => 'CkptwController@search'
]);

Route::get('confirmation/{token}',[
	'as' => 'confirmation',
	'uses' => 'UserController@confirm'
]);

Route::get('{username}',[
	'as' => 'user.show',
	'uses' => 'UserController@show'
]);

Route::get('{username}/followers', [
	'as' => '{username}.followers',
	'uses' => 'UserController@followers'
]);

Route::group(['prefix'=>'{username}'], function(){
	Route::resource('status','StatusController');
	Route::resource('status.comment', 'CommentController');
});

Route::group(['prefix'=>'admin/master/whatyoudontrealize','before'=>'admin'], function(){

	Route::get('/', [
		'as'	=>	'admin.master.whatyoudontrealize.index',
		'uses'	=>	'AdminController@index'
	]);

	Route::get('status', [
		'as'	=>	'admin.master.whatyoudontrealize.status.all',
		'uses'	=>	'StatusController@all'
	]);

	Route::get('comment', [
		'as'	=>	'admin.master.whatyoudontrealize.comment.all',
		'uses'	=>	'CommentController@all'
	]);

	Route::resource('user', 'UserController');
	Route::resource('user.status', 'StatusController');
	Route::resource('user.status.comment', 'CommentController');
});