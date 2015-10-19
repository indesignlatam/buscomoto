<?php namespace App\Http\Controllers\APIV2;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Carbon;
use Queue;
use Authorizer;

use App\Models\Appointment;
use	App\Models\Listing;
use App\Jobs\SendNewMessageEmail;
use	App\Jobs\RespondMessageEmail;
use	App\Events\ListingMessaged;

class MessageController extends Controller {

	/**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('oauth', ['only' => ['index', 'answer']]);
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request){
		//
		$query = null;
		$take = 30;

		$query = Appointment::leftJoin('listings',
							function($join) {
								$join->on('messages.listing_id', '=', 'listings.id');
							})
							->select('messages.id', 'name', 'email', 'phone', 'comments', 'read', 'answered', 'messages.user_id', 'messages.listing_id', 'listings.user_id', 'messages.created_at')
							->where('listings.user_id', Authorizer::getResourceOwnerId())
							->with('listing');


		if(count($request->all()) > 0){
			if($request->has('search')){
				$search = $request->search;
				$query = $query->where('name', 'LIKE', "%$search%");
			}

			if($request->get('deleted')){
				$query = $query->onlyTrashed();
			}

			// Order the objects
			if($request->has('order_by')){
				if($request->get('order_by') == 'id_asc'){
					$query = $query->orderBy('id', 'ASC');
				}else if($request->get('order_by') == 'id_desc'){
					$query = $query->orderBy('id', 'DESC');
				}
			}else{
				$query = $query->orderBy('answered', 'ASC')->orderBy('messages.created_at', 'DESC');
			}

			// Take n objects
			if($request->has('take')){
				$take = $request->get('take');
			}
		}else{
			$query = $query->orderBy('answered', 'ASC')->orderBy('messages.created_at', 'DESC');
		}

		// Execute the query
		$objects = $query->paginate($take);


		return response()->json($objects);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request){
		// Get object to work with
		$appointment = new Appointment;

		$count = Appointment::where('uuid', $request->get('uuid'))
						   	->where('listing_id', $request->get('listing_id'))
						   	->whereRaw('{fn TIMESTAMPDIFF( HOUR, created_at, now() )} < 24')
						   	->count();

		if($count > 0){
			return response()->json(['error' => ['code' 	=> 420,
	        									 'messages' => ['message_already_sent'],
	        									]
	        						], 420);
		}
				

		// Validate the request
		if (!$appointment->validate($request->all())){
	        return response()->json(['error' => ['code' 	=> 400,
	        									 'messages' => $appointment->errors(),
	        									]
	        						], 400);
	    }

	    $input = $request->all();
	    
	    // Create the object
		$appointment = $appointment->create($input);

		// User is not confirmed
		$listing = Listing::find($appointment->listing_id);

		if(!$listing || !isset($listing->user) || !$listing->user()->confirmed){
			return response()->json(['data' => ['message' 			=> $appointment,
												'user_confirmed' 	=> false,
	        									],
	        						]);
		}

		// Queue the Send Email command
		//Queue::push(new SendNewMessageEmail($appointment));

		return response()->json(['data' => ['message' 			=> $appointment,
											'user_confirmed'	=> true,
        									],
	        						]);
	}

	/**
	 * Answer the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function answer($id, Request $request){
		// Get the object requested
		$message = Appointment::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$message || $message->listing->user->id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/appointments')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Set the message as readed and answered
		$message->answered 	= true;
		$message->read 		= true;
		$message->save();

		$comments = $request->get('comments');

		// Queue the Send Email command
		// Queue::push(new RespondMessageEmail($comments, $message));

		// Return the response in ajax or sync
		if($request->ajax()){
			return response()->json(['success' => trans('responses.message_sent')]);
		}
		return redirect('admin/appointments')->withSuccess([trans('responses.message_sent')]);
	}

	/**
	 * Mark or unmark the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function markAsRead($id, Request $request){
		// Get the object requested
		$message = Appointment::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$message || $message->listing->user->id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/appointments')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Set the message as readed and answered
		$message->read 		= $request->get('mark');
		$message->answered 	= $request->get('mark');
		$message->save();

		// Return the response in ajax or sync
		if($request->ajax()){
			return response()->json(['success'  => trans('responses.message_marked_'.(bool)$message->read),
									 'mark' 	=> (bool)$message->read,
									 ]);
		}
		return redirect('admin/appointments')->withSuccess([trans('responses.message_marked_'.(bool)$message->read)]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Request $request){
		// Get the object requested
		$message = Appointment::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$message || $message->listing->user->id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/appointments')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Remove object
		$message->delete();

		// Return the response in ajax or sync
		if($request->ajax()){
			return response()->json(['success' => trans('responses.message_deleted')]);
		}
		return redirect('admin/appointments')->withSuccess([trans('responses.message_deleted')]);
	}

}