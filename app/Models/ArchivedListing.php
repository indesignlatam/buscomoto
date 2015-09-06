<?php namespace App\Models;

use App\Models\IndesignModel;

class ArchivedListing extends IndesignModel {

	/**
     * The attributes that would be returned as dates
     *
     * @var string
     */
    protected $dates = ['created_at', 'update_at', 'expires_at'];

	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'archived_listings';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules = ['user_id'     					=> 'required|numeric|exists:users,id',
						'engine_size'     				=> 'required|numeric|exists:engine_sizes,id',
						'listing_type'     				=> 'required|numeric|exists:listing_types,id',
						'manufacturer_id'  				=> 'required|numeric|exists:manufacturers,id',
						'model_id'  					=> 'required|numeric|exists:models,id',
						'fuel_type'  					=> 'required|numeric|exists:fuel_types,id',
						'transmission_type'				=> 'required|numeric|exists:transmission_types,id',
				        'city_id'     					=> 'required|numeric|exists:cities,id',
						'code'     						=> 'alpha_dash|max:20|unique:listings',
				        'slug'     						=> 'alpha_dash|max:255|unique:listings,slug',
				        'title'  						=> 'string|max:255',
				        'district'  					=> 'string|max:100',
				        'description'  					=> 'string|max:2000',
				        'price'  						=> 'required|numeric|min:0',
				        'year'				  			=> 'required|numeric|max:2040',
				        'odometer'				  		=> 'required|numeric|min:0',
				        'color'				  			=> 'string|max:100',
				        'license_number'				=> 'string|max:10',
				        'unique_owner'					=> 'boolean',
				        '4x4'							=> 'boolean',
				        'image_path'					=> 'string|max:255',
				        ];


	/**
	 * The rules to verify when editing.
	 *
	 * @var array
	 */
	protected $editRules = ['user_id'     					=> 'required|numeric|exists:users,id',
							'engine_size'     				=> 'required|numeric|exists:engine_sizes,id',
							'listing_type'     				=> 'required|numeric|exists:listing_types,id',
							'manufacturer_id'  				=> 'required|numeric|exists:manufacturers,id',
							'model_id'  					=> 'required|numeric|exists:models,id',
							'fuel_type'  					=> 'required|numeric|exists:fuel_types,id',
							'transmission_type'				=> 'required|numeric|exists:transmission_types,id',
					        'city_id'     					=> 'required|numeric|exists:cities,id',
							'code'     						=> 'alpha_dash|max:20|unique:listings',
					        'slug'     						=> 'alpha_dash|max:255|unique:listings,slug',
					        'title'  						=> 'string|max:255',
					        'district'  					=> 'string|max:100',
					        'description'  					=> 'string|max:2000',
					        'price'  						=> 'required|numeric|min:0',
					        'year'				  			=> 'required|numeric|max:2040',
					        'odometer'				  		=> 'required|numeric|min:0',
					        'color'				  			=> 'string|max:100',
					        'license_number'				=> 'string|max:10',
					        'unique_owner'					=> 'boolean',
					        '4x4'							=> 'boolean',
					        'image_path'					=> 'string|max:255',
					        ];


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'user_id', 
							'engine_size', 
							'listing_type', 
							'manufacturer_id', 
							'model_id', 
							'fuel_type', 
							'transmission_type', 
							'city_id', 
							'code', 
							'slug', 
							'title', 
							'district', 
							'description', 
							'price', 
							'year', 
							'odometer', 
							'color', 
							'license_number', 
							'unique_owner', 
							'4x4', 
							'image_path',
							];


	public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function engineSize(){
        return $this->belongsTo('App\Models\EngineSize', 'engine_size');
    }

    public function fuelType(){
        return $this->belongsTo('App\Models\FuelType', 'fuel_type');
    }

    public function transmissionType(){
        return $this->belongsTo('App\Models\TransmissionType', 'transmission_type');
    }

    public function manufacturer(){
        return $this->belongsTo('App\Models\Manufacturer', 'manufacturer_id');
    }

    public function model(){
        return $this->belongsTo('App\Models\Model', 'model_id');
    }

    public function city(){
        return $this->belongsTo('App\Models\City', 'city_id');
    }

    public function listingType(){
        return $this->belongsTo('App\Models\ListingType', 'listing_type');
    }

    public function featuredType(){
        return $this->belongsTo('App\Models\FeaturedType', 'featured_type');
    }
}