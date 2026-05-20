<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Notice::factory()->count(15)->create();
    }
}
