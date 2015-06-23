<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class CurrentUser extends Eloquent{
	// use SoftDeletingTrait;
	protected $dates = ['deleted_at'];
	protected $table = 'current_users';
}