<?php namespace App\Http\Controllers\APIV2;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Authorizer;
use Carbon;

use App\User;
use App\Models\Listing;
use App\Models\Feature;
use App\Models\Like;
use App\Models\Model;

// use	App\Events\ListingViewed;

class ListingController extends Controller {

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(){
        $this->middleware('oauth', ['only' => ['liked', 'like', 'userListings', 'store', 'update', 'renovate', 'destroy']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request){
		$query 			= Listing::remember(1)->active();
		$take 			= 30;
		$listings 		= null;

		// If there are search params set
		if(count($request->all()) > 0){
			// If user knows the listing code
			if($request->has('listing_code')){
				$listings = $query->where('code', $request->get('listing_code'))
								  ->with('listingType', 'featuredType')
								  ->paginate($take);
			}else{// If user didnt input listing code

				// If user input listing type - Venta o arriendo...
				if($request->has('listing_types')){
					$query = $query->whereIn('listing_type', $request->get('listing_types'));
				}

				// If user input city_id
				if($request->has('city_id')){
					$query = $query->where('city_id', $request->get('city_id'));
				}

				// If user input price_min & price_max
				if($request->has('price_min') && $request->has('price_max')){
					// If user set price_max at the input max dont limit max price
					if($request->get('price_max') >= 30000000){
						$query = $query->where('price', '>=', $request->get('price_min'));
					}else{// Else limit by min and max price
						$query = $query->WhereBetween('price', [$request->get('price_min')-1, $request->get('price_max')]);
					}
				}

				// If user input category_id - casas, apartamentos...
				if($request->has('engine_size_min') && $request->has('engine_size_max')){
					// If user set price_max at the input max dont limit max price
					if($request->get('engine_size_max') >= 1000){
						$query = $query->where('engine_size', '>=', $request->get('engine_size_min'));
					}else{// Else limit by min and max price
						$query = $query->WhereBetween('engine_size', [$request->get('engine_size_min')-1, $request->get('engine_size_max')]);
					}
				}

				// If user input category_id - casas, apartamentos...
				if($request->has('odometer_min') && $request->has('odometer_max')){
					// If user set price_max at the input max dont limit max price
					if($request->get('odometer_max') >= 50000){
						$query = $query->where('odometer', '>=', $request->get('odometer_min'));
					}else{// Else limit by min and max price
						$query = $query->WhereBetween('odometer', [$request->get('odometer_min')-1, $request->get('odometer_max')]);
					}
				}

				// If user input category_id - casas, apartamentos...
				if($request->has('year_min') && $request->has('year_max')){
					// If user set price_max at the input max dont limit max price
					if($request->get('year_min') <= 1970){
						$query = $query->whereBetween('year', [1, $request->get('year_max')]);
					}elseif($request->get('year_max') >= 2016){
						$query = $query->where('year', '>=', $request->get('year_min'));
					}else{// Else limit by min and max price
						$query = $query->whereBetween('year', [$request->get('year_min')-1, $request->get('year_max')]);
					}
				}

				// If user input manufacturers
				if($request->has('manufacturers') && is_array($request->get('manufacturers'))){
					$query = $query->whereIn('manufacturer_id', $request->get('manufacturers'));
				}

				// If user input manufacturers
				if($request->has('models')){
					$query = $query->whereIn('model_id', $request->get('models'));
				}

				// Order the query by params
				if($request->has('order_by')){
					if($request->get('order_by') == 'price_min'){
						$query = $query->orderBy('price', 'ASC')->orderBy('featured_type', 'DESC')->orderBy('points', 'DESC')->orderBy('featured_expires_at', 'DESC');
					}else if($request->get('order_by') == 'price_max'){
						$query = $query->orderBy('price', 'DESC')->orderBy('featured_type', 'DESC')->orderBy('points', 'DESC')->orderBy('featured_expires_at', 'DESC');
					}else if($request->get('order_by') == 'id_desc'){
						$query = $query->orderBy('id', 'DESC')->orderBy('featured_type', 'DESC')->orderBy('points', 'DESC')->orderBy('featured_expires_at', 'DESC');
					}else if($request->get('order_by') == 'id_asc'){
						$query = $query->orderBy('id', 'ASC')->orderBy('featured_type', 'DESC')->orderBy('points', 'DESC')->orderBy('featured_expires_at', 'DESC');
					}else if($request->get('order_by') == '0'){
						$query = $query->orderBy('featured_type', 'DESC')->orderBy('points', 'DESC')->orderBy('featured_expires_at', 'DESC');
					}
				}

				// Take n objects
				if($request->has('take')){
					$take = $request->get('take');
				}
			}// If user didnt input listing code
		}// Has params end

		if(!$request->has('order_by')){
			$query = $query->orderBy('featured_type', 'DESC')->orderBy('points', 'DESC')->orderBy('featured_expires_at', 'DESC');
		}

		if(!$request->has('listing_code')){
			$listings = $query->orderBy('id', 'DESC')->with('featuredType', 'images', 'listingType', 'city', 'user', 'features', 'manufacturer', 'model')->paginate($take);
		}


		return response()->json($listings);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){
		//
		$listing = Listing::where('id', $id)->with('features')->first();

		if(!$listing){
			return response()->json(['error' => ['code' => 404,
												 'message' => 'object not found',
												 ]
									], 404);
		}

		// Next, we will fire off an event and pass along 
	    //event(new ListingViewed($listing));

	    // if(Authorizer::getResourceOwnerId()){
	    // 	if(Like::where('listing_id', $listing->id)->where('user_id', Authorizer::getResourceOwnerId())->first()){
	    // 		$listing->like = true;
	    // 	}else{
	    // 		$listing->like = false;
	    // 	}
	    // }

		
		$features 	= Feature::remember(60)->with('category')->get();

		$related 	= Listing::remember(10)
							 ->where('id', '<>', $listing->id)
							 ->where('manufacturer_id', $listing->manufacturer_id)
							 ->orderBy('expires_at', 'DESC')
							 ->take(4)
							 ->get();

		$compare 	= Listing::remember(60)
							 ->where('id', '<>', $listing->id)
							 ->where('model_id', $listing->model_id)
							 ->orderBy('year', 'DESC')
							 ->take(10)
							 ->get();

		return response()->json(['data' => ['listing' 	=> $listing,
											'features' 	=> $features,
											'realated' 	=> $related,
											'compare' 	=> $compare,
											],
								]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request){
		// Max free listings limit
		$user = User::find(Authorizer::getResourceOwnerId());

		if(!$user->is('admin')){
			if(!$user->confirmed && $user->freeListingCount > 0){
				// Return not confirmed error
			}else if($user->confirmed && $user->freeListingCount >= 20){
				// Return free listings limit error
			}
		}
		

		$listing = new Listing;

		$input 				= $request->all();
		$input['district'] 	= preg_replace("/[^a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ ]+/u", "", $input['district']);
		$input['price'] 	= preg_replace("/[^0-9]/", "", $input['price']);
		$input['year'] 		= preg_replace("/[^0-9]/", "", $input['year']);
		$input['odometer'] 	= preg_replace("/[^0-9]/", "", $input['odometer']);
		$input['engine_size'] 	= 600;// TODO edit
		$input['listing_type'] 	= 2;// TODO edit
		$input['fuel_type'] 	= 1;// TODO edit
		$input['transmission_type'] = 2;// TODO edit
		$input['user_id'] 		= $user->id;

		if($input['model_id']){
			$model = Model::find($input['model_id']);
			if($model){
				$input['listing_type'] 	= $model->listing_type;// TODO edit
			}
		}

		if (!$listing->validate($input)){
			// Return validation errors
	        // return redirect('admin/listings/create')->withErrors($listing->errors())->withInput();
	    }

		$listing = $listing->create($input);

	    $listing->code 	= str_random(3).$listing->id;

		// Create the title and slug of the listing
		$title 				= ucwords($listing->manufacturer->name . 
				 			  ' ' . 
				 			  $listing->model->name .
				 			  ' ' .
				 			  $listing->year);
							  
		$listing->title 	= str_limit($title, $limit = 245, $end = '');
		$listing->slug 		= str_limit(str_slug($listing->title.'-'.$listing->listingType->name.'-'.$listing->city->name.'-'.$listing->code, '-'), $limit = 245, $end = '');

		// Set expiring date
		$listing->expires_at = Carbon::now()->addDays(30);

		// Set listing features
	    $listing->features()->attach($request->get('features'));


	    // Calculate points
	    $points = 0;
	    if(count($request->get('features')) > 0){
            $pointsFeatures = count($request->get('features')) * 2;
            if($pointsFeatures > 20){
                $points += 20;
            }else{
                $points += $pointsFeatures;
            }
        }

	    if($listing->description && strlen($listing->description) > 30){
	    	$pointsDescription = strlen($listing->description) * 0.1;
	    	if($pointsDescription > 30){
	    		$points += 30;
	    	}else{
	    		$points += $pointsDescription;
	    	}
	    }

	    $listing->points = $points;
	    // Calculate points

	    $listing->save();

	    // Return listing object
	    return response()->json(['data' => ['success' 	=> true,
	    									'id' 		=> $listing->id,
	    									],
	    						]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request){
		// Get the object requested
		$listing = Listing::find($id);
		$user = User::find(Authorizer::getResourceOwnerId());

		// Object not found
		if(!$listing){
			return response()->json(['error' => ['code' => 404,
												 'message' => 'object not found',
												 ]
									], 404);
		}

		// Security check
		if(!$user->is('admin')){
	    	if(!$listing || $listing->user_id != $user->id){
	    		return response()->json(['error' => ['code' => 401,
													 'message' => trans('responses.no_permission'),
													 ]
										], 401);
	    	}
		}

		$input 				= $request->all();
		$input['district'] 	= preg_replace("/[^a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ ]+/u", "", $input['district']);
		$input['price'] 	= preg_replace("/[^0-9]/", "", $input['price']);
		$input['year'] 		= preg_replace("/[^0-9]/", "", $input['year']);
		$input['odometer'] 	= preg_replace("/[^0-9]/", "", $input['odometer']);
		$input['engine_size'] = preg_replace("/[^0-9]/", "", $input['engine_size']);
		$input['fuel_type'] 	= 1;// TODO edit
		$input['transmission_type'] = 2;// TODO edit
		$input['user_id'] 	= $listing->user_id;

		if($input['model_id']){
			$model = Model::find($input['model_id']);
			if($model){
				$input['listing_type'] 	= $model->listing_type;// TODO edit
			}
		}

		if (!$listing->validate($input)){
			return response()->json(['error' => ['code' => 400,
												 'messages' => $listing->errors(),
												 ]
									], 400);
	    }

		// Update the listing
	    $listing->fill($input);

	    if(!$listing->code){
	    	$listing->code = str_random(3).$listing->id;
	    }
		
		// Create the title and slug of the listing
		$title 				= ucwords(strtolower($listing->manufacturer->name . 
				 			  ' ' . 
				 			  $listing->model->name .
				 			  ' ' .
				 			  $listing->year));
							  
		$listing->title 	= str_limit($title, $limit = 245, $end = '');
		$listing->slug 		= str_limit(str_slug($listing->title.'-'.$listing->listingType->name.'-'.$listing->city->name.'-'.$listing->code, '-'), $limit = 245, $end = '');

		// Set listing features
	    $listing->features()->sync($request->get('features'));

	    // Set image ordering
	    if(isset($input['image'])){
	    	$ordering = $input['image'];
		    foreach ($listing->images as $image) {
		    	$image->ordering = array_get($ordering, $image->id, null);
		    	if($image->ordering == 1){
		    		$listing->image_path = $image->image_path;
		    	}
		    }
	    }

	    // Calculate points
	    $points = 0;
	    if(isset($listing->images) && count($listing->images) > 0){
	    	if(count($listing->images) == 1){
	    		$points += 50;
	    	}else if(count($listing->images) > 1 && count($listing->images) < 5){
	    		$points += 50 + (count($listing->images)-1) * 10;
	    	}else{
	    		$points += 80;
	    	}
	    }

	    if(count($listing->features()) > 0){
            $pointsFeatures = count($listing->features()) * 2;
            if($pointsFeatures > 20){
                $points += 20;
            }else{
                $points += $pointsFeatures;
            }
        }

	    if($listing->description && strlen($listing->description) > 30){
	    	$pointsDescription = strlen($listing->description) * 0.1;
	    	if($pointsDescription > 30){
	    		$points += 30;
	    	}else{
	    		$points += $pointsDescription;
	    	}
	    }

	    $listing->points = $points;
	    // Calculate points
	    

	    // Save the listing and its relationships
	    $listing->push();

		// Return listing object
	    return response()->json(['data' => ['success' 	=> true,
	    									'id' 		=> $listing->id,
	    									],
	    						]);
	}


	/**
	 * Display the liked listings.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function userListings(){
		$user 	= User::find(Authorizer::getResourceOwnerId());
		$listings 	= $user->listings()->get();

		$listings->load('user', 'listingType', 'city', 'images', 'manufacturer', 'model', 'features');


		return response()->json(['data' => $listings,
								]);
	}

	/**
	 * Display the liked listings.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function liked(){
		$user 	= User::find(Authorizer::getResourceOwnerId());
		$query 	= $user->likes()->with('listing', 'listing.listingType', 'listing.featuredType')->get();

		return response()->json(['data' => $query,
								]);
	}

	/**
	 * Share by mail the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function like($id, Request $request){
		//
		$listing = Listing::find($id);

		if(!$listing){
			return response()->json(['error' => ['code' => 404,
												 'message' => 'object not found',
												 ]
									], 404);
		}

		$like = Like::where('user_id', Authorizer::getResourceOwnerId())->where('listing_id', $id)->first();

		$liked = false;
		if($like){
			$like->delete();
		}else{
			$liked = true;
			Like::create(['user_id' 	=> Authorizer::getResourceOwnerId(),
						  'listing_id'	=> $id,
						  ]);
		}

		return response()->json(['data'	=> ['listing' 	=> $id,
											'liked' 	=> $liked,
											],
								]);
	}

	/**
	 * Renovate the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function renovate($id, Request $request){
		// Get the object requested
		$listing = Listing::find($id);
		$user = User::find(Authorizer::getResourceOwnerId());

		// Security check
	    if(!$user->is('admin')){
	    	if(!$listing || $listing->user_id != $user->id){
	    		return response()->json(['error' => ['code' => 401,
													 'message' => trans('responses.no_permission'),
													 ]
										], 401);
	    	}
		}

		// Only renovate if is expiring in less than 5 days
		if($listing->expires_at > Carbon::now()->addDays(5)){
			return response()->json(['error' => ['code' => 403,
												 'message' => trans('responses.listing_renovation_error'),
												 ]
									], 403);
		}

		// The listing will expire in n days from now
		$listing->expires_at 		= Carbon::now()->addDays(30);
		$listing->expire_notified 	= false;
		$listing->save();

		$listing->load('user', 'listingType', 'city', 'images', 'manufacturer', 'model', 'features');

		// Return response in ajax or sync
		return response()->json(['data' => $listing]);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Request $request){
		// Get the object requested
		$listing = Listing::where('id', $id)->withTrashed()->first();
		$user = User::find(Authorizer::getResourceOwnerId());

		// Security check
	    if(!$user->is('admin')){
	    	if(!$listing || $listing->user_id != $user->id){
	    		return response()->json(['error' => ['code' => 401,
													 'message' => trans('responses.no_permission'),
													 ]
										], 401);
	    	}
		}

		if(!$listing){
			return response()->json(['error' => ['code' => 404,
												 'message' => 'object not found',
												 ]
									], 404);
		}

		// If listing is already softdeleted force its delete
		if($listing->deleted_at){
			$listing->forceDelete();
		}else{
			// Delete listing
			$listing->delete();
		}

		return response()->json(['data' => ['success' => true,
											'message' => trans('responses.listing_deleted')],
								]);
	}
}

