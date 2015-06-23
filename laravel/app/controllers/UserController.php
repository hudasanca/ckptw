<?php

class UserController extends \BaseController {

	public function __contruct(){
		$this->beforeFilter('csrf',['only'=>['store','update','destroy']]);
		$this->beforeFilter('auth',['only'=>['store','update','destroy']]);
		// $this->afterFilter('emailConfirmation,'.['only'=>['store']]);
	}

	public function index(){
		if (Route::currentRouteName()=='admin.master.whatyoudontrealize.user.index') {
			Config::set('view.pagination','pagination::slider-3');
			$users = User::paginate(10);
			return View::make('admin.pages.user', ['users'=>$users]);
		}
	}

	function confirm($confirmation){
		$user = User::where('confirmation_token',$confirmation)->first();
		$user->confirmed = 1;
		$user->step = 0;
		$user->save();
		return Redirect::route('root');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function register()
	{
		return View::make('pages.register');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();
		$data['username'] = strtolower($data['username']);
		$rules = [
			'name'=>'required',
			'username'=>[
				'required',
				'min:6',
				'max:12',
				'unique:users',
				'regex:/^[A-Za-z0-9_]+$/',
				'not_in:dashboard,setting,username,signout,register,blog,login,admin',
			],
			'email'=>'required|email|unique:users',
			'password'=>'required|min:6|confirmed'
		];
		
		$validator = Validator::make($data,$rules);
		$validated = $validator->passes();
		if ((!Auth::check())&&$validated) {
			$user = new User;
			$user->name = $data['name'];
			$user->username = $data['username'];
			$user->email = $data['email'];
			$user->password = Hash::make($data['password']);
			$user->followers = '[]';
			$user->following = '[]';
			$user->confirmation_token = str_random(200);
			$user->photo = 'default_picture.png';
			$user->header = 'default_user_header.png';
			$user->confirmed = 0;
			$user->step = 0;
			$user->save();

			Auth::login($user);

			//kirim email
			Mail::send('emails.auth.confirmation', ['name'=>'robot@ckptw.com'], function($message){
				$message->to(Input::get('email'), Input::get('name'))->subject('#ckptw Confirmation');
			});
		}

		if (Request::wantsJson()) {
			return Response::json(['message'=>$validated],200);
		}
		else{
			return Redirect::route('register')->withInput()->withErrors($validator);
		}
	}

	public function resendEmail(){
		$user = Auth::user();
		if ($user->confirmed==0) {
			$rules = [
				'email' => 'required|unique:users,email,'.Auth::id()
			];
			$validator = Validator::make(Input::all(),$rules);
			if ($validator->passes()) {
				if ($user->email != Input::get('email')) {
					$user->email = Input::get('email');
					$user->save();
				}

				//kirim email
				Mail::send('emails.auth.confirmation', ['name'=>'robot@ckptw.com'], function($message){
					$message->to(Input::get('email'), Input::get('name'))->subject('#ckptw Confirmation');
				});
			}
		}

		return Redirect::route('root');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  String  $username
	 * @return Response
	 */
	public function show($username)
	{
		$user = User::where('username',$username)->first();
		$statuses = $user->statuses;
		$followers = Follower::where('user_victim',$user->id)->get();
		$user['followed'] = UserController::isFollowed($username);
		foreach ($statuses as $status) {
			$status['username'] = $user->username;
			$status['name'] = $user->name;
		}
		if(Route::currentRouteName()=='admin.master.whatyoudontrealize.user.show'){
			return View::make('admin.pages.profile', ['user'=>$user,'statuses'=>$statuses,'followers'=>$followers]);
		}
		return View::make('pages.profile', ['user'=>$user,'statuses'=>$statuses,'followers'=>$followers]);
	}

	function skip(){
		$user = User::find(Auth::id());
		if ($user->step < 4) {
			$user->step = $user->step+1;
			$user->save();
		}
		return Redirect::route('root');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		$data = Input::all();
		if ($data['_form'] == 'profile') {
			$data['photo'] = Input::file('photo');
			$data['header'] = Input::file('header');
			$rules = [
				'name' => 'required',
				'username'=>[
					'required',
					'min:6',
					'max:12',
					'unique:users,username,'.Auth::id(),
					'regex:/^[A-Za-z0-9_]+$/',
					'not_in:admin,dashboard,setting,username,signout,register,blog,login',
				],
				'photo'=>'image',
				'header'=>'image',
				'bio'=>'max:200'
			];
			$validator = Validator::make($data,$rules);
			$validated = $validator->passes();
			$photo = "";
			$header = "";
			$moved = "";
			if ($validated) {
				$user = User::find(Auth::id());
				$user->name = $data['name'];
				$user->username = $data['username'];
				$user->bio = $data['bio'];
				//upload foto
				if($data['photo']!=''){
					$exists = true;
					while($exists){
						$photo = str_random(30).'.png';
						if(User::where('photo',$photo)->count()==0){
							$exists = false;
						}

					}
					$moved = $data['photo']->move('assets/images', $photo);

					if ($moved&&$user->photo != 'default_picture.png') {
						$file = 'assets/images/'.$user->photo;
						if (file_exists($file)) {
							unlink($file);
						}
					}
				}

				//upload header
				if($data['header']!=''){
					$exists = true;
					while($exists){
						$header = str_random(30).'.png';
						if(User::where('header',$header)->count()==0){
							$exists = false;
						}

					}
					$moved = $data['header']->move('assets/images', $header);

					if ($moved&&$user->header != 'default_user_header.png') {
						$file = 'assets/images/'.$user->header;
						if (file_exists($file)) {
							unlink($file);
						}
					}
				}

				//update profile
				$user->blur = $data['blur'];
				$user->photo = Input::file('photo')==null ? $user->photo : $photo;
				$user->header = Input::file('header')==null ? $user->header : $header;
				if (Input::has('_from')) {
					if (Input::get('_from')=='registration') {
						$user->step = $user->step+1;
					}
				}
				$user->save();
				if (Input::has('_from')) {
					return Redirect::route('root');
				}
				else{
					return Redirect::route('setting')->withErrors($moved ? '':$photo);
				}
			}
			else{
				return Redirect::route('setting')->withInput()->withErrors($validator);
			}			
		}
		elseif ($data['_form'] == 'password') {
			$data = Input::all();
			$rules = [
				'old_password'=>'required',
				'password'=>'required|min:6|confirmed'
			];
			$validator = Validator::make($data,$rules);
			$validated = $validator->passes();
			if ($validated) {
				$user = Auth::user();
				$oldPassword = $data['old_password'];
				$oldPasswordCorrect = Hash::check($oldPassword,$user->password);
				if ($oldPasswordCorrect) {
					$user->password = Hash::make($data['password']);
					$user->save();
					return Redirect::route('setting');
				}
				else{
					return 'password lama salah!<br>';
				}
			}
			else{
				return 'gaul';
			}
		}
		// return Redirect::route('setting');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	function follow($username){
		$user_actor = Auth::user();
		$user_victim = User::where('username',$username)->first();
		$followed = UserController::isFollowed($username);

		if (!$followed && Auth::id()!=$user_victim->id) {
			$follower = new Follower;
			$follower->user_victim = $user_victim->id;
			$follower->user_actor = $user_actor->id;
			$follower->save();

			//notif
			$notif = new NotificationController;
			$notif->store($user_victim->id,4);
		}

		if (Input::has('redirect')) {
			return Redirect::to(convert_uudecode(Input::get('redirect')));
		}
		else{
			return Redirect::route('user.show',[$username]);
		}
	}

	function followers($username){
		$user = User::where('username',$username)->first();
		$followers = Follower::where('user_victim',$user->id)->orderBy('created_at','desc');

		if (Request::ajax()&&Input::has('page')) {
			$skip = Input::get('skip');
			$page = Input::get('page');
			$followers = $followers->skip((9*$page+$skip))->take(9)->get();
		}
		else{
			$followers = $followers->simplePaginate(9);
		}

		$newUsers = [];
		
		foreach ($followers as $follower) {
			$follower = User::find($follower->user_actor);
			$followed = new UserController;
			$followed = $followed->isFollowed($follower->username);
			$newUsers[count($newUsers)] = [
				'id' => $follower->id,
				'username' => $follower->username,
				'name' => $follower->name,
				'photo' => $follower->photo,
				'header' => $follower->header,
				'followed' => $followed,
				'bio' => $follower->bio,
			];
		}

		return View::make('pages.followers', ['user'=>$user, 'followers'=>json_decode(json_encode($newUsers)), 'paginator'=>$followers]);
	}

	function unfollow($username){
		$user_actor = Auth::user();
		$user_victim = User::where('username',$username)->first();

		$followed = UserController::isFollowed($username);

		if ($followed && Auth::id()!=$user_victim->id) {
			$follower = Follower::where('user_actor',$user_actor->id)->where('user_victim',$user_victim->id)->first();
			$follower->delete();

			//hapus notif yang sudah ada
			$notif = Notification::where('type',4)
								->where('user_id',$user_victim->id)
								->where(function($query){
									$query->where('user_sender',Auth::id())
										  ->orWhere('user_involved','like','%{"id":'.Auth::id().'}%');
								})
								->first();
			if(count($notif)>0){
				if (count(json_decode($notif->user_involved))>1) {
					$notif->delete();
				}
				else{
					$user_involveds = json_decode($notif->user_involved);
					foreach ($user_involveds as $user_involved) {
						if ($user_involved->id != Auth::id()){
							$user_involved = [
								'id' => $user_involved->id
							];
						}
					}
					$notif->user_involved = json_encode($user_involveds);
					$notif->save();
				}
			}
		}	

		if (Input::has('redirect')) {
			return Redirect::to(convert_uudecode(Input::get('redirect')));
		}
		else{
			return Redirect::route('user.show',[$username]);
		}
	}

	function isFollowed($username){
		$followed = false;
		$user = User::where('username',$username)->first();
		$followers = Follower::where('user_victim',$user->id)->where('user_actor',Auth::id())->count();
		if ($followers>0) {
			$followed = true;
		}
		return $followed;
	}

	function validate(){
		$response;
		if (Input::get('method')=='name'){
			$rules = [
				'name'=>'required'
			];
			$validator = Validator::make(Input::all(),$rules);
			$validated = $validator->passes();
			$response = Response::json([
				'validated'=>$validated,
				'messages'=>$validator->messages()->first('name'),
				'data'=>Input::get('username')
			],200);
		}
		elseif (Input::get('method')=='username') {
			$rules = [
				'username'=>[
					'required',
					'min:6',
					'max:12',
					'unique:users',
					'regex:/^[A-Za-z0-9_]+$/',
					'not_in:admin,dashboard,setting,username,signout,register,blog,login',
				]
			];
			$validator = Validator::make(Input::all(),$rules);
			$validated = $validator->passes();
			$response = Response::json([
				'validated'=>$validated,
				'messages'=>$validator->messages()->first('username'),
				'data'=>Input::get('username')
			],200);
		}
		elseif(Input::get('method')=='email'){
			$rules = [
				'email'=>'required|email|unique:users'
			];
			$validator = Validator::make(Input::all(),$rules);
			$validated = $validator->passes();
			$response = Response::json([
				'validated'=>$validated,
				'messages'=>$validator->messages()->first('email'),
				'data'=>Input::get('username')
			],200);
		}
		elseif(Input::get('method')=='password'){
			$rules = [
				'password'=>'required|min:6'
			];
			$validator = Validator::make(Input::all(),$rules);
			$validated = $validator->passes();
			$response = Response::json([
				'validated'=>$validated,
				'messages'=>$validator->messages()->first('password'),
				'data'=>Input::get('password')
			],200);
		}
		else{
			$response = Response::json([
				'validated'=>'first',
				'messages'=>'',
				'data'=>''
			],200);
		}

		if (Request::ajax()) {
			return $response;
		}
		else{
			return 'access denied';
		}
	}

	function forgotPassword(){
		return View::make('pages.resetPassword');
	}

	function poll(){
		$statusResponse = '';
		$notifResponse = '';
		$notifIcon = '';
		$notifGede = '';

		//get status
		$statusModel = new Status;
		$statusModel = $statusModel->getStatusOfTheDay(Input::get('route'),Input::get('path'));
		$jumlahStatus = count($statusModel['statuses']);

		//get notif
		$notif = Session::get('notifications');
		$jumlahNotif = count($notif);

		if (Input::get('msg')=='jumlah') {
			return [
				'jumlahStatus'	=> $jumlahStatus,
				'jumlahNotif' => count($notif)
			];
		}
		elseif (Input::get('msg')=="update") {
			if (Input::get('statusClient')<$jumlahStatus) {
				//get view
				$statusModel = new Status;
				$statusModel = $statusModel->getRealTimeStatuses($jumlahStatus-Input::get('statusClient'),Input::get('route'),Input::get('path'));
				$statuses = $statusModel['statuses'];

				$statusResponse = '';
				foreach ($statuses as $status) {
					$statusResponse.=View::make('pages.status',['status'=>$status])->render();
				}
			}

			if (Input::get('notifClient')<$jumlahNotif) {
				// $notifResponse = "Ada ".($jumlahNotif-Input::get('notifClient'))." baru";
				// $notifResponse = 'ada';

				$notifResponse = View::make('templates.notificationsPopup',['notifications'=>$notif,'limit'=>$jumlahNotif-Input::get('notifClient')])->render();
				for($i=0;$i<$jumlahNotif-Input::get('notifClient');$i++) {
					$notifIcon .= View::make('templates.notificationsIcon',['notif'=>$notif[$i]])->render();
					$notifGede .= View::make('templates.notificationsSingle', ['notif'=>$notif[$i]])->render();
				}
			}

			return [
				'status'=>$statusResponse,
				'jumlahStatus' => $jumlahStatus,
				'marginStatus' => $jumlahStatus-Input::get('statusClient'),
				'notif' => $notifResponse,
				'notifIcon' => $notifIcon,
				'notifGede' => $notifGede,
				'jumlahNotif'  => $jumlahNotif,
				'marginNotif'  => $jumlahNotif-Input::get('notifClient')
			];
		}
	}
}
