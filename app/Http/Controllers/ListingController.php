<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use Gmaps;
use Geocoder;
use Carbon;
use Settings;
use Queue;

use App\Models\Listing;
use App\Models\EngineSize;
use	App\Models\ListingType;
use	App\Models\TransmissionType;
use	App\Models\FuelType;
use	App\Models\Manufacturer;
use	App\Models\Model;
use	App\Models\Feature;
use	App\Models\City;
use	App\Models\FeaturedType;

use App\Jobs\ListingShare;

class ListingController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request){
		$query = null;
		$take = Settings::get('pagination_objects');

		// Create the principal query
		if(Auth::user()->isAdmin()){
			$query = Listing::whereNotNull('id');
		}else{
			$query = Listing::where('user_id', Auth::user()->id);
		}

		if(count($request->all()) > 0){
			if($request->has('search')){
				$search = $request->search;
				$query = $query->where('slug', 'LIKE', "%$search%");
			}

			if($request->get('deleted')){
				$query = $query->onlyTrashed();
			}

			// Order the objects
			if($request->has('order_by')){
				if($request->get('order_by') == 'exp_desc'){
					$query = $query->orderBy('expires_at', 'ASC');
				}else if($request->get('order_by') == 'id_desc'){
					$query = $query->orderBy('id', 'DESC');
				}					
			}else{
				$query = $query->orderBy('expires_at', 'DESC')->orderBy('id', 'DESC');
			}

			// Take n objects
			if($request->has('take')){
				$take = $request->take;
			}
		}else{
			$query = $query->orderBy('expires_at', 'ASC')->orderBy('featured_expires_at', 'ASC')->orderBy('id', 'DESC');
		}

		// Execute the query
		if(Auth::user()->isAdmin()){
			$objects = $query->with('user')->paginate($take);
		}else{
			$objects = $query->with('listingType', 'featuredType', 'images', 'features')->paginate($take);
		}

