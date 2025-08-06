<?php

namespace Database\Seeders;

use App\Models\Nurse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NurseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    //      // Disable foreign key checks
    // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    // // Truncate the table
    // DB::table('nurses')->truncate();

    // // Re-enable foreign key checks
    // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Nurse::insert([
        [
            'name' => 'Nurse 1',
            'gender' => 'male',
            'phone' => '01234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Nurse 2',
            'gender' => 'male',
            'phone' => '01236567866',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Nurse 3',
            'gender' => 'female',
            'phone' => '01126567966',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Nurse 4',
            'gender' => 'female',
            'phone' => '01126634966',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    }
}
