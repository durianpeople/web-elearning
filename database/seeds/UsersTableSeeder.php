<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id'        =>  1,
            'NRP'       =>  '05111740000028',
            'name'      =>  'Setya Wibawa',
            'email'     =>  'durianpeople@gmail.com',
            'KTM'       =>  'null',
            'password'  =>  bcrypt('admin'),
        ]);
    }
}
