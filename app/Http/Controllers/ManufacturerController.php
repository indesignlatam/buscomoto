<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use Session;

use App\Models\Manufacturer;
use App\Models\Country;

class ManufacturerController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		//
		$manufacturers 	= Manufacturer::orderBy('id', 'desc')->paginate(30);
		$countries 		= Country::all();

		return view('admin.manufacturers.index', ['manufacturers' 	=> $manufacturers,
												  'countries' 		=> $countries,
												  ]);
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
	public function store(Request $request){
		//
		$device = new Manufacturer;

		if (!$device->validate($request->all())){
	        return redirect('admin/manufacturers')->withErrors($device->errors());
	    }
	    $input = $request->all();
	    $input['slug'] = str_slug($request->name);

		$device->create($input);

		return redirect('admin/manufacturers')->withSuccess([trans('responses.manufacturer_created')]);
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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id){
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id){
		// for use only with js
		$device = Manufacturer::find($id);

		if(!$device){
			return trans('responses.not_found');
		}

		$device->delete();
		return trans('responses.manufacturer_created');
	}

}
