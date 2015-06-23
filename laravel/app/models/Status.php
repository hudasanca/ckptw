<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Status extends Eloquent{
	protected $dates = ['deleted_at'];
	protected $fillable = ['user_id','status','loves','photo_status'];

	public function users(){
		return $this->belongsTo('User');
	}

	public function comments(){
		return $this->hasMany('Comment');
	}

	public static function semua(){
		$statuses = Status::paginate(10);
		foreach ($statuses as $status) {
			$status['username'] = User::find($status->user_id)->username;
		}
		return $statuses;
	}

	public function getById($id){
		$status = Status::find($id);
		$user = User::find($status->user_id);
		$status['username'] = $user->username;
		$status['photo'] = $user->photo;
		$status['name'] = $user->name;
		$status['loved'] = new StatusController;
		$status['loved'] = $status['loved']->isLoved($id);
		$status['originalStatus'] = $status->status;
		$status->status = $status->statusFilter($status->status);
		$status['date'] = $status->getDate($status->created_at);
		$comments = $status->comments;
		foreach ($comments as $comment) {
			$userComment = User::find($comment->user_id);
			$commentController = new CommentController;
			$comment['username'] = $userComment->username;
			$comment['name'] = $userComment->name;
			$comment['photo'] = $userComment->photo;
			$comment['loved'] = $commentController->isLoved($comment->id);
			$comment->comment = $status->statusFilter($comment->comment);
			$comment['date'] = $status->getDate($comment->created_at);
		}

		return ['status'=>$status,'comments'=>$comments];
	}

	public function getByUsername($username,$onTheDay = false, $mountOfStatuses=0){
		$user = User::where('username',$username)->first();
		$statuses = $user->statuses()->orderBy('id','desc');
		if ($onTheDay) {
			$yesterday = Carbon::now()->subDay();
			$statuses = $statuses->whereBetween('created_at', [$yesterday,Carbon::now()]);
			if ($mountOfStatuses!=0) {
				$statuses = $statuses->take($mountOfStatuses);
			}
			$statuses = $statuses->get();
		}
		elseif (Request::ajax()&&Input::has('page')) {
			$skip = Input::get('skip');
			$page = Input::get('page');
			$statuses = $statuses->skip((10*$page+$skip))->take(10)->get();
		}
		else{
			$statuses = $statuses->simplePaginate(10);
		}
		if (count($statuses)>0) {
			foreach ($statuses as $status) {
				$user = User::find($status->user_id);
				$status['username'] = $user->username;
				$status['name'] = $user->name;
				$status['photo'] = $user->photo;
				$status['loved'] = new StatusController;
				$status['loved'] = $status['loved']->isLoved($status->id);
				$status['originalStatus'] = $status->status;
				$status->status = $status->statusFilter($status->status);
				$status['date'] = $status->getDate($status->created_at);
			}
		}
		return ['statuses'=>$statuses];
	}

	public function getByUserFollowed($onTheDay = false, $mountOfStatuses=0){
		$followers = Follower::where('user_actor',Auth::id())->get();
		$statuses = Status::where('user_id',Auth::id())->orderBy('id','desc');
		foreach ($followers as $follower) {
			$statuses->orWhere('user_id',$follower->user_victim);
		}
		if ($onTheDay) {
			$yesterday = Carbon::now()->subDay();
			$statuses = $statuses->whereBetween('created_at', [$yesterday,Carbon::now()]);
			if ($mountOfStatuses!=0) {
				$statuses = $statuses->take($mountOfStatuses);
			}
			$statuses = $statuses->get();
		}
		elseif (Request::ajax()&&Input::has('page')) {
			$skip = Input::get('skip');
			$page = Input::get('page');
			$statuses = $statuses->skip((10*$page)+$skip)->take(10)->get();
		}
		else{
			$statuses = $statuses->simplePaginate(10);
		}
		if (count($statuses)>0) {
			foreach ($statuses as $status) {
				$user = User::find($status->user_id);
				$status['username'] = $user->username;
				$status['name'] = $user->name;
				$status['photo'] = $user->photo;
				$status['loved'] = new StatusController;
				$status['loved'] = $status['loved']->isLoved($status->id);
				$status['originalStatus'] = $status->status;
				$status->status = $status->statusFilter($status->status);
				$status['date'] = $status->getDate($status->created_at);
			}
		}
		return ['statuses'=>$statuses];
	}

	public function getStatusOfTheDay($clientActiveRoute='', $clientActivePath=''){
		$statusModel = new Status;
		if ($clientActiveRoute=='{username}.status.index') {
			$pattern = "/\b(http:\/\/|www\.)([a-zA-Z0-9-\?\.\/#]+)\/(\w+)\/status\b/i";
			$username = 'default';
			if(preg_match($pattern, $clientActivePath, $matches)){
				$username = $matches[3];
			}
			$statusModel = $statusModel->getByUsername($username,true);
			// $statusModel = $statusModel->getByUserFollowed(true);
		}
		else{
			$statusModel = $statusModel->getByUserFollowed(true);
		}
		return $statusModel;
	}

	public function getRealTimeStatuses($mountOfStatuses,$clientActiveRoute='', $clientActivePath=''){
		$statusModel = new Status;
		$statusModel = $statusModel->getByUserFollowed(true, $mountOfStatuses);
		return $statusModel;
	}


	public function getUserInvolvedOnStatus($status_id){
		$data = Comment::where('status_id','=', $status_id)->distinct()->lists('user_id');
		$found = false;

		//mencari user yg punya status sudah komentar atau tidak
		for($i=0;$i<count($data);$i++){
			if ($data[$i]==Status::find($status_id)->user_id) {
				$found = true;
			}
		}

		//jika tidak maka akan dilibatkan secara manual
		if(!$found){
			$data[count($data)] = Status::find($status_id)->user_id;
		}

		//mencari apakah yang user yang sedang logged in sudah terlibat
		$found = false;
		for($i=0;$i<count($data);$i++){
			if ($data[$i]==Auth::id()) {
				$found = true;
			}
		}
		//jika tidak maka akan dilibatkan secara manual
		if(!$found){
			$data[count($data)] = Auth::id();
		}
		return $data;
	}

	public function searchStatus($query){
		$statuses = Status::where('status','like','%'.$query.'%')->orderBy('created_at','desc');
		if(Request::ajax()&&Input::has('page')){
			$skip = Input::get('skip');
			$page = Input::get('page');
			$statuses = $statuses->skip((5*$page+$skip))->take(5)->get();
		}
		else{
			$statuses = $statuses->simplePaginate(5);
			$statuses->setBaseUrl(route(Route::currentRouteName(),['q'=>Input::get('q')]).'&');
		}
		$users = Status::select('user_id')->distinct()->where('status','like','%'.$query.'%')->get();
		if (count($statuses)>0) {
			foreach ($statuses as $status) {
				$user = User::find($status->user_id);
				$status['username'] = $user->username;
				$status['name'] = $user->name;
				$status['photo'] = $user->photo;
				$status['loved'] = new StatusController;
				$status['loved'] = $status['loved']->isLoved($status->id);
				$status['originalStatus'] = $status->status;
				$status->status = $status->statusFilter($status->status);
				$status['date'] = $status->getDate($status->created_at);
			}
		}
				

		return ['statuses'=>$statuses, 'users'=>$users];
	}

	function statusFilter($status, $method = 'read'){
		$result = $status;
		
		$link = false;


		// $pattern = "/\B\@(\w+)/i";
		// if (preg_match($pattern, $result, $matches)) {
		// }

		$resultExplode = explode(" ", $result);

		for ($i=0; $i < count($resultExplode); $i++) { 
			//jika ber www atau http
			$pattern = "/\b(http:\/\/|www\.)([a-zA-Z0-9-\?\.\/#]+)\b/i";
			if(preg_match($pattern, $resultExplode[$i], $matches)){
				$resultExplode[$i] = $matches[2];
			}

			$pattern = "/\b([\w]+\.[a-zA-Z0-9\-\?\.\/#]+)\b/i";
			if(preg_match($pattern, $resultExplode[$i], $matches)){
				// $result = "<a target='_blank' href='http://".$matches[1]."'>".$matches[1]."</a>";
				$replacement = "<a target='_blank' href='http://".$matches[1]."'>".$matches[1]."</a>";
				$resultExplode[$i] = preg_replace($pattern, $replacement, $resultExplode[$i]);
				$link = true;
			}
			else{
				$link = false;
			}

			$pattern = "/\B\#(\w+)/i";
			if (!$link&&preg_match($pattern, $resultExplode[$i], $matches)) {
				if ($method=='store') {
					$hashtag = new Hashtag;
					$hashtag->user_id = Auth::id();
					$hashtag->hashtag = $matches[1];
					$hashtag->save();
				}
				$url = route('discover.search',['q'=>$matches[1]]);
				$replacement = '<a href="'.$url.'">#'.$matches[1].'</a>';
				$resultExplode[$i] = $replacement;

			}

			$pattern = "/\B\@(\w+)/i";
			if (preg_match($pattern, $resultExplode[$i], $matches)) {
				$url = route('user.show',[$matches[1]]);
				$found = User::where('username',$matches[1])->count()>0 ? true : false;
				$replacement = '<a href="'.$url.'">@'.$matches[1].'</a>';

				$resultExplode[$i] = $found ? $replacement : $resultExplode[$i];
			
				if ($found&&$method=='store'&&Auth::user()->username!=$matches[1]) {
					$currentUser = new CurrentUser;
					$currentUser->user_actor = Auth::id();
					$currentUser->user_victim = User::where('username',$matches[1])->first()->id;
					$currentUser->type = 1;
					$currentUser->save();

					$notif = new NotificationController;
					$notif->store($currentUser->user_victim,5);
				}
			}

			$pattern = "/\B\<3/i";
			if (preg_match($pattern, $resultExplode[$i], $matches)) {
				$replacement = '<span class="glyphicon glyphicon-heart"></span>';
				$resultExplode[$i] = $replacement;
			}
		}

		$result = '';
		for ($i=0; $i < count($resultExplode); $i++) { 
			$result .= $resultExplode[$i]." ";
		}

		if ($method == 'read') {
			return $result;
		}
	}

	function getDate($created_at){
		$date1 = new DateTime('NOW');
		$date2 = new DateTime($created_at);
		$interval = $date1->diff($date2);
		// return $interval->y . " years, " . $interval->m." months, ".$interval->d." days ".$interval->h." hours ".$interval->i." minutes ".$interval->s." s "; 
		// shows the total amount of days (not divided into years, months and days like above)
		// echo "difference " . $interval->days . " days ";
		if ($interval->y>0) {
			$result = $interval->y."y";
		}
		elseif ($interval->m>0) {
			$result = $interval->m."m";
		}
		elseif ($interval->d>0) {
			$result = $interval->days."d";
		}
		elseif ($interval->h>0) {
			$result = $interval->h."h";
		}
		elseif ($interval->i>0) {
			$result = $interval->i."m";
		}
		else{
			$result = $interval->s."s";
		}
		return $result;
	}

	function saveHashtagIfExists($data){
		// $hashtag = new Hashtag;
		// $hashtag->user_id = Auth::id();
		// $hashtag->hashtag = $data->status;
		// $hashtag->save();
		$status = new Status;
		$status->statusFilter($data->status, 'store');
	}
}