		return view('admin.listings.index', ['listings' => $objects]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request){
		// Max free listings limit
		if(!Auth::user()->is('admin')){
			if(!Auth::user()->confirmed && Auth::user()->freeListingCount > 0){
				return redirect('admin/user/not_confirmed');
			}else if(Auth::user()->confirmed && Auth::user()->freeListingCount >= Settings::get('free_listings_limit', 10)){
				return redirect('admin/listings/limit');
			}
		}

		$manufacturers 	= Manufacturer::selectRaw('id, name AS text')->remember(Settings::get('query_cache_time'))->get();
		$listingTypes 	= ListingType::remember(Settings::get('query_cache_time'))->get();
		$features 		= Feature::remember(Settings::get('query_cache_time'))->with('category')->get();
		$cities 		= City::remember(Settings::get('query_cache_time'))->orderBy('ordering', 'ASC')->with('department')->get();
		$transmissions 	= TransmissionType::remember(Settings::get('query_cache_time'))->get();
		$fuels 			= FuelType::remember(Settings::get('query_cache_time'))->get();


		return view('admin.listings.new', [ 'manufacturers' 	=> $manufacturers, 
											'listingTypes' 		=> $listingTypes, 
											'cities' 			=> $cities,
											'features' 			=> $features, 
											'transmissions' 	=> $transmissions, 
											'fuels' 			=> $fuels, 
											]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request){
		// Max free listings limit
		if(!Auth::user()->is('admin')){
			if(!Auth::user()->confirmed && Auth::user()->freeListingCount > 0){
				return redirect('admin/not_confirmed');
			}else if(Auth::user()->confirmed && Auth::user()->freeListingCount >= Settings::get('free_listings_limit', 10)){
				return redirect('admin/listings/limit');
			}
		}
		

		$listing = new Listing;

		$input 				= $request->all();
		$input['district'] 	= preg_replace("/[^a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ ]+/u", "", $input['district']);
		$input['price'] 	= preg_replace("/[^0-9]/", "", $input['price']);
		$input['year'] 		= preg_replace("/[^0-9]/", "", $input['year']);
		$input['odometer'] 	= preg_replace("/[^0-9]/", "", $input['odometer']);
		$input['engine_size'] = preg_replace("/[^0-9]/", "", $input['engine_size']);
		$input['user_id'] 	= Auth::user()->id;

		// If year input is less than 500 is not a year is the age so substract current year - age
		if((int)$input['year'] < 500){
			$input['year'] = date('Y') - $input['year'];
		}


		if (!$listing->validate($input)){
	        return redirect('admin/listings/create')->withErrors($listing->errors())->withInput();
	    }

		$listing = $listing->create($input);

	    $listing->code 	= str_random(3).$listing->id;

		$unwanted_array = [ 'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' ];

		// Create the title and slug of the listing
		$title 				= ucwords(strtolower($listing->manufacturer->name . 
				 			  ' ' . 
				 			  $listing->model->name .
				 			  ' ' .
				 			  $listing->year .
				 			  ' - ' )).
				 			  $listing->listingType->name;
							  
		$listing->title 	= str_limit($title, $limit = 245, $end = '');
		$listing->slug 		= str_limit(str_slug($listing->title. '-' .$listing->city->name.'-'.$listing->code, '-'), $limit = 245, $end = '');

		// Set expiring date
		$listing->expires_at = Carbon::now()->addDays(Settings::get('listing_expiring'));

		// Set listing features
		$features = Feature::all();
	    $featuresSelected = [];
	    foreach ($features as $feature) {
	    	if($request->has($feature->id)){
	    		$featuresSelected[] = $feature->id;
	    	}
	    }
	    $listing->features()->attach($featuresSelected);

	    $listing->save();

		return redirect('admin/listings/'.$listing->id.'/edit')->withSuccess([trans('responses.listing_created')]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id){
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id){
		// Get the object requested
		$listing = Listing::find($id);

		// Security check
	    if(!Auth::user()->isAdmin()){
	    	if(!$listing || $listing->user->id != Auth::user()->id){
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		$manufacturers 	= Manufacturer::selectRaw('id, name AS text')->remember(Settings::get('query_cache_time'))->get();
		$listingTypes 	= ListingType::remember(Settings::get('query_cache_time'))->get();
		$features 		= Feature::remember(Settings::get('query_cache_time'))->with('category')->get();
		$cities 		= City::remember(Settings::get('query_cache_time'))->orderBy('ordering', 'ASC')->with('department')->get();
		$transmissions 	= TransmissionType::remember(Settings::get('query_cache_time'))->get();
		$fuels 			= FuelType::remember(Settings::get('query_cache_time'))->get();


		return view('admin.listings.edit', ['listing'			=> $listing,
											'manufacturers' 	=> $manufacturers, 
											'listingTypes' 		=> $listingTypes, 
											'cities' 			=> $cities,
											'features' 			=> $features, 
											'transmissions' 	=> $transmissions, 
											'fuels' 			=> $fuels, 
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

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->user->id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
				}
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		$input 				= $request->all();
		$input['district'] 	= preg_replace("/[^a-zA-Z0-9ñáéíóúÁÉÍÓÚÑ ]+/u", "", $input['district']);
		$input['price'] 	= preg_replace("/[^0-9]/", "", $input['price']);
		$input['year'] 		= preg_replace("/[^0-9]/", "", $input['year']);
		$input['odometer'] 	= preg_replace("/[^0-9]/", "", $input['odometer']);
		$input['engine_size'] = preg_replace("/[^0-9]/", "", $input['engine_size']);
		$input['user_id'] 	= Auth::user()->id;

		// If year input is less than 500 is not a year is the age so substract current year - age
		if((int)$input['year'] < 500){
			$input['year'] = date('Y') - $input['year'];
		}


		if (!$listing->validate($input)){
	        return redirect('admin/listings/create')->withErrors($listing->errors())->withInput();
	    }

		// Update the listing
	    $listing->fill($input);

	    if(!$listing->code){
	    	$listing->code = str_random(3).$listing->id;
	    }

	    $unwanted_array = [ 'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' ];
		
		// Create the title and slug of the listing
		$title 				= ucwords(strtolower($listing->manufacturer->name . 
				 			  ' ' . 
				 			  $listing->model->name .
				 			  ' ' .
				 			  $listing->year .
				 			  ' - ' )).
				 			  $listing->listingType->name;
							  
		$listing->title 	= str_limit($title, $limit = 245, $end = '');
		$listing->slug 		= str_limit(str_slug($listing->title. '-' .$listing->city->name.'-'.$listing->code, '-'), $limit = 245, $end = '');

		// Set listing features
	    $features = Feature::all();
	    $featuresSelected = [];
	    foreach ($features as $feature) {
	    	if($request->has($feature->id)){
	    		$featuresSelected[] = $feature->id;
	    	}
	    }
	    $listing->features()->sync($featuresSelected);

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
	    

	    // Save the listing and its relationships
	    $listing->push();

		// If save and close button redirect to my listings	    
	    if($request->get('save_close')){
			return redirect('admin/listings')->withSuccess([trans('responses.listing_saved')]);
	    }
		return redirect('admin/listings/'.$id.'/edit')->withSuccess([trans('responses.listing_saved')]);
	}

	/**
	 * Share by mail the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function share($id, Request $request){
		//
		$listing = Listing::find($id);

		if(!$listing){
			abort(404);
		}

		Queue::push(new ListingShare($listing, $request->email, $request->message));

		return response()->json(['success' =>  trans('responses.message_sent'),
								]);
	}

	/**
	 * Show renovation dialog for resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function limitShow(){
		return view('admin.listings.limit_reached');
	}

	/**
	 * Show renovation dialog for resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function renovateShow($id, Request $request){
		// Get the object requested
		$listing = Listing::find($id);

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->user->id != Auth::user()->id){
	    		if($request->ajax()){// If request was sent using ajax
					return response()->json(['error' => trans('responses.no_permission')]);
	    		}
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Get all featured types and cache them
		$featuredTypes = FeaturedType::remember(Settings::get('query_cache_time'))->get();

		return view('admin.listings.renovate', ['listing' 		=> $listing,
												'featuredTypes' => $featuredTypes,
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

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->user->id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
	    		}
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Only renovate if is expiring in less than 5 days
		if($listing->expires_at > Carbon::now()->addDays(5)){
			return redirect('admin/listings')->withErrors([trans('responses.listing_renovation_error')]);
		}

		// The listing will expire in n days from now
		$listing->expires_at 		= Carbon::now()->addDays(Settings::get('listing_expiring'));
		$listing->expire_notified 	= false;

		$listing->save();

		// Return response in ajax or sync
		if($request->ajax()){
			return response()->json(['success' => trans('responses.listing_renovated')]);
		}
		return redirect('admin/listings/')->withSuccess([trans('responses.listing_renovated')]);
	}

	/**
	 * Recover softdeleted resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function recover($id, Request $request){
		// Get the object requested
		$listing = Listing::onlyTrashed()->where('id', $id)->first();

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->user->id != Auth::user()->id){
	    		if($request->ajax()){
					return response()->json(['error' => trans('responses.no_permission')]);
	    		}
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// Max free listings limit
		if(!Auth::user()->confirmed && Auth::user()->freeListingCount > 0){
			return redirect('admin/not_confirmed');
		}else if(Auth::user()->confirmed && Auth::user()->freeListingCount >= Settings::get('free_listings_limit', 10)){
			return redirect('admin/listings/limit');
		}

		// Restore the listing
		$listing->restore();

		return redirect('admin/listings')->withSuccess([trans('responses.listing_recovered')]);
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

		// Security check
	    if(!Auth::user()->is('admin')){
	    	if(!$listing || $listing->user->id != Auth::user()->id){
	    		if($request->ajax()){// If request was sent using ajax
					return response()->json(['error' => trans('responses.no_permission')]);
				}
				// If nos usign ajax return redirect
	        	return redirect('admin/listings')->withErrors([trans('responses.no_permission')]);
	    	}
		}

		// If listing is already softdeleted force it deletes
		if($listing->deleted_at){
			$listing->forceDelete();
		}else{
			// Delete listing
			$listing->delete();
		}


		if($request->ajax()){// If request was sent using ajax
			return response()->json(['success' => trans('responses.listing_deleted')]);
		}
		// If nos usign ajax return redirect
		return redirect('admin/listings')->withSuccess([trans('responses.listing_deleted')]);
	}

}