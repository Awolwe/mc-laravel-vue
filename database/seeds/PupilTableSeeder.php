<?php

use Illuminate\Database\Seeder;

class PupilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Pupils', 20)->create();
}
}
