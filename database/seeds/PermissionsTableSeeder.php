<?php

/*
 * A Forever Home Rescue Foundation 
 * 
 */

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder {

    public function run() {
        DB::table('permissions')->delete();

        $addUser = new Permission();
        $addUser->name = 'add-user';
        $addUser->display_name = 'Add Users'; // optional
        // Allow a user to...
        $addUser->description = 'Add new users'; // optional
        $addUser->save();

        $editUser = new Permission();
        $editUser->name = 'edit-user';
        $editUser->display_name = 'Edit Users'; // optional
        // Allow a user to...
        $editUser->description = 'Edit users already in the system'; // optional
        $editUser->save();

        $addAnimal = new Permission();
        $addAnimal->name = 'add-animal';
        $addAnimal->display_name = 'Add Animals'; // optional
        // Allow a user to...
        $addAnimal->description = 'Add animals to the inventory'; // optional
        $addAnimal->save();

        $editAnimal = new Permission();
        $editAnimal->name = 'edit-animal';
        $editAnimal->display_name = 'Edit Animals'; // optional
        // Allow a user to...
        $editAnimal->description = 'Edit animals already in the inventory'; // optional
        $editAnimal->save();

        $admin = Role::where('name', '=', 'admin')->first();
        $admin->attachPermissions(array($addUser, $editUser, $addAnimal, $editAnimal));
        
        $intakeCoordinator = Role::where('name', '=', 'intake_coordinator')->first();
        $intakeCoordinator->attachPermissions(array($addAnimal, $editAnimal));

        $fosterCoordinator = Role::where('name', '=', 'foster_coordinator')->first();
        $fosterCoordinator->attachPermissions(array($addUser, $editUser));

        $medicalCoordinator = Role::where('name', '=', 'medical_coordinator')->first();
        $medicalCoordinator->attachPermissions(array($addAnimal, $editAnimal));

        $fosterLiaison = Role::where('name', '=', 'foster_liaison')->first();
        $fosterLiaison->attachPermissions(array($addAnimal, $editAnimal));
    }
}
