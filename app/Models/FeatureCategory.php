<?php namespace App\Models;

use App\Models\IndesignModel;

class FeatureCategory extends IndesignModel {

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
    protected $table = 'feature_categories';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules 	= [ 'name'  					=> 'required|string|max:255',
					        'slug'  					=> 'string|max:255',
					        ];

	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['name'  					=> 'required|string|max:255',
					        'slug'  					=> 'string|max:255',
					        ];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'name', 
							'slug',
							];

	
	/**
     * Relationship with features
     *
     * @return \App\Models\Feature
     */
	public function features(){
        return $this->hasMany('App\Models\Feature', 'category_id');
    }
}