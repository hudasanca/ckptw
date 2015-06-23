<?php

class AdminController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::orderBy('id','desc')->take(5)->get();
		foreach ($users as $user) {
			$followers = Follower::where('user_victim',$user->id)->get();
			$user['followers'] = $followers;
		}

		$statuses = Status::orderBy('id','desc')->take(5)->get();
		foreach($statuses as $status){
			$comments = Comment::where('status_id', $status->id)->get();
			$status['comments'] = $comments;
			$status['username'] = User::find($status->user_id)->username;
		}

		$comments = Comment::orderBy('id','desc')->take(5)->get();
		foreach ($comments as $comment) {
			$comment['username'] = User::find($comment->user_id)->username;
		}

		return View::make('admin.pages.dashboard', [
			'users'		=>	$users,
			'statuses'	=>	$statuses,
			'comments'	=>	$comments
		]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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


}
