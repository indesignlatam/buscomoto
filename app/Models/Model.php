<?php namespace App\Models;

use App\Models\IndesignModel;

class Model extends IndesignModel {

	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'models';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules = ['name'  						=> 'required|string|max:255',
				        'manufacturer_id'  				=> 'required|numeric|exists:manufacturers,id',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['name'  						=> 'required|string|max:255',
					        'manufacturer_id'  				=> 'required|numeric|exists:manufacturers,id',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'name', 
							'manufacturer_id',
							];

    /**
     * Relationship with listing which the message belongs to
     *
     * @return \App\Models\Listing
     */
	public function manufacturer(){
        return $this->belongsTo('App\Models\Manufacturer', 'manufacturer_id');
    }

    public function listings(){
        return $this->hasMany('App\Models\Listing', 'manufacturer_id');
    }
}