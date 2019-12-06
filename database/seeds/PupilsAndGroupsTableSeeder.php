<?php

use Illuminate\Database\Seeder;

class PupilsAndGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\PupilsAndGroups', 160)->create();
    }
}
