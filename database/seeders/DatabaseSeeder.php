<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AcademicSettingSeeder::class,
            PermissionSeeder::class,
            SchoolSessionSeeder::class,
            SemesterSeeder::class,
            SchoolClassSeeder::class,
            SectionSeeder::class,
            CourseSeeder::class,
            DemoSeeder::class,
        ]);
    }
}
