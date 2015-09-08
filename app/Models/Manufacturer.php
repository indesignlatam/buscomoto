<?php namespace App\Models;

use App\Models\IndesignModel;

class Manufacturer extends IndesignModel {

	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'manufacturers';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules = ['name'  						=> 'required|string|max:255',
				        'country_id'  					=> 'numeric|exists:countries,id',
				        'image_path'					=> 'string|max:255',
				        'ordering'						=> 'numeric',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['name'  						=> 'required|string|max:255',
					        'country_id'  					=> 'numeric|exists:countries,id',
					        'image_path'					=> 'string|max:255',
					        'ordering'						=> 'numeric',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'name', 
							'country_id', 
							'image_path',
							'ordering',
							];

    /**
     * Relationship with listing which the message belongs to
     *
     * @return \App\Models\Listing
     */
	public function listings(){
        return $this->hasMany('App\Models\Listing', 'manufacturer_id');
    }

    /**
     * Relationship with listing which the message belongs to
     *
     * @return \App\Models\Listing
     */
	public function country(){
        return $this->belongsTo('App\Models\Country', 'country_id');
    }
}