<?php namespace App\Http\Controllers\API2;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Settings;
use Authorizer;

use App\Models\Manufaturer;
use App\Models\Model;
use App\Models\ListingType;

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
		$models 		= Model::with('manufacturer')->remember(Settings::get('query_cache_time_short'))->get();
		$types 			= ListingType::remember(Settings::get('query_cache_time_short'))->get();

		return response()->json(['data' => ['models'		=> $models,
											'types' 		=> $types,
											]
								]);
	}
}