<?php
class CommentController extends \BaseController {

	public function __construct(){
		$this->beforeFilter('csrf',['only'=>['store','update','destroy']]);
		$this->beforeFilter('auth',['only'=>['store','update','destroy','edit']]);
	}
	
	public function all(){
		if (Route::currentRouteName()=='admin.master.whatyoudontrealize.comment.all') {
			Config::set('view.pagination','pagination::slider-3');
			$comments = Comment::semua();
			return View::make('admin.pages.comment', ['comments'=>$comments]);
		}
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($username,$status_id)
	{
		$comment = new Comment([
			'user_id'=>Auth::id(),
			'comment'=>Input::get('comment'),
			'status_id'=>$status_id,
			'loves' => '[]']);
		$statusModel = new Status;
		$statusModel = $statusModel->getById($status_id);
		$status = $statusModel['status'];
		$user = User::find($status->user_id);
		$data = Input::all();
		$rules = [
			'comment'=>'required'
		];
		$validator = Validator::make($data,$rules);
		$validated = $validator->passes();
		if ($validated) {
			$comment = $status->comments()->save($comment);

			//notif 
			$notif = new NotificationController;
			$notif->store($status_id,1);

			//recent
			$currentUser = new CurrentUser;
			$currentUser->user_actor = Auth::id();
			$currentUser->user_victim = User::where('id',$status->user_id)->first()->id;
			$currentUser->type = 2;
			$currentUser->save();

			if (Request::wantsJson()) {
				return Response::json(['message'=>'done',$comment],200);
			}
			elseif (Request::ajax()) {
				$comment['username'] = Auth::user()->username;
				$comment['photo'] = Auth::user()->photo;
				$comment['name'] = Auth::user()->name;
				$statusModel = new Status;
				$comment->comment = $statusModel->statusFilter($comment->comment);
				$comment['date'] = $statusModel->getDate($comment->created_at);
				return View::make('templates.comment',['status'=>$status,'comment'=>$comment]);
			}
			else{
				return Redirect::route('{username}.status.show', [$user->username,$status->id]);
			}
			return 'validated';
		}
		else{
			if (Request::wantsJson()) {
				return Response::json(['message'=>'failed',$comment],200);
			}
			else{
				return Redirect::route('{username}.status.show', [$user->username,$status->id]);
			}
		}

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($username,$status_id,$id)
	{
		$comment = Comment::where('status_id',$status_id)->where('id',$id)->first();
		$user = User::where('username',$username)->first();
		$statusModel = new Status;
		$statusModel = $statusModel->getById($status_id);
		$status = $statusModel['status'];
		return View::make('pages.commentEdit',['status'=>$status,'comment'=>$comment,'user'=>$user]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($username,$status_id,$id)
	{
		$comment = Comment::find($id);
		$comment->comment = Input::get('comment');
		$comment->save();

		if (Request::wantsJson()) {
			return Response::json(['message'=>'done','comment'=>$comment],200);
		}
		else{
			return Redirect::route('{username}.status.show',[$username,$status_id]);
		}
	}

	public function deleteConfirmation($username,$status_id,$id){
		$user = User::where('username',$username)->first();
		$status = Status::where('user_id',$user->id)->where('id',$status_id)->first();
		$status['username'] = $user->username;
		$comment = Comment::find($id);
		return View::make('pages.commentDelete',['status'=>$status,'comment'=>$comment]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($username,$status_id,$id)
	{
		// $user = User::where('username',$username)->first();
		// $status = Status::where('user_id',$user->id)->where('id',$id)->first();
		$comment = Comment::find($id);

		if ($comment->user_id==Auth::id()) {
			$comment->delete();
			return Redirect::route('{username}.status.show',[$username,$status_id]);
		}
		else{
			return "This's not your status!";
		}
	}

	function love($username,$status_id,$id){
		$user = User::find(Auth::id());
		$comment = Comment::find($id);
		$loves = json_decode($comment->loves);
		
		//periksa apakah user sudah mencintai atau belum
		$loved = CommentController::isLoved($id);
		
		if (!$loved) {
			$loves[count($loves)] = array('id'=>$user->id);
			$comment->loves = json_encode($loves);
			$comment->save();
			
			//notif
			$notif = new NotificationController;
			$notif->store($status_id,3);
			return Redirect::route('{username}.status.show', array($username,$status_id));
		}
		else{
			return Redirect::route('{username}.status.show', array($username,$status_id))->withErrors("You can't vote your own thread!");
		}
	}

	function undoLove($username,$status_id,$id){
		$user = User::find(Auth::id());
		$comment = Comment::find($id);
		$loves = json_decode($comment->loves);
		
		$new_loves = array();
		for ($i=0; $i < count($loves); $i++) { 
			if ($loves[$i]->id!=$user->id) {
				$new_loves[count($new_loves)] = array('id'=>$loves[$i]->id);
			}
		}
		$comment->loves = json_encode($new_loves);
		$comment->save();
		return Redirect::route('{username}.status.show', array($username,$status_id));
	}

	function isLoved($id){
		$loved = false;
		$comment = Comment::find($id);
		$loves = json_decode($comment->loves);
		if (count($loves)>0) {
			for ($i=0; $i < count($loves); $i++) { 
				if (Auth::id()==$loves[$i]->id) {
					$loved = true;
				}
			}
		}
		return $loved;
	}
}
