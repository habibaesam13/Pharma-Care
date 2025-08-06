<?php

namespace Database\Seeders;

use App\Models\NurseService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NurseServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $NurseServices=[
            [
                'nurse_id' => 1,
                'service_id' => 1,
            ],
            [
                'nurse_id' => 1,
                'service_id' => 2,
            ],
            [
                'nurse_id' => 2,
                'service_id' => 3,
            ],
            [
                'nurse_id' => 2,
                'service_id' => 4,
            ],
            [
                'nurse_id' => 3,
                'service_id' => 1,
            ],
            [
                'nurse_id' => 3,
                'service_id' => 2,
            ],
            [
                'nurse_id' => 4,
                'service_id' => 3,
            ],
            [
                'nurse_id' => 4,
                'service_id' => 4,
            ],
        ];
        foreach ($NurseServices as $NurseService) {
            NurseService::create($NurseService);
        }
    }
}
