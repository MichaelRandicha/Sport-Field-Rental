<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'PO',
            'description' => 'Penggemar Olahraga',
        ]);
        DB::table('roles')->insert([
            'name' => 'PL',
            'description' => 'Pemilik Lapangan',
        ]);
        DB::table('roles')->insert([
            'name' => 'CS',
            'description' => 'Customer Service',
        ]);
    }
}
