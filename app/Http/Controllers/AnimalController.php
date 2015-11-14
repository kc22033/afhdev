<?php namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Breed;
use Session;
use Input;
use Config;
use Log;
use Cookie;
use View;
use Carbon\Carbon;
use Validator;
use Redirect;

class AnimalController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $selected_status_id = Session::get('afh.selected_status_id', Config::get('rescue.available_id'));
        $status = Config::get('rescue.status');
        $current_status = $status[$selected_status_id];
        if (Session::has('afh_page_size')) {
            $page_size = Session::get('afh_page_size');
            if (Cookie::has('afh_page_size')) {
                Cookie::forget('afh_page_size');
            } else {
                Cookie::set('afh_page_size', $page_size);
                Session::forget('afh_page_size');
            }
        } else {
            $page_size = Cookie::get('afh_page_size', Config::get('rescue.page_size'));
        }
        $animals = Animal::orderBy('name', 'asc')
                ->where('status_id', '=', $selected_status_id)
                ->paginate($page_size);
        if ($selected_status_id == Config::get('rescue.pending_id')) {
           return View::make('pages.animal.pendingIndex', compact('animals', 'status', 'selected_status_id', 'current_status'));
        } else {
            return View::make('pages.animal.index', compact('animals', 'status', 'selected_status_id', 'current_status'));
        }
    }

    public function update_status() {
        $ids_to_update = Input::get('ids_to_update');
        $selected_status_id = Input::get('status_id');
        foreach ($ids_to_update as $id) {
            $animal = Animal::find($id);
            $animal->status_id = $selected_status_id;
            $animal->status_date = new Carbon('today');
            $animal->save();
        }
        return Redirect::back();
    }

    public function search() {
        $search_string = Input::get('search_string', '*');
        Log::info('Search: ' . $search_string);
        $selected_status_id = Cookie::get('afh.selected_status_id', Config::get('rescue.available_id'));
        $status = Config::get('rescue.status');
        $current_status = $status[$selected_status_id];
        $animals = Animal::orderBy('name', 'asc')
                ->where('status_id', '=', $selected_status_id)
                ->where('name', 'LIKE', '%' . $search_string . '%')
                ->orWhere('foster', 'LIKE', '%' . $search_string . '%')
                ->paginate(Cookie::get('afh_page_size', Config::get('rescue.page_size')));
        Log::info('Query Result');
        return View::make('pages.animal.index', compact('animals', 'status', 'selected_status_id', 'current_status'));
    }

    public function set_page_size() {
        $page_size = Input::get('page_size');
        if (is_numeric($page_size)) {
            Session::put('afh_page_size', $page_size);
        }
        return Redirect::route('animal.index');
    }

    public function setDefaultStatus($status) {
        $selected_status_id = array_search($status, Config::get('rescue.status'));
        Log::info('setDefaultStatus: ' . $status);
        Log::info('setDefaultStatus: ' . $selected_status_id);
        if (FALSE == $selected_status_id) {
            $selected_status_id = Config::get('rescue.available_id');
        }
        Session::set('afh.selected_status_id', $selected_status_id);
        return Redirect::route('animal.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        Log::info('create: ');
        $species = Config::get('rescue.species');
        $gender = Config::get('rescue.gender');
        $status = Config::get('rescue.status');
        $breeds = array('0' => 'Please Select');
        $breeds += Breed::where('species', '=', 'Dog')->orderBy('name')->lists('name', 'id');
        $upload_path = Config::get('rescue.upload_path');
        return View::make('pages.animal.createOrEdit', compact('species', 'breeds', 'gender', 'status', 'upload_path'));
    }

    /**
     * @param $url
     * @param $json
     * @return array
     */
    private function rgPostJson($url, $json) {
        Log::info('rgPostJson: ' . $url . ' JSON: ' . $json);
        // create a new cURL resource
        $ch = curl_init();
        // set options, url, etc.
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // grab URL and pass it to the browser
        Log::info('rgPostJson: curl_exec');
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            // TODO: Handle errors here
            Log::error('rgPostJson: ' . (curl_errno($ch)));
            return array(
                "result" => "",
                "status" => "error",
                "error" => curl_error($ch)
            );
        } else {
            // close cURL resource, and free up system resources
            curl_close($ch);
        }
        return array(
            "status" => "ok",
            "error" => "",
            "result" => $result,
        );
    }

    /**
     * @return bool|string
     */
    private function rgGetJsonError() {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return false;
                break;
            case JSON_ERROR_DEPTH:
                return "Maximum stack depth exceeded";
                break;
            case JSON_ERROR_STATE_MISMATCH:
                return "Underflow or the modes mismatch";
                break;
            case JSON_ERROR_CTRL_CHAR:
                return "Unexpected control character found";
                break;
            case JSON_ERROR_SYNTAX:
                return "Syntax error, malformed JSON";
                break;
            case JSON_ERROR_UTF8:
                return "Malformed UTF-8 characters, possibly incorrectly encoded";
                break;
            default:
                return "Unknown error";
                break;
        }
    }

    /**
     * @param $data
     * @return array|bool|mixed
     */
    function rgPostToApi($data) {
        Log::info('rgPostToApi: data: ', $data);
        $data = json_encode($data);
        $resultJson = $this->rgPostJson(Config::get('rescue_groups_org.url'), $data);
        Log::info('rgPostToApi: Result ', $resultJson);
        if ($resultJson["status"] == "ok") {
            return $resultJson;
        } else {
            Log::error("Error in postToApi:", $result["error"] . $jsonError);
            return array(
                "status" => "error",
                "text" => $result["error"] . $jsonError,
                "errors" => array()
            );
        }
        return false;
    }

    private function rgDoLogin() {
        Log::info('rgDoLogin: Entry');
        if (Session::has('rg.token')) {
            Log::info('rgDoLogin: Entry');
            $return = true;
        } else {
            $login = array(
                "username" => Config::get('rescue_groups_org.user_id'),
                "password" => Config::get('rescue_groups_org.password'),
                "accountNumber" => Config::get('rescue_groups_org.account_num'),
                "action" => "login"
            );

            $result = $this->rgPostToApi($login);

            if ($result['status'] == 'ok') {
                Log::info('rgDoLogin: Status: OK');
                Session::push('rg.token', $result['data']['token']);
                Session::push('rg.tokenHash', $result['data']['tokenHash']);
                $return = true;
            } else {
                Log::info('rgDoLogin: Status: Failed: '.$result['status']);
                $return = false;
            }
        }
        return $return;
    }

    private function rgAddNewAnimal(Animal $animal) {
        return 0;
        if ($this->rgDoLogin()) {
            Log::info('rgAddNewAnimal: Adding new Animal ID: ' . $animal->id);
            $animal_id = 0;
            $data = array(
                "token" => Session::get('rg.token'),
                "tokenHash" => Session::get('rg.tokenHash'),
                "objectType" => "animals",
                "objectAction" => 'add',
                "values" => array(
                    array(
                        "animalRescueID" => $animal->id,
                        "animalName" => $animal->name,
                        "animalSpeciesID" => $animal->species,
                        "animalPrimaryBreedID" => $animal->pri_breed_id,
                        "animalSecondaryBreedID" => $animal->sec_breed_id),
                    "animalMixedBreed" => $animal->mixed_breed,
                    "animalSex" => $animal->gender,
                    "animalStatusID" => $animal->status_id,
                    "animalDescription" => $animal->description,
                ),
            );
            $result = $this->rgPostToApi($data);

            if (!empty($result['messages']['recordMessages'])) {
                $animal_id = $result['messages']['recordMessages'][0]['ID'];
            }
            return $animal_id;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $input = Input::except('link_to_index', 'litter_size', 'auto');
        Log::info('store: Entered');
        $input['date_of_birth'] = new Carbon($input['date_of_birth']);
        $input['intake_date'] = new Carbon($input['intake_date']);
        $input['status_date'] = new Carbon($input['status_date']);
        $input['mixed_breed'] = Input::has('mixed_breed') ? true : false;
        if ($input['sec_breed_id'] != Config::get('rescue.select_breed_id')) {
            $input['mixed_breed'] = true;
        }
        $input['altered'] = Input::has('altered') ? true : false;
        Log::info('store: Before Validation');
        $validation = Validator::make($input, Animal::$rules);

        if ($validation->passes()) {
            Log::info('store: Passed Validation');
            $newAnimal = Animal::create($input);
            $this->rgAddNewAnimal($newAnimal);

            if (Input::has('litter_size')) {
		        Log::info('store: Call process_litter: '.Input::get('litter_size'));

                return Redirect::route('process_litter', array(
						'litter_size' => Input::get('litter_size'),
						'pri_breed_id' => Input::get('pri_breed_id'),
						'sec_breed_id' => Input::get('sec_breed_id'),
						'mixed_breed' => Input::has('mixed_breed') ? TRUE : FALSE,
                    )
                );
            }
			return Redirect::route('animal.index');
        }
		Log::info('store: Redisplay with Errors');
        return Redirect::route('animal.create')
                        ->withInput()
                        ->withErrors($validation->getMessageBag())
                        ->with('message', 'There were validation errors.');
    }

    public function processLitter($litter_size, $pri_breed_id, $sec_breed_id, $mixed_breed = False) {
        Log::info('Entering processLitter: ' . $litter_size);
        if ($litter_size > 0) {
            $gender = Config::get('rescue.gender');
            $breeds = Breed::where('species', '=', 'Dog')->orderBy('name')->lists('name', 'id');
            $default_status = Config::get('rescue.available_id');
            $status = Config::get('rescue.status');
            Log::info('processLitter: returning View::pages.animal.createLitter KLC: ' . $litter_size);
            return View::make(
                'pages.animal.createLitter', 
				compact(
					'litter_size', 
					'pri_breed_id', 
					'sec_breed_id', 
					'mixed_breed', 
					'breeds', 
					'gender', 
					'status', 
					'default_status'
				)
            );
        }
        Log::info('Exiting processLitter: ' . $litter_size);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function storeLitter() {
		Log::info('storeLitter: Entered');
        $puppy = array();
        $litter_name = Input::get('litter-name');
        $puppy['date_of_birth'] = new Carbon(Input::get('date_of_birth'));
        $puppy['intake_date'] = new Carbon(Input::get('intake_date'));
        $puppy['status_date'] = new Carbon(Input::get('status_date'));
        $puppy['pri_breed_id'] = Input::get('pri_breed_id');
        $puppy['sec_breed_id'] = Input::get('sec_breed_id');
        $puppy['foster'] = Input::get('foster');
        $puppy['picture'] = Input::get('picture');
        $puppy['status_id'] = Input::get('status_id');
        $puppy['description'] = Input::get('description');
        $puppy['mixed_breed'] = Input::has('mixed_breed') ? true : false;
        if ($puppy['sec_breed_id'] != Config::get('rescue.select_breed_id')) {
            $puppy['mixed_breed'] = true;
        }
        $puppy['altered'] = Input::has('altered') ? true : false;

        $i = 1;
        $puppy_name = 'name-' . $i;
		Log::info('storeLitter: Create '.$puppy_name);
        while (Input::has($puppy_name)) {
            $puppy['name'] = $litter_name . ': ' . Input::get('name-' . $i);
            $puppy['gender'] = Input::get('gender-' . $i);
            $puppy['altered'] = Input::has('altered-' . $i) ? true : false;
            Log::info('storeLitter:', $puppy);
            $validation = Validator::make($puppy, Animal::$rules);

            if ($validation->passes()) {
                $newAnimal = Animal::create($puppy);
                $this->rgAddNewAnimal($newAnimal);
            } else {
                return Redirect::route('animal.createLitter')
                                ->withInput()
                                ->withErrors($validation)
                                ->with('message', 'There were validation errors.');
            }
            $i++;
            $puppy_name = 'name-' . $i;
        }
        return Redirect::route('animal.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
        $animal = Animal::find($id);
        if (is_null($animal)) {
            return Redirect::route('animal.index');
        }
        return Redirect::route('animal.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
        $animal = Animal::find($id);
        if (is_null($animal)) {
            return Redirect::route('animal.index');
        }
        $species = Config::get('rescue.species');
        $gender = Config::get('rescue.gender');
        $status = Config::get('rescue.status');
        $upload_path = Config::get('rescue.upload_path');
        $breeds = array('0' => 'Please Select');
        $breeds += Breed::where('species', '=', 'Dog')->orderBy('name')->lists('name', 'id');
        return View::make('pages.animal.createOrEdit', compact('animal', 'species', 'breeds', 'gender', 'status', 'upload_path'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
        $input = Input::except('link_to_index', 'litter_size', 'auto');
        $input['date_of_birth'] = new Carbon($input['date_of_birth']);
        $input['intake_date'] = new Carbon($input['intake_date']);
        $input['status_date'] = new Carbon($input['status_date']);
        $input['mixed_breed'] = Input::has('mixed_breed') ? true : false;
        $input['altered'] = Input::has('altered') ? true : false;
        $redirect_url = Input::get('link_to_index');
        if ($redirect_url == '') {
            $redirect_url = '/animal';
        }
        $rules = Animal::$rules;
        $rules['name'] = $rules['name'] . ',id,' . $id;
        $validation = Validator::make($input, $rules);
        Log::info('update:input', $input);
        if ($validation->passes()) {
            $animal = Animal::find($id);
            if ($animal->status_id != $input['status_id']) {
                $input['status_date'] = new Carbon('today');
            }
            if ($input['sec_breed_id'] != Config::get('rescue.select_breed_id')) {
                $input['mixed_breed'] = true;
            } 
            $animal->update($input);

            if (Input::has('litter_size')) {
                Log::info('update: litter_size: ' . Input::get('litter_size'));
                return Redirect::route('process_litter', array(
						'litter_size' => Input::get('litter_size'),
						'pri_breed_id' => Input::get('pri_breed_id'),
						'sec_breed_id' => Input::get('sec_breed_id'),
						'mixed_breed' => Input::has('mixed_breed') ? TRUE : FALSE,
                    )
                );
            }

            Log::info('update: return Redirect::to($redirect_url)');
            return Redirect::to($redirect_url);
        }
        return Redirect::route('animal.edit', $id)
                        ->withInput()
                        ->withErrors($validation)
                        ->with('message', 'There were validation errors.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $animal = Animal::find($id);
		
		$animal->delete();

        return Redirect::route('animal.index');
    }

    public function import() {
        Animal::truncate();
        $animals = Import::all();
        $breeds = Breed::where('species', '=', 'Dog')->orderBy('name')->lists('name', 'id');
        $stati = Config::get('rescue.status');
        $no_breed = Config::get('rescue.select_breed_id');
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
        return Redirect::route('animal.index');
    }

}
