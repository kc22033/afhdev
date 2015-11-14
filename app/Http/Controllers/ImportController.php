<?php namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Import;
use App\Models\Breed;

class ImportController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * @return Response
	 */
	public function index() {
        Animal::truncate();
        $animals = Import::all();
        $breeds = Breed::where('species', '=', 'Dog')->orderBy('name')->lists('name', 'id');
        $stati = \Config::get('rescue.status');
        $no_breed = \Config::get('rescue.select_breed_id');
        foreach ($animals as $animal) {
            $pri_breed_id = array_search($animal->pri_breed, $breeds);
            if (FALSE <> $pri_breed_id) {
                $sec_breed_id = array_search($animal->sec_breed, $breeds);
                if (FALSE == $sec_breed_id) {
                    $sec_breed_id = $no_breed;
                }
                $status = array_search($animal->status, $stati);
                if (FALSE == $status) {
                    $status = 0;
                }
                Animal::create(array(
                    'id' => $animal,
                    'name' => $animal->name,
                    'species' => $animal->species,
                    'pri_breed_id' => $pri_breed_id,
                    'sec_breed_id' => $sec_breed_id,
                    'mixed_breed' => $animal->mix == '1' ? TRUE : FALSE,
                    'date_of_birth' => $animal->date_of_birth,
                    'gender' => $animal->gender,
                    'altered' => $animal->altered == 1 ? TRUE : FALSE,
                    'intake_date' => $animal->intake_date,
                    'status_id' => $status,
                    'status_date' => $animal->status_date,
                    'foster' => $animal->foster,
                    'picture' => $animal->picture,
                    'description' => $animal->description,
                        )
                );
            }
        }
        return '<h2>Import Complete: ' . Animal::Count() . ' Records Imported</h2>';
    }


}
