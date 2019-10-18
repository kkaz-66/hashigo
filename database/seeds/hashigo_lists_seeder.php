<?php

use Illuminate\Database\Seeder;

class hashigo_lists_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hashigo_lists')->insert([
            'member_id' => 1,
            'first_store_id' => 'J000140120',
            'second_store_id' => 'J000805613',
            'third_store_id' =>  'J000011690',
            'created_at' => NOW(),
        ]);
        DB::table('hashigo_lists')->insert([
            'member_id' => 1,
            'first_store_id' => 'J001041125',
            'second_store_id' => 'J000002572',
            'third_store_id' =>  'J000011690',
            'created_at' => NOW(),
        ]);
        DB::table('hashigo_lists')->insert([
            'member_id' => 1,
            'first_store_id' => 'J001162817',
            'second_store_id' => 'J001018368',
            'third_store_id' =>  'J000797077',
            'created_at' => NOW(),
        ]);
    }
}
