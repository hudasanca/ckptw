<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Notification extends Eloquent{
	// use SoftDeletingTrait;
	protected $dates = ['deleted_at'];
}