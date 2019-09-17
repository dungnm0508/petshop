<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
         DB::table('users')->insert([
            'name' => 'Nguyễn Thị Ngọc Ánh',
            'email' => 'dungnm@zotabox.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
