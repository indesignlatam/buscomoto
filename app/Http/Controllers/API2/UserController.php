<?php namespace App\Http\Controllers\API2;

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
		$this->middleware('oauth', ['only' => ['index', 'edit', 'password', 'update', 'sendConfirmationEmail']]);
		$this->middleware('throttle', ['only' => ['confirm', 'sendConfirmationEmail']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		//
		// $users = User::with('listingCount')->orderBy('id', 'DESC')->paginate(30);
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
	public function show($id){
		//
		$user = User::where('id', $id)->first();

		if(!$user){
			return response()->json(['error' => ['code' => 404,
												 'message' => 'object not found',
												 ]
									], 404);
		}

		$user->load('listings', 'listings.listingType', 'listings.featuredType');

		return response()->json(['data' => $user,
								]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getUserByEmail(Request $request){
		//
		$user = User::where('email', $request->get('email'))->first();

		if(!$user){
			return response()->json(['error' => ['code' => 404,
												 'message' => 'object not found',
												 ]
									], 404);
		}
		
		return response()->json(['data' => $user,
								]);
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

	public function sendConfirmationEmail($id = null, Request $request){
		$user = User::find(Authorizer::getResourceOwnerId());

		if($id && $user && $user->isAdmin()){
			$user = User::find($id);
		}

		if(!$user){
			return response()->json(['error' => ['code' => 404,
												 'message' => 'object not found',
												 ]
									], 404);
		}

		if(!$user->confirmed){
			$user->confirmation_code = str_random(64);
			$user->save();

			Queue::push(new SendUserConfirmationEmail($user));

			return response()->json(['success' => true]);
		}

		return response()->json(['error' => ['code' => 400,
											 'message' => 'User already confirmed',
											 ]
								], 400);
	}

	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Request $request){
		//
		$user = User::find(Authorizer::getResourceOwnerId());

		if($id && $user && $user->isAdmin()){
			$user = User::find($id);
		}

		$user->delete();

		return response()->json(['success' => true]);
	}

}