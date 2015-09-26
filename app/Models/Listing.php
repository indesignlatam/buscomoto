<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use DB;
use Carbon;
use App\Models\IndesignModel;

class Listing extends IndesignModel {

	use SoftDeletes;

	/**
     * The attributes that would be returned as dates
     *
     * @var string
     */
	protected $dates = ['created_at', 'update_at', 'deleted_at', 'featured_expires_at', 'expires_at'];

	/**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['url', 'tag', 'image_url'];

	/**
	 * The name of the table.
	 *
	 * @var string
	 */
    protected $table = 'listings';

	/**
	 * The rules to verify when creating.
	 *
	 * @var array
	 */
	protected $rules = ['user_id'     					=> 'required|numeric|exists:users,id',
						'engine_size'     				=> 'required|numeric',
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


    /**
     * Get the administrator flag for the user.
     *
     * @return bool
     */
    public function getUrlAttribute(){
        return url($this->path());
    }

    /**
     * Get the administrator flag for the user.
     *
     * @return bool
     */
    public function getTagAttribute(){
        if($this->featured_type && $this->featured_expires_at > Carbon::now()){
	    	return '<div style="background-color:#1C7BBA; position:absolute; top:15px; left:15px;" class="uk-text-contrast uk-text-center uk-h3"><p class="uk-margin-small-bottom uk-margin-small-top uk-margin-left uk-margin-right"><i class="uk-icon-check" data-uk-tooltip title="'.trans("frontend.listing_featured").'"></i></p></div>';
        }
        return '';
    }

    /**
	 * Resolve the image path to show
	 *
	 * @var string
	 */
	public function getImageUrlAttribute(){
		if($this->image_path || $this->image_path != ''){
			return url($this->image_path);
		}
		return url('/images/defaults/listing.jpg');
	}

	/**
	 * Resolve the image path to show
	 *
	 * @var string
	 */
	public function image_path(){
		if($this->image_path || $this->image_path != ''){
			return $this->image_path;
		}
		return '/images/defaults/listing.jpg';
	}

	/**
	 * Resolve the path to listing in FE
	 *
	 * @var string
	 */
	public function path(){
		return strtolower('/buscar/'.$this->slug);
	}

	/**
	 * Resolve the path to listing in BE
	 *
	 * @var string
	 */
	public function pathEdit(){
		return strtolower('/admin/listings/'.$this->id.'/edit');
	}

	/**
	 * Check if listing has an unconfirmed payment
	 *
	 * @var bool
	 */
	public function hasUnconfirmedPayments(){
		if(count($this->payments) > 0){
			foreach ($this->payments as $payment) {
				if(!$payment->confirmed && !$payment->canceled && $payment->state_pol == null){
					return true;
				}
			}
		}
		return false;
	}

	/**
     * Scope a query to only include non expired listings.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query){
        return $query->where('expires_at', '>', DB::raw('now()'));
    }

    /**
     * Scope a query to only include featured listings.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query){
        return $query->whereNotNull('featured_type')->where('featured_expires_at', '>', DB::raw('now()'));
    }



	// All messages count
    public function messageCount(){
	  	return $this->hasOne('App\Models\Appointment')
			      	->selectRaw('listing_id, count(*) as aggregate')
			      	->groupBy('listing_id');
	}
	public function getMessageCountAttribute(){
		// if relation is not loaded already, let's do it first
		if ( ! array_key_exists('messageCount', $this->relations)) 
		$this->load('messageCount');

		$related = $this->getRelation('messageCount');

		// then return the count directly
		return ($related) ? (int) $related->aggregate : 0;
	}

	// Unread messages count
    public function notAnsweredMessageCount(){
	  	return $this->hasOne('App\Models\Appointment')
			      	->selectRaw('listing_id, count(*) as aggregate')
			      	->notAnswered()
			      	->groupBy('listing_id');
	}
	public function getNotAnsweredMessageCountAttribute(){
		// if relation is not loaded already, let's do it first
		if ( ! array_key_exists('notAnsweredMessageCount', $this->relations)) 
		$this->load('notAnsweredMessageCount');

		$related = $this->getRelation('notAnsweredMessageCount');

		// then return the count directly
		return ($related) ? (int) $related->aggregate : 0;
	}


	/**
     * Model events
     */
    protected static function boot() {
        parent::boot();

        static::deleting(function($listing) { // before delete() method call this
        	if($listing->forceDeleting){
		        // do in case of force delete

		        $listing->payments()->update(['listing_id' => null]);

		        // Set main image id to null to prevent mysql errors
				if($listing->main_image_id){
					$listing->main_image_id = null;
					$listing->save();
				}
				
				// Delete images	
				foreach($listing->images as $image){
					$image->delete();
				}

				// Delete features relations
				$ids = [];
				foreach($listing->features as $feature){
					$ids[] = $feature->id;
				}
				$listing->features()->detach($ids);

				$archived = $listing->toArray();
				$archived['listing_type'] = $listing->listing_type;
				array_forget($archived, 'images');
				array_forget($archived, 'features');
				array_forget($archived, 'featured_type');
				array_forget($archived, 'category');
				array_forget($archived, 'city');
				ArchivedListing::create($archived);
		    }
        });
    }



	public function user(){
        return $this->belongsTo('App\User', 'user_id');
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

    public function features(){
        return $this->belongsToMany('App\Models\Feature');
    }

    public function images(){
        return $this->hasMany('App\Models\Image', 'listing_id');
    }

    public function payments(){
        return $this->hasMany('App\Models\Payment', 'listing_id');
    }

    public function messages(){
        return $this->hasMany('App\Models\Appointment', 'listing_id');
    }

    public function likes(){
        return $this->hasMany('App\Models\Like', 'listing_id');
    }
}