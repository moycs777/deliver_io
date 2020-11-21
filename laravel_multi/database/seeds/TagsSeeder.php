<?php

use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->insert([
            'name' => 'Rock',
            'category_id' => 1,
        ]);
        DB::table('tags')->insert([
            'name' => 'pop',
            'category_id' => 1,
        ]);
    }
}
