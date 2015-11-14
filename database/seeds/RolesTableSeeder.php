<?php

/*
 * A Forever Home Rescue Foundation 
 * 
 */

use Illuminate\Database\Seeder;
use App\Models\User;

class RolesTableSeeder extends Seeder {

    public function run() {
        DB::table('roles')->delete();

        $admin = new Role();
        $admin->name = 'admin';
        $admin->display_name = 'Administrator';
        $admin->description = 'Administrator has full access to all system functions.';
        $admin->save();

        $intakeCoordinator = new Role();
        $intakeCoordinator->name = 'intake_coordinator';
        $intakeCoordinator->display_name = 'Intake Coordinator';
        $intakeCoordinator->description = 'Intake Coordinator is responsible for coordinating the intake of animals to the rescue.';
        $intakeCoordinator->save();

        $fosterCoordinator = new Role();
        $fosterCoordinator->name = 'foster_coordinator';
        $fosterCoordinator->display_name = 'Foster Coordinator';
        $fosterCoordinator->description = 'Foster Coordinator is responsible for bringing foster providers up to speed and answering their process questions.';
        $fosterCoordinator->save();

        $medicalCoordinator = new Role();
        $medicalCoordinator->name = 'medical_coordinator';
        $medicalCoordinator->display_name = 'Medical Coordinator';
        $medicalCoordinator->description = 'Medical Coordinator facilitates and monitors the medical care for all animals in the rescue\'s care.';
        $medicalCoordinator->save();

        $fosterLiaison = new Role();
        $fosterLiaison->name = 'foster_liaison';
        $fosterLiaison->display_name = 'Foster Liaison';
        $fosterLiaison->description = 'Foster Liaison manages the movement of animals among the approved foster providers.';
        $fosterLiaison->save();

        $fosterProvider = new Role();
        $fosterProvider->name = 'foster_provider';
        $fosterProvider->display_name = 'Foster Provider';
        $fosterProvider->description = 'Foster Provider delivers daily care to animals in the rescue\'s inventory.';
        $fosterProvider->save();
        
        // Find Ken's account, attach the admin role
        $user = User::where('email', '=', 'admin@example.org')->first();
        $user->attachRole($admin);
    }
}