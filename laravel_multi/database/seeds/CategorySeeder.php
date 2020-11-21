<?php

use Illuminate\Database\Seeder;
use \App\Category;

class CategorySeeder extends Seeder
{
    
    public function run()
    {
        DB::table('categories')->insert([
            'name' => 'Musica',
        ]);
        DB::table('categories')->insert([
            'name' => 'Programacion',
        ]);
        DB::table('categories')->insert([
            'name' => 'Diseno',
        ]);
    }
}
