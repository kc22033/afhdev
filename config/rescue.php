<?php

/*
 * A Forever Home Rescue Foundation 
 * 
 * See: http://laravelsnippets.com/snippets/custom-config-files-values 
 * 
 * Usage: $species_list = Config::get('rescue.species');
 */

return array(
    'gender' => array(
        'Invalid' => 'Select Gender',
        'Female' => 'Female',
        'Male' => 'Male',
        'Unknown' => 'Unknown'
        ),
    'status' => array(
        '0' => 'Select Status',
        '1' => 'Intake Pending',
        '2' => 'Available',
        '3' => 'Adoption Pending',
        '4' => 'Adopted',
        '5' => 'Transferred',
        '6' => 'Deceased'
    ),
    'available_id' => 2,
    'pending_id' => 3,
    'species' => array(
        'Dog' => 'Dog',
        'Cat' => 'Cat',
        'Bird' => 'Bird',
        'Farm Animal' => 'Farm Animal',
        'Horse' => 'Horse',
        'Rabbit' => 'Rabbit',
        'Reptile' => 'Reptile',
        'Small Animal' => 'Small Animal',
        'Other' => 'Other',
    ),
    'page_size' => 15, //12,
    'select_breed_id' => 0,
    'upload_path' => 'images',
    'max_picture_size' => 1000,
    'default_image' => '/afh_logo.svg',
    'treatment_units' => array(
        'Days' => 'Days',
        'Weeks' => 'Weeks',
        'Months' => 'Months',
        'Years' => 'Years',
    ),
);
