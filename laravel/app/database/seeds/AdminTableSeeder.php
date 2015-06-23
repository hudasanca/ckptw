<?php
class AdminTableSeeder extends seeder{
	public function run(){
		DB::table('admin')->delete();
		Admin::create([
			'name'		=>	'Nurul Huda',
			'username'	=>	'masterofsleeping',
			'password'	=>	Hash::make('okejeh'),
			'email'		=>	'hudasanca@gmail.com'
		]);
	}
}