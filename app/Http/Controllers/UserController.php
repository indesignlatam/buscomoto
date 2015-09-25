<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use Queue;
use Hash;
use Agent;

use App\User;
use App\Jobs\SendUserConfirmationEmail;

class UserController extends Controller {

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(){
		$this->middleware('throttle.auth', ['only' => ['update']]);
		$this->middleware('throttle', ['only' => ['confirm', 'sendConfirmationEmail']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		//
		$users = User::with('listingCount')->orderBy('id', 'DESC')->paginate(30);

		// Serve correct view for Desktop or mobile
		$view = 'admin.users.index';
		if(Agent::isMobile()){
			$view = 'admin.users.mobile.index';
		}

		return view($view, ['users' => $users]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(){
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(){
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($username){
		//
		$user = User::where('username', $username)->first();

		if(!$user){
			abort(404);
		}

		$user->load('listings', 'listings.listingType', 'listings.featuredType');

		return view('user.show', ['user' => $user]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id, Request $request){
		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$id || $id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('/admin/user/'.Auth::user()->id.'/edit')->withErrors([trans('responses.no_permission')]);
	    	}
		}

	    $user = User::find($id);

	    if(!$user){
			abort(404);
		}

		// Serve correct view for Desktop or mobile
		$view = 'admin.users.edit';
		if(Agent::isMobile()){
			$view = 'admin.users.mobile.edit';
		}

		return view($view, ['user' => $user]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request){
		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$id || $id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('/admin/user/'.Auth::user()->id.'/edit')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		$user = new User;

		$input 					= $request->all();
		$input['email'] 		= Auth::user()->email;
		$input['phone_1'] 		= preg_replace("/[^0-9]/", "", $input['phone_1']);
		$input['phone_2'] 		= preg_replace("/[^0-9]/", "", $input['phone_2']);
		$input['description'] 	= preg_replace("/[^a-zA-Z0-9.,?¿#%&ñáéíóú ]+/", "", $input['description']);

		if (!$user->validate($input, null, null, $id)){
	        return redirect('/admin/user/'.$id.'/edit')->withErrors($user->errors());
	    }

	    $user = User::find($id);

	    if(!$user){
			abort(404);
		}

		$user->fill($input)->save();

		if($request->ajax()){
			return response()->json(['success' => trans('responses.user_saved')]);
		}
		return redirect('/admin/user/'.$id.'/edit')->withSuccess([trans('responses.user_saved')]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function password($id, Request $request){
		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$id || $id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('/admin/user/'.Auth::user()->id.'/edit')->withErrors([trans('responses.no_permission')]);
	    	}
		}

	    $user = User::find($id);

	    if(!$user){
			abort(404);
		}

		$this->validate($request, [
        	'current_password' 	=> 'required|string',
            'password' 			=> 'required|string|min:6|confirmed',
    	]);

    	if( Hash::check($request->get('current_password'), $user->password) ){
	    	$user->password = Hash::make($request->get('password'));
	    	$user->save();
    	}else{
    		return redirect('/admin/user/'.$id.'/edit')->withErrors([trans('responses.password_dont_match')]);
    	}

		return redirect('/admin/user/'.$id.'/edit')->withSuccess([trans('responses.password_changed_sucessfuly')]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function confirm($id, $code){
		//
	    $user = User::where('id', $id)->where('confirmation_code', $code)->first();

	    if(!$user){
			abort(403);
		}
	    
	    $user->confirmed = true;
	    $user->confirmation_code = null;
	    $user->save();

	    Auth::login($user);
		return redirect('/admin/user/'.$id.'/edit')->withSuccess([trans('responses.account_confirmed'), trans('responses.complete_profile')]);
	}

	public function sendConfirmationEmail(Request $request, $id = NULL){
		$user = null;
		if($id && Auth::check() && Auth::user()->isAdmin()){
			$user = User::find($id);
		}else{
			$user = Auth::user();
		}

		if(!$user){
			if($request->ajax()){
				return response()->json(['errors' => trans('responses.no_permission')]);
			}
			return redirect('admin')->withErrors([trans('responses.no_permission')]);
		}

		if(!$user->confirmed){
			$user->confirmation_code = str_random(64);
			$user->save();

			Queue::push(new SendUserConfirmationEmail($user));

			if($request->ajax()){
				return response()->json(['success' => trans('responses.confirmation_sent')]);
			}
			return view('admin.users.confirmation_sent');
		}

		if($request->ajax()){
			return response()->json(['errors' => trans('responses.account_already_confirmed')]);
		}
		return redirect('admin')->withErrors([trans('responses.account_already_confirmed')]);
	}

	public function notConfirmed(){
		if(!Auth::user()->confirmed){
			return view('admin.users.not_confirmed');
		}else{
			return redirect('admin')->withErrors([trans('responses.no_permission')]);
		}
	}

	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Request $request){
		$self = false;
		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$id || $id != Auth::user()->id){
	    		$self = true;
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('/admin/user/'.Auth::user()->id.'/edit')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		if($self){
			$user = Auth::user();
			$user->delete();

			Auth::logout();
			return redirect('/')->withSuccess([trans('responses.your_account_removed')]);
		}

		$user = User::find($id);
		$user->delete();

		if($request->ajax()){
			return response()->json(['success' => trans('responses.no_permission')]);
		}
		return redirect('/admin/users')->withSuccess([trans('responses.account_removed')]);
	}

}