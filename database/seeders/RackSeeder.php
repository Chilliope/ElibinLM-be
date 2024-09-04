<?php

namespace Database\Seeders;

use App\Models\Rack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $racks = [
            'Rak-1',
            'Rak-2',
            'Rak-3',
            'Rak-4',
            'Rak-5',
            'Rak-6',
            'Rak-7',
            'Rak-8',
            'Rak-9',
            'Rak-10',
            'Rak-11',
            'Rak-12'
        ];

        foreach ($racks as $rackName) {
            Rack::create([
                'rack' => $rackName
            ]);
        }
    }
}
