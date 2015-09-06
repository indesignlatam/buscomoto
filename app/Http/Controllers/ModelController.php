<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Model;
use App\Models\Manufacturer;

class ModelController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(){
        //
        $models         = Model::with('manufacturer')->orderBy('manufacturer_id', 'desc')->orderBy('id', 'desc')->paginate(30);
        $manufacturers  = Manufacturer::all();

        return view('admin.models.index', ['manufacturers'   => $manufacturers,
                                                  'models'          => $models,
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
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request){
        //
        $model = new Model;

        if (!$model->validate($request->all())){
            return redirect('admin/models')->withErrors($model->errors());
        }
        $input = $request->all();

        $model->create($input);

        return redirect('admin/models')->withSuccess([trans('responses.model_created')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){
        //
        $models = Model::selectRaw('id, name AS text')->where('manufacturer_id', $id)->get();

        return $models;
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
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id){
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id){
        //
        $model = Model::find($id);

        if(!$model){
            return trans('responses.not_found');
        }

        $model->delete();
        return trans('responses.model_created');
    }
}
