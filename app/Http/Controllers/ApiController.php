<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Models\Animal;

class ApiController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | API Controller
      |--------------------------------------------------------------------------
      |
      |	Route::get('/', 'ApiController@showWelcome');
      |
     */

    private function getCount(Carbon $start_date, Carbon $end_date) {
        $count = Animal::where('date_of_birth', '>', $start_date->toDateString())
                ->where('date_of_birth', '<=', $end_date->toDateString())
                ->where('status_id', '=', Config::get('rescue.available_id'))
                ->count();
        return $count;
    }

    public function getAges() {
        $end_date = new Carbon('today');
        $baby = $this->getCount(new Carbon('16 weeks ago'), $end_date);
        $end_date = new Carbon('16 weeks ago');
        $young = $this->getCount(new Carbon('18 months ago'), $end_date);
        $end_date = new Carbon('18 months ago');
        $adult = $this->getCount(new Carbon('2 years ago'), $end_date);
        $end_date = new Carbon('2 years ago');
        $senior = $this->getCount(new Carbon('7 years ago'), $end_date);
        $end_date = new Carbon('7 years ago');
        $unknown = $this->getCount(new Carbon('100 years ago'), $end_date);

        $data['cols'] = [
            [
                'id' => '',
                'label' => 'Age',
                'type' => 'string'
            ],
            [
                'id' => '',
                'label' => 'Count',
                'type' => 'number'
            ]
        ];
        $data['rows'] = [
                    ['c' => [
                            ['v' => 'Baby (< 16 Weeks)'],
                            ['v' => $baby]
                        ]
                    ],
                    ['c' => [
                            ['v' => 'Young (< 18 Months)'],
                            ['v' => $young]
                        ]
                    ],
                    ['c' => [
                            ['v' => 'Adult (< 7 Years)'],
                            ['v' => $adult]
                        ]
                    ],
                    ['c' => [
                            ['v' => 'Senior (> 7 Years)'],
                            ['v' => $senior]
                        ]
                    ],
                    ['c' => [
                            ['v' => 'Unknown'],
                            ['v' => $unknown]
                        ]
        ]];
        return $data;
    }

    public function getGender() {
        $female = Animal::where('gender', '=', 'Female')
                ->where('status_id', '=', Config::get('rescue.available_id'))
                ->count();
        $male = Animal::where('gender', '=', 'Male')
                ->where('status_id', '=', Config::get('rescue.available_id'))
                ->count();
        $unknown = Animal::where('gender', '=', 'Unknown')
                ->where('status_id', '=', Config::get('rescue.available_id'))
                ->count();

        $data['cols'] = [
            [
                'id' => '',
                'label' => 'Gender',
                'type' => 'string'
            ],
            [
                'id' => '',
                'label' => 'Count',
                'type' => 'number'
            ]
        ];
        $data['rows'] = [
                    ['c' => [
                            ['v' => 'Female'],
                            ['v' => $female]
                        ]
                    ],
                    ['c' => [
                            ['v' => 'Male'],
                            ['v' => $male]
                        ]
                    ],
                    ['c' => [
                            ['v' => 'Unknown'],
                            ['v' => $unknown]
                        ]
        ]];
        return $data;
    }

    public function getAltered() {
        $altered = Animal::where('altered', '=', true)
                ->where('status_id', '=', Config::get('rescue.available_id'))
                ->count();
        $intact = Animal::where('altered', '=', false)
                ->where('status_id', '=', Config::get('rescue.available_id'))
                ->count();

        $data['cols'] = [
            [
                'id' => '',
                'label' => 'Altered',
                'type' => 'string'
            ],
            [
                'id' => '',
                'label' => 'Count',
                'type' => 'number'
            ]
        ];
        $data['rows'] = [
                    ['c' => [
                            ['v' => 'Altered'],
                            ['v' => $altered]
                        ]
                    ],
                    ['c' => [
                            ['v' => 'Intact'],
                            ['v' => $intact]
                        ]
        ]];
        return $data;
    }

    public function getFosters() {
        $search = Input::get('term');
     //   Log::info('getFosters: '.$search);
        $data = Animal::orderBy('foster', 'ASC')
                ->distinct()
                ->get(array('foster'))
                ->lists('foster');
        return $data;
    }

}
