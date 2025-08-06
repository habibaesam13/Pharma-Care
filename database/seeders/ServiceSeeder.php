<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    //          // Disable foreign key checks
    // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    // // Truncate the table
    // DB::table('services')->truncate();

    // // Re-enable foreign key checks
    // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $services = [
    ['name' => 'Giving an injection'],
    ['name' => 'Changing a dressing/wound'],
    ['name' => 'Inserting a cannula/solution'],
    ['name' => 'Monitoring a chronic patient'],
    ['name' => 'Elderly care'],
];

foreach ($services as $service) {
    Service::create($service);
}

    }
}
