<?php namespace App\Http\Controllers;

use App\Models\Breed;
use App\Http\Controllers\Controller;
use View;
use Input;
use Validator;
use Redirect;

class BreedController extends Controller {

    /**
     * GET /breed
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $breeds = Breed::where('species', '=', 'Dog')->paginate(15);
        return View::make('pages.breed.index', compact('breeds'));

    }

    public function getBreedsDataTable() {

        $query = Breed::select('name', 'species', 'description_url', 'id')->get();

        return Datatable::collection($query)
                        ->addColumn('name', function($model) {
                            return $model->name;
                        })
                        ->addColumn('species', function($model) {
                            return $model->species;
                        })
                        ->addColumn('description_url', function($model) {
                            return '<a href="' . $model->description_url . '">See Breed Description</a>';
                        })
                        ->addColumn('id', function($model) {
                            return '<a href="/breed/' . $model->id . '">view</a>';
                        })
                        ->searchColumns('name', 'species')
                        ->orderColumns('name', 'species')
                        ->make();
    }

    /**
     * GET /breed/create
     * 
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
        return View::make('pages.breed.create');
    }

    /**
     * POST /breed
     * 
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
        $input = Input::all();
        $validation = Validator::make($input, Breed::$rules);

        if ($validation->passes()) {
            Breed::create($input);

            return Redirect::route('breed.index');
        }

        return Redirect::route('breed.create')
                        ->withInput()
                        ->withErrors($validation)
                        ->with('message', 'There were validation errors.');
    }

    /**
     * GET /breed/{id}
     * 
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
        $breed = Breed::find($id);
        if (is_null($breed)) {
            return Redirect::route('breed.index');
        }
        $species_list = \Config::get('rescue.species');
        return View::make('pages.breed.show', compact('breed'));
    }

    /**
     * GET /breed/{id}/edit
     * 
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
        $breed = Breed::find($id);
        if (is_null($breed)) {
            return Redirect::route('breed.index');
        }
        return View::make('pages.breed.edit', compact('breed'));
    }

    /**
     * PUT /breed/{id}
     * 
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
        $input = Input::all();
        $rules = Breed::$rules;
        $rules['name'] = $rules['name'] . ',id,' . $id;
        $validation = Validator::make($input, $rules);
        if ($validation->passes()) {
            $breed = Breed::find($id);
            $breed->update($input);
            return Redirect::route('breed.show', $id);
        }
        return Redirect::route('breed.edit', $id)
                        ->withInput()
                        ->withErrors($validation)
                        ->with('message', 'There were validation errors.');
    }

    /**
     * DELETE /breed/{id}
     * 
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

    /*
     * ***********************************************************************
     * ***********************************************************************
     * RescueGruops.org Stuff to retreive breeds list 
     * ***********************************************************************
     * ***********************************************************************
     */

    private $rg_token = '';
    private $rg_tokenHash = '';
    private $breed_list = array();

    public function load_breeds() {
        $data = array(
            "apikey" => \Config::get('rescue_groups_org.apikey'),
            "objectType" => "animalBreeds",
            "objectAction" => "publicList",
            "search" => array(
                "resultLimit" => 300,
                "fields" => array(
                    "breedID",
                    "breedName"
                )
            )
        );
        $this->breed_list = $this->postToApi($data);
        $count = 0;
        // var_dump($this->breed_list);
        foreach($this->breed_list['data'] as $key=>$value) {
            var_dump($key, $value);
            try {
                $breedName = $value['name'];
                $speciesName = $value['species'];
                $breedArray = [
                    'id' => $key,
                    'name' => $breedName,
                    'species' => $speciesName,
                    'description_url' => 'http://en.wikipedia.org/wiki/' . str_replace(' ', '_', $breedName)
                ];
                Breed::create($breedArray);
            $count++;
            } catch (Exception $e) {
                print "<p>" . $e->getMessage() . "</p>";
            }
        }
        print "Created $count Breeds</p></p>";
        return View::make('pages.breed.load', compact('breeds'));
    }

    private function find_breed($the_breed) {
        foreach ($this->breed_list['data'] as $key => $value) {
            if ($value['breedName'] == $the_breed) {
                return (string) $key;
            }
        }
        return "";
    }

    /**
     * @param $url
     * @param $json
     * @return array
     */
    private function postJson($url, $json) {
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
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            // TODO: Handle errors here
            echo "curl_err";
            print "<pre>";
            print_r(curl_errno($ch));
            print "</pre>";
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
     * @param $data
     * @return array|bool|mixed
     */
    private function postToApi($data) {
//        print "<pre>";
//        print_r($data);
//        print "</pre>";
        $resultJson = $this->postJson(\Config::get('rescue_groups_org.url'), json_encode($data));
        if ($resultJson["status"] == "ok") {
            $result = json_decode($resultJson["result"], true);
//            print "<pre>After json_decode";
//            print_r ($result);
//            print "</pre>";
            $jsonError = $this->getJsonError();
            if (!$jsonError && $resultJson["status"] == "ok") {
                // print_r($result);
                return $result;
            } else {
                print_r($result);
                return array(
                    "status" => "error",
                    "text" => $result["error"] . $jsonError,
                    "errors" => array()
                );
            }
        } else {
            print_r($resultJson);
        }
        print "<pre>Before Return false";
        print_r($resultJson);
        print "</pre>";

        return false;
    }

    /**
     * @return bool|string
     */
    private function getJsonError() {
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

}
