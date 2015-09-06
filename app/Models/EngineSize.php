<?php namespace App\Models;

use App\Models\IndesignModel;

class EngineSize extends IndesignModel {

	/**
	 * Dont update my timestamps! I dont have any.
	 *
	 * @var string
	 */
	public $timestamps = false;

	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'engine_sizes';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules = ['name'  						=> 'required|string|max:255',
				        'slug'  						=> 'string|max:255|unique:listing_types,slug',
				        'min'  							=> 'numeric',
				        'max'  							=> 'numeric',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['name'  						=> 'required|string|max:255',
					        'slug'  						=> 'string|max:255|unique:listing_types,slug',
					        'min'  							=> 'numeric',
					        'max'  							=> 'numeric',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'name', 
							'slug', 
							'min',
							'max',
							];

    /**
     * Relationship with listing which the message belongs to
     *
     * @return \App\Models\Listing
     */
	public function listings(){
        return $this->hasMany('App\Models\Listing', 'engine_size');
    }
}