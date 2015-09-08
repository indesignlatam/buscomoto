<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Gmaps;
use Image;
use Cookie;
use	Settings;
use	Carbon;
use DB;
use Agent;
use Auth;

use App\Models\Listing;
use App\Models\EngineSize;
use	App\Models\ListingType;
use	App\Models\TransmissionType;
use	App\Models\FuelType;
use	App\Models\Manufacturer;
use	App\Models\Model;
use	App\Models\Feature;
use	App\Models\City;
use	App\Models\Like;
use	App\Events\ListingViewed;

class ListingFEController extends Controller {

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(){
        $this->middleware('listings.view.throttle', ['only' => ['show']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request){
		$mobile 		= Agent::isMobile();
		$query 			= Listing::remember(Settings::get('query_cache_time_extra_short'))->active();
		$take 			= Settings::get('pagination_objects');

		// Only if not mobile
		$featuredTop = null;
		$view 		= 'listings.index';

		if(!$mobile){
			// Featured Listings Top
			$fTopQuery 	= null;
			$fTopQuery = Listing::remember(Settings::get('query_cache_time_extra_short', 1))->where('featured_expires_at', '>', DB::raw('now()'));
			if($request->has('listing_type_id')){
				$fTopQuery = $fTopQuery->where('listing_type', $request->get('listing_type_id'));
			}
			$featuredTop = $fTopQuery->take(8)
								  	 ->orderByRaw("RAND()")
								  	 ->with('listingType', 'featuredType')
								  	 ->get();
			// Featured Listings Top End
	  	}else{ // If is mobile
	  		// Set the view to mobile view
	  		$view = 'listings.mobile.index';
	  	}

	  	$engineSizes 	= EngineSize::remember(Settings::get('query_cache_time'))->get();
		$listingTypes 	= ListingType::remember(Settings::get('query_cache_time'))->get();
		$manufacturers 	= Manufacturer::selectRaw('id, name AS text')->remember(Settings::get('query_cache_time'))->get();
		$cities 		= City::selectRaw('id, name AS text')->remember(Settings::get('query_cache_time'))->orderBy('ordering')->get();

		return view($view, ['featuredListings' 	=> $featuredTop,
							'listingTypes' 		=> $listingTypes,
							'cities' 			=> $cities, 
							'engineSizes' 		=> $engineSizes ,
							'manufacturers'		=> $manufacturers,
							]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function indexAPI(Request $request){
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
				if($request->has('listing_type')){
					$query = $query->where('listing_type', $request->get('listing_type'));
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
					$query = $query->where('model_id', $request->get('models'));
				}

				// Order the query by params
				if($request->has('order_by')){
					if($request->get('order_by') == 'price_min'){
						session(['listings_order_by' => 'price_min']);
						$query = $query->orderBy('price', 'ASC')->orderBy('featured_type', 'DESC')->orderBy('featured_expires_at', 'DESC');
					}else if($request->get('order_by') == 'price_max'){
						session(['listings_order_by' => 'price_max']);
						$query = $query->orderBy('price', 'DESC')->orderBy('featured_type', 'DESC')->orderBy('featured_expires_at', 'DESC');
					}else if($request->get('order_by') == 'id_desc'){
						session(['listings_order_by' => 'id_desc']);
						$query = $query->orderBy('id', 'DESC')->orderBy('featured_type', 'DESC')->orderBy('featured_expires_at', 'DESC');
					}else if($request->get('order_by') == 'id_asc'){
						session(['listings_order_by' => 'id_asc']);
						$query = $query->orderBy('id', 'ASC')->orderBy('featured_type', 'DESC')->orderBy('featured_expires_at', 'DESC');
					}else if($request->get('order_by') == '0'){
						session()->forget('listings_order_by');
						$query = $query->orderBy('featured_type', 'DESC')->orderBy('featured_expires_at', 'DESC');
					}
				}

				// Take n objects
				if($request->has('take')){
					if($request->get('take')){
						$request->session()->put('listings_take', $request->get('take'));
						$take = $request->get('take');
					}
				}
			}// If user didnt input listing code
		}// Has params end

		// Order the query by cookie
		if(!$request->has('order_by') && $request->session()->has('listings_order_by')){
			if(session('listings_order_by') == 'price_min'){
				$query = $query->orderBy('price', 'ASC')->orderBy('featured_type', 'DESC')->orderBy('featured_expires_at', 'DESC');
			}else if(session('listings_order_by') == 'price_max'){
				$query = $query->orderBy('price', 'DESC')->orderBy('featured_type', 'DESC')->orderBy('featured_expires_at', 'DESC');
			}else if(session('listings_order_by') == 'id_desc'){
				$query = $query->orderBy('id', 'DESC')->orderBy('featured_type', 'DESC')->orderBy('featured_expires_at', 'DESC');
			}else if(session('listings_order_by') == 'id_asc'){
				$query = $query->orderBy('id', 'ASC')->orderBy('featured_type', 'DESC')->orderBy('featured_expires_at', 'DESC');
			}
		}else{
			$query = $query->orderBy('featured_type', 'DESC')->orderBy('featured_expires_at', 'DESC');
		}

		// Take n objects by cookie
		if(!$request->has('take') && $request->session()->has('listings_take')){
			if($request->session()->get('listings_take')){
				$take = $request->session()->get('listings_take');
			}
		}

		if(!$request->has('listing_code')){
			$listings = $query->orderBy('id', 'DESC')->with('featuredType')->paginate($take);
		}
		return $listings;
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){
		//
		$listing = Listing::where('slug', $id)->first();

		if(!$listing){
			abort(404);
		}

		// Next, we will fire off an event and pass along 
	    event(new ListingViewed($listing));

	    if(Auth::check()){
	    	if(Like::where('listing_id', $listing->id)->where('user_id', Auth::user()->id)->first()){
	    		$listing->like = true;
	    	}else{
	    		$listing->like = false;
	    	}
	    }

		
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

		return view('listings.show', [ 'listing' 	=> $listing,
									   'related' 	=> $related,
									   'features' 	=> $features,
									   'compare'	=> $compare,
									]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function showLikedListings(){
		//
		$query = null;
		if(Auth::check()){
			$query = Auth::user()->likes()->paginate(20);
			if(count($query) > 0){
				$query->load('listing', 'listing.listingType', 'listing.featuredType');
			}
		}else if(Cookie::get('likes') && is_array(Cookie::get('likes')) && count(Cookie::get('likes')) > 0){
			$ids = array_keys(Cookie::get('likes'));
			$query = Listing::whereIn('id', $ids)->with('listingType', 'featuredType')->paginate(30);
		}
		


		return view('listings.showLikedListings', [ 'likes' 	=> $query,
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
			abort(404);
		}

		$likesCookie = null;
		$liked = true;

		if(Auth::check()){
			$like = Like::where('user_id', Auth::user()->id)->where('listing_id', $id)->first();

			if($like){
				$liked = false;
				$like->delete();
			}else{
				Like::create(['user_id' 	=> Auth::user()->id,
							  'listing_id'	=> $id,
							  ]);
			}
		}else{
			if($likesCookie = Cookie::get('likes')){
				if(isset($likesCookie[$id]) && $likesCookie[$id]){
					$liked = false;
					array_forget($likesCookie, $id);
				}else{
					$likesCookie[$id] = true;
				}
			}else{
				$likesCookie[$id] = true;
			}

			Cookie::queue('likes', $likesCookie, 525600);
		}

		return response()->json(['success'	=> trans('responses.listing_liked'),
								 'like'		=> $liked,
								]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function compare(Request $request){
		$listingsIds = Cookie::get('selected_listings');
		$listings = null;

		if(count($listingsIds) > 1){
			$listings = Listing::whereIn('id', $listingsIds)->take(4)->with('features', 'listingType')->get();
		}else if($listingsIds){
			$listings = Listing::where('id', $listingsIds)->take(4)->with('features', 'listingType')->get();
		}

		$features = Feature::remember(Settings::get('query_cache_time'))->with('category')->get();

		return view('listings.compare', ['listings' => $listings,
										 'features' => $features
										 ]);
	}
}