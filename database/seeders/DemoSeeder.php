<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!config('app.demo_mode')) {
            return;
        }

        $this->call([
            NoticeSeeder::class,
            EventSeeder::class,
            GradingSystemSeeder::class,
            GradeRuleSeeder::class,
            ExamSeeder::class,
            DemoUserSeeder::class,
            MarkSeeder::class,
            FinalMarkSeeder::class,
            AssignedTeacherSeeder::class,
            RoutineSeeder::class,
            FinanceDemoSeeder::class,
        ]);
    }
}
