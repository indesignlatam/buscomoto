<?php namespace App\Http\Controllers\APIV2;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Authorizer;

use App\Models\Manufaturer;
use App\Models\Model;
use App\Models\ListingType;
use App\Models\City;
use App\Models\Feature;

class SearchController extends Controller {

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(){
        //$this->middleware('oauth', ['only' => ['liked', 'like']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request){
		$models 		= Model::with('manufacturer')->remember(10)->get();
		$types 			= ListingType::remember(10)->get();
		$cities 		= City::remember(10)->get();
		$features 		= Feature::remember(10)->get();

		return response()->json(['data' => ['models'		=> $models,
											'types' 		=> $types,
											'cities' 		=> $cities,
											'features' 		=> $features,
											]
								]);
	}
}