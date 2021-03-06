<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Role::truncate();

        Role::create([
            'name' => 'administrator',
            'description' => 'Администратор',
            'instrumental' => 'для администратора'
        ]);
        Role::create([
            'name' => 'user',
            'description' => 'Пользователь',
            'instrumental' => 'для пользователя'
        ]);

    }
}
