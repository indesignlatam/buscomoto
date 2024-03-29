<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth, Session;
use App\Models\Feature, App\Models\FeatureCategory;

class FeatureController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(){
		//
		$objects = Feature::paginate(30);

		$categories = FeatureCategory::all();

		return view('admin.features.index', ['features' => $objects, 'categories' => $categories]);
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
		$device = new Feature;

		if (!$device->validate($request->all())){
	        return redirect('admin/features')->withErrors($device->errors());
	    }

	    $input = $request->all();
	    $input['slug'] = str_slug($request->name);

		$device->create($input);

		return redirect('admin/features')->withSuccess([trans('responses.feature_created')]);
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
		$device = Feature::find($id);

		if(!$device){
			Session::flash('errors', ['No object found']);
			return;
		}

		$device->delete();
		Session::flash('success', ['Feature Deleted']);
		return;
	}

}
