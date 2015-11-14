<?php namespace App\Models;

/*
 * A Forever Home Rescue Foundation 
 * 
 */

/**
 * Description of animal
 *
 * @author Ken Cline <ken@aforeverhome.org>
 */
use Illuminate\Database\Eloquent\Model;
use Config;

class Animal extends Model {
    
    protected $guarded = array('id');
    
    // http://stackoverflow.com/questions/18401771/how-to-customize-date-mutators-in-laravel/18655255#18655255
    
//    protected $dates = array('date_of_birth', 'intake_date', 'status_date');

    public static $rules = array(
        'name' => 'required|min:2',
        'species' => 'sometimes|required|min:2',
        'pri_breed_id' => 'integer|required|min:1',   
        'date_of_birth' => 'required|date',
        'intake_date' => 'sometimes|required',
        'status_id' => 'integer|required|min:1',
        'gender' => 'in:Female,Male,Unknown',
    );
    
    public static $messages = array(
        'name.required' => 'The :attribute field is Required.',
        'pri_breed_id.required' => 'The Primary Breed is required.',
    );

    public function getSecBreedIdAttribute($value)
    {
        return $value == null ? Config::get('rescue.select_breed_id') : $value;
    }

    public function setSecBreedIdAttribute($value)
    {
        $this->attributes['sec_breed_id'] = $value == 0 ? null : $value;
    }

    public function priBreed() {
        return $this->belongsTo('App\Models\Breed', 'pri_breed_id');
    }
    
    public function secBreed() {
        return $this->belongsTo('App\Models\Breed', 'sec_breed_id');
    }
    
//    public function getDates(){
//        $res = parent::getDates();
//        array_push($res,array('date_of_birth', 'intake_date', 'status_date', 'created_at', 'update_at', 'deleted_at'));
//        return $res;
//    }
    
    public function getPicture() {
        if($this->picture != '') {
            return $this->picture;
        } else {
            return Config::get('rescue.default_image');
        }
    }
}
