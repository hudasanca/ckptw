<?php

class NotificationController extends \BaseController {

	public function store($id,$type)
	{
		//jika type-nya satu alias status yang dikomentari
		if ($type==1) {
			$notifications = Notification::where('type',$type)->orderBy('updated_at','desc')->where('effected',$id)->get();
			//periksa apakah notif sudah ada atau belum
			if ($notifications->count()>0) { //jika sudah
				//ambil data user yang terlibat
				$user_involved = json_decode($notifications[0]->user_involved);
				$involved = false;
				//periksa apakah sudah terlibat
				foreach ($user_involved as $key) {
					if ($key->id==Auth::id()) {
						$involved = true;
						break;
					}
				}
				//jika terlibat
				if ($involved) {
					$status = new Status;
					$data = $status->getUserInvolvedOnStatus($id);
					for ($i=0; $i < count($data); $i++) {
						$notification = Notification::where('user_id',$data[$i])->where('type',$type)->where('effected',$id)->first();
						if ($notification->user_id != Auth::id()) {
							$notification->user_sender = Auth::id();
						}
						$bo = Auth::id()==$data[$i] ? 1 : 0; //tidak aktif jika user sender dan yang login sama
						$notification->seen = $bo;
						$notification->clicked = $bo;
						$notification->save();
					}
				}
				else{//jika tidak terlibat
					$status = new Status;
					$data = $status->getUserInvolvedOnStatus($id);
					for ($i=0; $i < count($data); $i++) { 
						$notification = Notification::where('user_id',$data[$i])->where('type',$type)->where('effected',$id)->first();
						if(count($notification)>0){
							$user_involved = json_decode($notification->user_involved);
							$user_involved[count($user_involved)] = array('id'=>Auth::id());
							$notification->user_involved = json_encode($user_involved);
							$notification->user_sender = Auth::id();
							$bo = Auth::id()==$data[$i] ? 1 : 0;
							$notification->seen = $bo;
							$notification->clicked = $bo;
							$notification->save();
						}
						else{
							$notification = new Notification;
							$notification->user_id = $data[$i];
							$notification->user_sender = Auth::id();
							$user_involved = array();
							for ($j=0; $j < count($data); $j++) { 
								$user_involved[count($user_involved)] = array('id'=>$data[$j]);
							}
							$notification->user_involved = json_encode($user_involved);
							$notification->type = $type;
							$notification->effected = $id;
							$bo = Auth::id()==$data[$i] ? 1 : 0;
							$notification->seen = $bo;
							$notification->clicked = $bo;
							$notification->save();
						}
					}
				}
			}
			else{
				$status = new Status;
				$data = $status->getUserInvolvedOnStatus($id);
				for ($i=0; $i < count($data); $i++) { 
					$notification = new Notification;
					$notification->user_id = $data[$i];
					$notification->user_sender = Auth::id();
					$user_involved = array(array('id'=>Auth::id()));
					$notification->user_involved = json_encode($user_involved);
					$notification->type = $type;
					$notification->effected = $id;
					$bo = Auth::id()==$data[$i] ? 1 : 0;
					$notification->seen = $bo;
					$notification->clicked = $bo;
					$notification->save();
				}
			}
		}
		//love status
		elseif ($type == 2) {
			$notifications = Notification::where('type',$type)->orderBy('updated_at','desc')->where('effected',$id)->get();
			$status = Status::find($id);
			//jika notif sudah ada
			if ($notifications->count()>0) {
				$notification = Notification::where('type',$type)->where('effected',$id)->first();
				$notification->user_sender = Auth::id();
				$notification->user_involved = $status->loves;
				$notification->seen = 0;
				$notification->clicked = 0;
				$notification->save();
			}
			//jika belum
			else{

				$notification = new Notification;
				$notification->user_id = $status->user_id;
				$notification->user_sender = Auth::id();
				$notification->user_involved = $status->loves;
				$notification->type = $type;
				$notification->effected = $id;
				$notification->seen = 0;
				$notification->clicked = 0;
				$notification->save();
			}
		}
		//vote answer
		elseif ($type == 3) {
			$notifications = Notification::where('type',$type)->orderBy('updated_at','desc')->where('effected',$id)->get();
			$comment = Comment::find($id);
			$status = Status::find($comment->status_id);
			//jika notif sudah ada
			if ($notifications->count()>0) {
				$notification = Notification::where('type',$type)->where('effected',$id)->first();
				$notification->user_sender = Auth::id();
				$notification->user_involved = $comment->loves;
				$notification->seen = 0;
				$notification->clicked = 0;
				$notification->save();
			}
			//jika belum
			else{
				$notification = new Notification;
				$notification->user_id = $comment->user_id;
				$notification->user_sender = Auth::id();
				$notification->user_involved = $comment->loves;
				$notification->type = $type;
				$notification->effected = $id;
				$notification->seen = 0;
				$notification->clicked = 0;
				$notification->save();
			}
		}
		//follow
		elseif($type==4){
			$notifications = Notification::where('type',$type)->orderBy('updated_at','desc')->where('effected',$id)->first();
			//jika notif sudah ada dan belum diklik
			if (count($notifications) > 0 ) {
				if ($notifications->clicked==0 && $notifications->seen == 0) {
					$notification = $notifications;
					$notification->user_sender = Auth::id();
					$user_involved = json_decode($notification->user_involved);
					
					//tambahkan keterlibatan jika belum ada
					$involved = false;
					foreach ($user_involved as $key) {
						if ($key->id == Auth::id()) {
							$involved = true;
						}
					}
					if(!$involved){
						$user_involved[count($user_involved)] = array('id'=>Auth::id());
					}
					$notification->user_involved = json_encode($user_involved);
					$notification->save();
				}
				else{
					$notification = new Notification;
					$notification->user_id = $id;
					$notification->user_sender = Auth::id();
					$notification->user_involved = json_encode(array(array('id'=>Auth::id())));
					$notification->type = $type;
					$notification->effected = $id;
					$notification->seen = 0;
					$notification->clicked = 0;
					$notification->save();
				}
			}
			//jika tidak
			else{
				$notification = new Notification;
				$notification->user_id = $id;
				$notification->user_sender = Auth::id();
				$notification->user_involved = json_encode(array(array('id'=>Auth::id())));
				$notification->type = $type;
				$notification->effected = $id;
				$notification->seen = 0;
				$notification->clicked = 0;
				$notification->save();
			}
		}
		elseif ($type==5) {
			$notification = new Notification;
			$notification->user_id = $id;
			$notification->user_sender = Auth::id();
			$notification->user_involved = json_encode(array(array('id'=>Auth::id())));
			$notification->type = $type;
			$statusEffected = Status::orderBy('created_at','desc')->where('user_id',$notification->user_sender)->first();
			$notification->effected = $statusEffected->id;
			$notification->seen = 0;
			$notification->clicked = 0;
			$notification->save();
		}
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

	function getNotifications($all=false,$onTheDay = false, $mountOfNotif=0){

		$notif = Notification::where('user_id','=',Auth::id())->orderBy('updated_at','desc')->whereNotIn('user_sender',[Auth::id()]);
		if ($all) {
			if (Request::ajax()&&Input::has('page')) {
				$skip = Input::get('skip');
				$page = Input::get('page');
				$notif = $notif->skip((10*$page+$skip))->take(10)->get();
			}
			else{
				$notif = $notif->simplePaginate(10);
			}
		}
		elseif ($onTheDay) {
			$yesterday = Carbon::now()->subDay();
			$notif = $notif->whereBetween('created_at', [$yesterday,Carbon::now()]);
			if ($mountOfNotif!=0) {
				$notif = $notif->take($mountOfNotif);
			}
			$notif = $notif->get();
		}
		else{
			$notif = $notif->where('clicked',0)->get();
		}

		$unseen_notif = Notification::where('user_id','=',Auth::id())->where('seen','=',0)->orderBy('updated_at', 'desc')->get();
		foreach ($notif as $key) {
			$user_involved = json_decode($key->user_involved);
			$message = "";
			$limit = 0;
			if (count($user_involved)-3 > 0) {
				$limit = count($user_involved)-3;
			}
			for ($i=count($user_involved)-1; $i >= $limit; $i--) { 
				if (Auth::id()!=$user_involved[$i]->id) {
					$user = User::find($user_involved[$i]->id);
					$message .= $user->name;
					if ($i==$limit+1 && $user_involved[$limit]->id!=Auth::id()) {
						$message .= ' dan ';
					}
					elseif ($i>$limit+1) {
						$message .= ', ';
					}
					else{
						$message .= ' ';
					}
				}
			}
			switch ($key->type) {
			//jika tipe satu (komentar di thread)
			case 1:
				// $message = User::find($key->user_sender)->name." menjawab thread ";
				$icon = 'glyphicon glyphicon-comment';
				$i = 0;
				$message .= " mengomentari status";
				if (Status::find($key->effected)->user_id==Auth::id()) {
					$message.=" anda ";
				}
				else{
					$status = Status::find($key->effected);
					if ($key->user_sender==$status->user_id) {
						$message.='nya';
					}
					else{
						$message.=' '.$status->name;
					}
				}
				$link = route('notif.read',array('id'=>$key->id));
				break;
			case 2:
				$icon = 'glyphicon glyphicon-heart';
				$i = 0;
				$message .= " menyukai status ";
				if (Status::find($key->effected)->user_id==Auth::id()) {
					$message.="anda ";
				}
				else{
					$message.=User::find(Status::find($key->effected)->user_id)->name;
				}
				$link = route('notif.read',array('id'=>$key->id));
				break;
			case 3:
				$icon = 'glyphicon glyphicon-heart';
				$message .= " menyukai komentar anda";
				$link = route('notif.read',array('id'=>$key->id));
				break;
			case 4:
				$icon = 'glyphicon glyphicon-user';
				$message .= " mengikuti anda";
				$link = route('notif.read',array('id'=>$key->id));
				break;
			case 5:
				$icon = 'glyphicon glyphicon-tag';
				$i = 0;
				$message .= " menandai anda dalam statusnya";
				$link = route('notif.read',array('id'=>$key->id));
				break;
			default:
				$message = "";
				$link = "";
				$icon = "";
				break;
			}
			$key['message'] = $message;
			$key['link'] = $link;
			$key['icon'] = $icon;
		}
		if (!$all) {
			Session::put('notifications',$notif);
			Session::put('unseen_notif',count($unseen_notif));
		}
		else{
			return $notif;
		}
	}

	function readNotif(){
		// route('notif.read',array(User::find(Thread::find($key->effected)->user_id)->username,$key->effected));
		$notif = Notification::find(Input::get('id'));
		$notif->clicked = 1;
		$notif->seen = 1;
		$notif->save();
		switch ($notif->type) {
			case 1:
				$link = route('{username}.status.show', array(User::find(Status::find($notif->effected)->user_id)->username, $notif->effected));
				break;
			case 2:
				$link = route('{username}.status.show', array(User::find(Status::find($notif->effected)->user_id)->username, $notif->effected));
				break;
			case 3:
				$comment = Comment::find($notif->effected);
				$status = Status::find($comment->status_id);
				$user = User::find($status->user_id);
				$link = route('{username}.status.show', array($user->username, $status->id));
				break;
			case 4:
				$user = User::find($notif->user_sender);
				$link = route('{username}.followers', array(Auth::user()->username));
				break;
			case 5:
				$status = Status::find($notif->effected);
				$user = User::find($status->user_id);
				$link = route('{username}.status.show', array($user->username,$notif->effected));
				break;	
			default:
				$link = route('home');
				break;
		}

		return Redirect::to($link);
	}

	public function index(){
		$notifController = new NotificationController;
		$notifications = $notifController->getNotifications(true);
		return View::make('pages.notifications',['notifications'=>$notifications]);
	}
}
