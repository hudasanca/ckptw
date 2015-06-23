<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Client Identifier
	|--------------------------------------------------------------------------
	|
	| Your applications client identifier. Used when generating authentication
	| tokens and to receive your authorization code. This must always be set
	| to fetch data from Instagram.
	|
	*/

	'client_id' => 'a8421d7f19ca4d9abd11774f26493e14',

	/*
	|--------------------------------------------------------------------------
	| Client Secret
	|--------------------------------------------------------------------------
	|
	| Your applications client secret. Used when generating authentication
	| tokens and to receive your authorization code. Leave this NULL if you
	| only are going to fetch public data.
	|
	*/

	'client_secret' => '8680614cdd9f4df192756fdcd410c568',

	/*
	|--------------------------------------------------------------------------
	| Callback URL
	|--------------------------------------------------------------------------
	|
	| Your applications callback url. Used to authorize users and generating
	| tokens. Leave this NULL if you only are going to fetch public data.
	|
	*/

	'callback_url' => 'http://ckptw.com/instagram-connect'

];
