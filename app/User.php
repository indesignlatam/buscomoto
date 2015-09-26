<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Bican\Roles\Traits\HasRoleAndPermission;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;

use Validator;
use Settings;
use Carbon;

use App\Models\Appointment;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, HasRoleAndPermissionContract {
    use Authenticatable, CanResetPassword, HasRoleAndPermission;

    protected $errors;

    /**
     * The rules to verify when creating.
     *
     * @var array
     */
    protected $rules = ['name'                          => 'required|string|max:255',
                        'username'                      => 'required|alpha_dash|min:4|unique:users,username,{:id}',
                        'email'                         => 'required|email|unique:users,email,{:id}',
                        'password'                      => 'string|min:6|max:100',
                        'phone_1'                       => 'required|digits_between:7,15',
                        'phone_2'                       => 'digits_between:7,15',
                        'description'                   => 'string|max:1500',
                        'email_notifications'           => 'required|boolean',
                        'privacy_name'                  => 'required|boolean',
                        'privacy_phone'                 => 'required|boolean',
                        ];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name' ,'username', 'email', 'phone_1', 'phone_2', 'description', 'password', 'confirmation_code', 'email_notifications', 'privacy_name', 'privacy_phone'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'confirmation_code'];



    public function validate($data, $rulesAdd = null, $isEdit = false, $id = null){

        $rules = $this->rules;

        if($isEdit){
            $rules = $this->editRules;
        }

        if($rulesAdd){
            array_merge($rules,$rulesAdd);
        }

        $replace = ($id > 0) ? $id : '';
        foreach ($rules as $key => $rule){
            $rules[$key] = str_replace('{:id}', $replace, $rule);
        }
        
        // make a new validator object
        $validator = Validator::make($data, $rules);

        // check for failure
        if ($validator->fails()){
            // set errors and return false
            $this->errors = $validator->errors();
            return false;
        }

        // validation pass
        return true;
    }

    public function errors(){
        return $this->errors;
    }


    // Path to user listings
    public function path(){
        if($this->username){
            return url($this->username);
        }
        return url($this->id);
    }


    // Listings count
    public function listingCount(){
        return $this->hasOne('App\Models\Listing', 'user_id')
                    ->remember(Settings::get('query_cache_time_extra_short'))
                    ->selectRaw('user_id, count(*) as aggregate')
                    ->groupBy('user_id');
    }
    public function getListingCountAttribute(){
        // if relation is not loaded already, let's do it first
        if ( ! array_key_exists('listingCount', $this->relations)) 
        $this->load('listingCount');

        $related = $this->getRelation('listingCount');

        // then return the count directly
        return ($related) ? (int) $related->aggregate : 0;
    }

    // Free listings count
    public function freeListingCount(){
        return $this->hasOne('App\Models\Listing', 'user_id')
                    ->selectRaw('user_id, featured_expires_at, deleted_at, count(*) as aggregate')
                    ->where('featured_expires_at', '<', Carbon::now())
                    ->whereNull('deleted_at')
                    ->orWhereNull('featured_expires_at')
                    ->whereNull('deleted_at')
                    ->groupBy('user_id');
    }
    public function getFreeListingCountAttribute(){
        // if relation is not loaded already, let's do it first
        if ( ! array_key_exists('freeListingCount', $this->relations)) 
        $this->load('freeListingCount');

        $related = $this->getRelation('freeListingCount');

        // then return the count directly
        return ($related) ? (int) $related->aggregate : 0;
    }

    // Messages count
    public function getMessagesCountAttribute(){
        return Appointment::leftJoin('listings',
                                    function($join) {
                                        $join->on('appointments.listing_id', '=', 'listings.id');
                                    })
                                  ->remember(Settings::get('query_cache_time_extra_short'))
                                  ->select('appointments.listing_id', 'listings.user_id', 'listing.featured_type')
                                  ->where('listings.user_id', $this->id)
                                  ->where('featured_type', '>', 0)
                                  ->count();
    }


    // Relationships
    public function listings(){
        return $this->hasMany('App\Models\Listing', 'user_id');
    }

    public function payments(){
        return $this->hasMany('App\Models\Payment', 'user_id');
    }

    public function likes(){
        return $this->hasMany('App\Models\Like', 'user_id');
    }

    public function appointments(){
        return $this->hasManyThrough('App\Models\Appointment', 'App\Models\Listing', 'user_id');
    }
}
