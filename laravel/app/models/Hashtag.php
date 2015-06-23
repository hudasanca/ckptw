<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Hashtag extends Eloquent{
	// use SoftDeletingTrait;
	protected $dates = ['deleted_at'];
}