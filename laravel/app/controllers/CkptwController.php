<?php

class CkptwController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (Auth::check()) {
			$user = Auth::user();
			if ($user->confirmed == 0 || $user->step == 0) {
				return View::make('signup.step0',['user'=>Auth::user()]);
			}
			elseif($user->step == 1){
				return View::make('signup.step1',['user'=>Auth::user()]);
			}
			elseif($user->step == 2){
				$popularUser = CkptwController::discover('popularUser');
				return View::make('signup.step2',['user'=>Auth::user(),'popularUser'=>$popularUser]);
			}
			elseif($user->step == 3){
				return View::make('signup.step3',['user'=>Auth::user()]);
			}
			else{
				$statusModel = new Status;
				$statusModel = $statusModel->getByUserFollowed();
				$statuses = $statusModel['statuses'];
				return View::make('pages.dashboard',['user'=>Auth::user(),'statuses'=>$statuses]);
			}
		}
		else{
			return View::make('pages.home');
		}
	}

	public function discover($wants = 'all'){
		$yesterday = Carbon::now()->subDay();

		if ($wants=='all'||$wants=='trendings') {
			$trendings = Hashtag::whereBetween('created_at', [$yesterday,Carbon::now()])->distinct()->lists('hashtag');

			for ($i=0; $i < count($trendings); $i++) { 
				$hashtag = $trendings[$i];
				$trendings[$i] = [
					'hashtag'=>$hashtag,
					'count' => Hashtag::whereBetween('created_at', [$yesterday,Carbon::now()])->where('hashtag',$hashtag)->count()
				];
			}
			
			for ($i=0; $i < count($trendings); $i++) { 
				for ($j=0; $j < count($trendings)-1; $j++) { 
					if ($trendings[$j]['count'] < $trendings[$j+1]['count']) {
						$temp = $trendings[$j];
						$trendings[$j] = $trendings[$j+1];
						$trendings[$j+1] = $temp;
					}
				}
			}
		}
		
		if ($wants=='all'||$wants=='recentPeople') {
			$currentUsers = CurrentUser::where('user_actor',Auth::id())->whereNotIn('user_victim',[Auth::id()])->distinct()->lists('user_victim');

			for ($i=0; $i < count($currentUsers); $i++) {
				$user = User::find($currentUsers[$i]);
				$followed = new UserController;
				$followed = $followed->isFollowed($user->username); 
				$currentUsers[$i] = [
					'id' => $user->id,
					'username' => $user->username,
					'name' => $user->name,
					'photo' => $user->photo,
					'header' => $user->header,
					'followed' => $followed,
					'bio' => $user->bio,
					'count' => CurrentUser::where('user_actor',Auth::id())->where('user_victim',$user->id)->count()
				];
			}

			for ($i=0; $i < count($currentUsers); $i++) { 
				for ($j=0; $j < count($currentUsers)-1; $j++) { 
					if ($currentUsers[$j]['count'] < $currentUsers[$j+1]['count']) {
						$temp = $currentUsers[$j];
						$currentUsers[$j] = $currentUsers[$j+1];
						$currentUsers[$j+1] = $temp;
					}
				}
			}

			$recentPeople = [];
			$limit = count($currentUsers)>6 ? 6:count($currentUsers);
			for ($i=0; $i < $limit; $i++) { 
				$recentPeople[$i] = $currentUsers[$i];
			}
		}
		
		if ($wants=='all'||$wants=='popularUser') {
			if (Auth::check()) {
				$popularUser = User::whereNotIn('id',[Auth::id()])->get();
			}
			else{
				$popularUser = User::where('confirmed',1)->get();
			}

			foreach ($popularUser as $key) {
				$followed = new UserController;
				$followed = $followed->isFollowed($key->username);
				$key['followed'] = $followed;
				$key['count'] = Follower::where('user_victim',$key->id)->count();
			}

			for ($i=0; $i < count($popularUser); $i++) { 
				for ($j=0; $j < count($popularUser)-1; $j++) { 
					if ($popularUser[$j]['count'] < $popularUser[$j+1]['count']) {
						$temp = $popularUser[$j];
						$popularUser[$j] = $popularUser[$j+1];
						$popularUser[$j+1] = $temp;
					}
				}
			}

			$newPopularUser = [];
			$limit = count($popularUser)>9 ? 9:count($popularUser);
			$j = 0;
			for ($i=0; $j < $limit; $i++) { 
				if ($popularUser[$i]->confirmed == 1) {
					$newPopularUser[$i] = $popularUser[$i];
					$j++;
				}
			}
		}
		if ($wants=='all') {
			return View::make('pages.discover', [
				'trendings' => json_decode(json_encode($trendings)),
				'recentPeople' => json_decode(json_encode($recentPeople)),
				'popularUser' => $newPopularUser
			]);
		}
		elseif ($wants=='trendings') {
			return json_decode(json_encode($trendings));
		}
		elseif ($wants=='recentPeople') {
			return json_decode(json_encode($recentPeople));
		}
		else{
			return $newPopularUser;
		}
	}

	public function search(){
		if (Input::has('q')) {
			$statusModel = new Status;
			$statusModel = $statusModel->searchStatus(Input::get('q'));
			$statuses = $statusModel['statuses'];

			$newUsers = [];

			$users = User::where('username','like','%'.Input::get('q').'%')
					->orWhere('name','like','%'.Input::get('q').'%')
					->get();

			foreach ($users as $user) {
				$followed = new UserController;
				$followed = $followed->isFollowed($user->username);
				$newUsers[count($newUsers)] = [
					'id' => $user->id,
					'username' => $user->username,
					'name' => $user->name,
					'photo' => $user->photo,
					'header' => $user->header,
					'followed' => $followed,
					'bio' => $user->bio,
				];
			}			

			$users = $statusModel['users'];
			foreach ($users as $key) {
				$user = User::find($key->user_id);
				$followed = new UserController;
				$followed = $followed->isFollowed($user->username);
				$doesntExist = true;
				//periksa apakah sudah ada di sebelumnya
				foreach ($newUsers as $key2) {
					if ($key2['username']==$user->username) {
						$doesntExist = false;
						break;
					}
				}

				if ($doesntExist) {
					$newUsers[count($newUsers)] = [
						'id' => $user->id,
						'username' => $user->username,
						'name' => $user->name,
						'photo' => $user->photo,
						'header' => $user->header,
						'followed' => $followed,
						'bio' => $user->bio,
						'count' => CurrentUser::where('user_actor',Auth::id())->where('user_victim',$user->id)->count()
					];
				}
			}


			return View::make('pages.search.dashboard', [
				'statuses'=>$statuses,
				'users'=>json_decode(json_encode($newUsers))
			]);
		}
		else{
			return Redirect::route('discover');
		}
	}

	public function setting(){
		return View::make('pages.setting',['user'=>Auth::user()]);	
	}

	public function signinAuth(){
		$data = Input::all();
		$data['username'] = strtolower($data['username']);
		$rules = [
			'username' => 'email'
		];
		$validator = Validator::make($data,$rules);
		$validated = $validator->passes();
		if (Auth::attempt([$validated ? 'email' : 'username' => $data['username'], 'password'=>$data['password']], Input::has('remember') ? true : false)) {
			return Redirect::route('root');
		}
		else{
			return Redirect::route('signin')->withInput()->withErrors('Sign in failed');
		}
	}

	public function signinPage(){
		return View::make('pages.signin');
	}

	public function instagramConnect(){
		if (Input::has('code')) {
			$data = Instagram::getOAuthToken(Input::get('code'));
			if (User::where('instagram_access_token',$data->access_token)->count()==0) {
				$user = Auth::user();
				$user->instagram_access_token = $data->access_token;
				$user->save();
			}
			return Redirect::route('root');
		}
		else{
			return Redirect::to(Instagram::getLoginUrl());
		}
	}

	public function getPhotosFromInstagram(){
		$instagram_token = Auth::user()->instagram_access_token;
		if (!$instagram_token==null) {
			Instagram::setAccessToken($instagram_token);
			$media = Instagram::getUserMedia('self');
			if ($media->meta->code==200) {
				return View::make('instagram.media',['media'=>$media]);
				// return Response::json($media, 200);
			}
			else{
				return View::make('instagram.connect');
			}
		}
		else{
			return View::make('instagram.connect');
		}
	}
}
