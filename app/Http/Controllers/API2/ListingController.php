<?php namespace App\Http\Controllers\API2;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Settings;
use Authorizer;

use App\User;
use App\Models\Listing;
use App\Models\Feature;
use App\Models\Like;

use	App\Events\ListingViewed;

class ListingController extends Controller {

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(){
        $this->middleware('oauth', ['only' => ['liked', 'like', 'userListings']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request){
		$query 			= Listing::remember(Settings::get('query_cache_time_extra_short'))->active();
		$take 			= Settings::get('pagination_objects');
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
			$listings = $query->orderBy('id', 'DESC')->with('featuredType', 'images', 'listingType', 'city', 'user', 'features')->paginate($take);
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
	    event(new ListingViewed($listing));

	    // if(Authorizer::getResourceOwnerId()){
	    // 	if(Like::where('listing_id', $listing->id)->where('user_id', Authorizer::getResourceOwnerId())->first()){
	    // 		$listing->like = true;
	    // 	}else{
	    // 		$listing->like = false;
	    // 	}
	    // }

		
		$features 	= Feature::remember(Settings::get('query_cache_time'))->with('category')->get();

		$related 	= Listing::remember(Settings::get('query_cache_time_short'))
							 ->where('id', '<>', $listing->id)
							 ->where('manufacturer_id', $listing->manufacturer_id)
							 ->orderBy('expires_at', 'DESC')
							 ->take(4)
							 ->get();

		$compare 	= Listing::remember(Settings::get('query_cache_time_short'))
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
	 * Display the liked listings.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function userListings(){
		$user 	= User::find(Authorizer::getResourceOwnerId());
		$query 	= $user->listings()->get();

		return response()->json(['data' => $query,
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
		$query 	= $user->likes()->with('listing', 'listing.listingType', 'listing.featuredType')->get(20);

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
}