<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Comment extends Eloquent{
	protected $dates = ['deleted_at'];
	protected $fillable = ['comment','status_id','user_id','loves'];

	public function statuses(){
		return $this->belongsTo('Status');
	}

	public function users(){
		return $this->belongsTo('User');
	}

	public static function semua(){
		$comments = Comment::paginate(10);
		foreach ($comments as $comment) {
			$comment['username'] = User::find($comment->user_id)->username;
		}
		return $comments;
	}

}