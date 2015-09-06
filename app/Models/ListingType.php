<?php namespace App\Models;

use App\Models\IndesignModel;

class ListingType extends IndesignModel {

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
    protected $table = 'listing_types';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules = ['name'  						=> 'required|string|max:255',
				        'slug'  						=> 'string|max:255|unique:listing_types,slug',
				        'image_path'  					=> 'string|max:255',
				        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['name'  						=> 'required|string|max:255',
					        'slug'  						=> 'string|max:255|unique:listing_types,slug',
					        'image_path'  					=> 'string|max:255',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'name', 
							'image_path', 
							'slug',
							];

	/**
     * Scope a query to only include ventas
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
	public function scopeVentas($query){
        return $query->where('id', 1);
    }

    /**
     * Relationship with listing which the message belongs to
     *
     * @return \App\Models\Listing
     */
	public function listings(){
        return $this->hasMany('App\Models\Listing', 'listing_type');
    }
}