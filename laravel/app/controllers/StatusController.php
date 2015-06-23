<?php

class StatusController extends \BaseController {

	public function __construct(){
		$this->beforeFilter('csrf',['only'=>['store','update','destroy']]);
		$this->beforeFilter('auth',['only'=>['store','update','destroy','edit']]);
	}
	
	public function all(){
		if (Route::currentRouteName()=='admin.master.whatyoudontrealize.status.all') {
			Config::set('view.pagination','pagination::slider-3');
			// $statuses = Status::paginate(10);
			$statuses = Status::semua();
			return View::make('admin.pages.status', ['statuses'=>$statuses]);
		}
	}


	public function index($username)
	{
		$statusModel = new Status;
		$statusModel = $statusModel->getByUsername($username);
		$statuses = $statusModel['statuses'];
		return View::make('pages.profileStatus',['user'=>User::where('username',$username)->first(),'statuses'=>$statuses]);
	}


	/**
	 * Store a newly created resource in storage.
	 * 
	 * param string @username
	 * @return Response
	 */
	public function store($username)
	{
		$data = Input::all();
		$data['status'] = trim($data['status']);
		$rules = [
			'status'=>'required'
		];

		$validator = Validator::make($data,$rules);
		$validated = $validator->passes();
		$status = new Status(['status'=>$data['status'],'loves'=>'[]','photo_status'=>$data['photo']]);
		$user = User::find(Auth::id());
		if($validated){
			$status = $user->statuses()->save($status);
		}
		$statusModel = new Status;
		$statusModel->saveHashtagIfExists($status);
		// $statusModel = new Status;
		$statusModel = $statusModel->getById($status->id);
		$status = $statusModel['status'];

		if(Request::wantsJson()){
			return Response::json($user->status,200);
		}
		elseif (Request::ajax()) {
			// return json_encode($status);
			return View::make('templates.status',['status'=>$status]);
		}
		else{
			return Redirect::to(Input::has('redirect') ? Input::get('redirect') : '/')->withErrors($validator);
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($username,$id)
	{
		$statusModel = new Status;
		$statusModel = $statusModel->getById($id);
		$status = $statusModel['status'];
		$comments = $statusModel['comments'];
		if(Route::currentRouteName()=='grasp.api.v1.user.status.show'){
			return Response::json($status,200);
		}
		else{
			return View::make('pages.status', ['status'=>$status,'comments'=>$comments]);
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($username,$id)
	{
		$statusModel = new Status;
		$statusModel = $statusModel->getById($id);
		$status = $statusModel['status'];
		return View::make('pages.statusEdit',['status'=>$status]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($username,$id)
	{
		$user = User::where('username',$username)->first();
		$status = Status::where('user_id',$user->id)->where('id',$id)->first();
		if ($status->user_id == Auth::id()) {
			$data = Input::all();
			$rules = [
				'status'=>'required'
			];
			$validator = Validator::make($data,$rules);
			$validated = $validator->passes();
			if ($validated) {
				$status->status = Input::get('status');
				$status->save();
			}
			return Redirect::route('{username}.status.show',[$username,$id]);
		}
		else{
			return Redirect::route('{username}.status.show',[$username,$id])->withErrors('It\'s not your status!');
		}

	}



	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($username,$id)
	{
		$user = User::where('username',$username)->first();
		$status = Status::where('user_id',$user->id)->where('id',$id)->first();
		if ($status->user_id==Auth::id()) {
			$status->delete();
			$comments = Comment::where('status_id',$id)->get();
			foreach ($comments as $comment) {
				$comment->delete();
			}

			$notifications = Notification::where('type',1)->orwhere('type','2')->where('effected',$status->id)->get();
			foreach ($notifications as $notification) {
				$notification->delete();
			}
			return Redirect::route('root');
		}
		else{
			return "This's not your status!";
		}
	}

	public function deleteConfirmation($username,$id){
		$user = User::where('username',$username)->first();
		$status = Status::where('user_id',$user->id)->where('id',$id)->first();
		$status['username'] = $user->username;
		$link = route('{username}.status.destroy',[$username,$id]);
		$token = Session::token();
		if (Request::ajax()) {
			return Response::json(
				[
					'link'=>$link,
					'token'=>$token
				],
				200
			);
		}
		else{
			return View::make('pages.statusDelete',['status'=>$status]);
		}
	}

	function love($username,$id){
		$user = User::find(Auth::id());
		$status = Status::find($id);
		$loves = json_decode($status->loves);
		
		//periksa apakah user sudah mencintai atau belum
		$loved = StatusController::isLoved($id);
		
		if (!$loved) {
			$loves[count($loves)] = array('id'=>$user->id);
			$status->loves = json_encode($loves);
			$status->save();
			
			//notif
			$notif = new NotificationController;
			$notif->store($id,2);
			return Redirect::route('{username}.status.show', array(User::find($status->user_id)->username,$id));
		}
		elseif (Request::ajax()) {
			return 'true';
		}
		else{
			return Redirect::route('{username}.status.show', array(User::find($status->user_id)->username,$id))->withErrors("You can't vote your own thread!");
		}
	}

	function undoLove($username,$id){
		$user = User::find(Auth::id());
		$status = Status::find($id);
		$loves = json_decode($status->loves);
		
		$new_loves = array();
		for ($i=0; $i < count($loves); $i++) { 
			if ($loves[$i]->id!=$user->id) {
				$new_loves[count($new_loves)] = array('id'=>$loves[$i]->id);
			}
		}
		$status->loves = json_encode($new_loves);
		$status->save();
		return Redirect::route('{username}.status.show', array(User::find($status->user_id)->username,$id));
	}

	function isLoved($id){
		$loved = false;
		$status = Status::find($id);
		if(count($status)>0){
			$loves = json_decode($status->loves);
			if (count($loves)>0) {
				for ($i=0; $i < count($loves); $i++) { 
					if (Auth::id()==$loves[$i]->id) {
						$loved = true;
					}
				}
			}
		}
		return $loved;
	}

}
