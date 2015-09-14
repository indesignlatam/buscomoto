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
		$featured 	= Listing::remember(Settings::get('query_cache_time_extra_short'))
							 ->active()
							 ->where('featured_type', '>', 2)
							 ->whereNotNull('image_path')
							 ->where('image_path', '<>', '')
							 ->with('listingType')
							 ->orderByRaw('RAND()')
							 ->take(8)
							 ->get();

		$newBikes 	= Listing::remember(Settings::get('query_cache_time_extra_short'))
							 ->active()
							 ->whereNotNull('image_path')
							 ->where('image_path', '<>', '')
							 ->with('listingType')
							 ->orderBy('featured_type', 'DESC')
							 ->orderBy('featured_expires_at', 'DESC')
							 ->take(20)
							 ->get();

		$listingTypes 	= ListingType::remember(Settings::get('query_cache_time'))->get();
		$priceRanges 	= [	['id' => 3000000, 'name' => '3,000,000'],
							['id' => 5000000, 'name' => '5,000,000'],
							['id' => 10000000, 'name' => '10,000,000'],
							['id' => 30000000, 'name' => '30,000,000'],
							['id' => 50000000, 'name' => '50,000,000 o más']
							];
		$manufacturers 	= Manufacturer::selectRaw('id, name AS text')->orderBy('name', 'ASC')->remember(Settings::get('query_cache_time'))->get();

		return view('welcome', ['priceRanges' 			=> $priceRanges,
								'listingTypes' 			=> $listingTypes,
								'manufacturers' 		=> $manufacturers,
								'newBikes' 				=> $newBikes,
								'featured'				=> $featured,
								]);
	}

}