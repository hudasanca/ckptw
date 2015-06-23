<?php

class Follower extends Eloquent{
	protected $fillable = ['user_id'];
	protected $table = 'followers';

	public function users(){
		return $this->belongsTo('User');
	}
}