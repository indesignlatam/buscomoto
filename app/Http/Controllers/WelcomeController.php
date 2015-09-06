<?php namespace App\Http\Controllers;

use Settings;

use App\Models\Listing;
use App\Models\City;
use App\Models\ListingType;
use App\Models\EngineSize;
use App\Models\Manufacturer;
use App\Models\Model;

class WelcomeController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(){
		//
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index(){
		$turism = Listing::remember(Settings::get('query_cache_time_extra_short'))
						 ->active()
						 ->where('listing_type', 2)
						 ->whereNotNull('image_path')
						 ->where('image_path', '<>', '')
						 ->with('listingType')
						 ->orderBy('featured_type', 'DESC')
						 ->orderBy('featured_expires_at', 'DESC')
						 ->take(12)
						 ->get();

		$sport 	= Listing::remember(Settings::get('query_cache_time_extra_short'))
						 ->active()
						 ->where('listing_type', 3)
						 ->whereNotNull('image_path')
						 ->where('image_path', '<>', '')
						 ->with('listingType')
						 ->orderBy('featured_type', 'DESC')
						 ->orderBy('featured_expires_at', 'DESC')
						 ->take(12)
						 ->get();

		$street = Listing::remember(Settings::get('query_cache_time_extra_short'))
						 ->active()
						 ->where('listing_type', 1)
						 ->whereNotNull('image_path')
						 ->where('image_path', '<>', '')
						 ->with('listingType')
						 ->orderBy('featured_type', 'DESC')
						 ->orderBy('featured_expires_at', 'DESC')
						 ->take(12)
						 ->get();

		$cross 	= Listing::remember(Settings::get('query_cache_time_extra_short'))
						 ->active()
						 ->where('listing_type', 5)
						 ->whereNotNull('image_path')
						 ->where('image_path', '<>', '')
						 ->with('listingType')
						 ->orderBy('featured_type', 'DESC')
						 ->orderBy('featured_expires_at', 'DESC')
						 ->take(12)
						 ->get();

		$featured 	= Listing::remember(Settings::get('query_cache_time_extra_short'))
							 ->active()
							 ->where('featured_type', '>', 2)
							 ->whereNotNull('image_path')
							 ->where('image_path', '<>', '')
							 ->with('listingType')
							 ->orderByRaw('RAND()')
							 ->take(8)
							 ->get();

		$engineSizes 	= EngineSize::remember(Settings::get('query_cache_time'))->get();
		$listingTypes 	= ListingType::remember(Settings::get('query_cache_time'))->get();
		$priceRanges 	= [	['id' => 1, 'name' => '0 - 3,000,000'],
							['id' => 2, 'name' => '3,000,000 - 5,000,000'],
							['id' => 3, 'name' => '5,000,000 - 10,000,000'],
							['id' => 4, 'name' => '10,000,000 - 30,000,000'],
							['id' => 5, 'name' => '30,000,000 o +']
							];
		$manufacturers 	= Manufacturer::selectRaw('id, name AS text')->remember(Settings::get('query_cache_time'))->get();

		return view('welcome', ['engineSizes' 			=> $engineSizes,
								'priceRanges' 			=> $priceRanges,
								'listingTypes' 			=> $listingTypes,
								'manufacturers' 		=> $manufacturers,
								'turism' 				=> $turism,
								'sport'					=> $sport,
								'cross'					=> $cross,
								'street'				=> $street,
								'featured'				=> $featured,
								]);
	}

}