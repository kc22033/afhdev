<?php namespace App\Models;


/*
 * A Forever Home Rescue Foundation 
 * 
 */

/**
 * Description of breed
 *
 * @author Ken Cline <ken@aforeverhome.org>
 */

use \Illuminate\Database\Eloquent\Model;

class Breed extends Model {

    // protected $guarded = array('id', 'species', 'name');
    protected $guarded = array('');
    
    public static $rules = array(
        'name' => 'sometimes|required|min:2|unique:breeds',
        'species' => 'min:2',
        'description_url' => 'URL:active_url'
    );

    public function animals() {
        return $this->hasMany('Animal');
    }

}
