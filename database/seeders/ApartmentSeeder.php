<?php

namespace Database\Seeders;

use App\Models\Apartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = public_path('files/data.csv');

        if (!file_exists($csvFile)) {
            $this->command->error("CSV file not found: {$csvFile}");
            return;
        }

        $file = fopen($csvFile, 'r');
        fgetcsv($file, 0, ';');
        while (($row = fgetcsv($file, 0, ';')) !== false) {
            Apartment::create([
                'owner_name' => $row[0],
                'city' => $row[1],
                'address' => $row[2],
                'bedrooms' => (int)$row[3],
                'house_floors' => (int)$row[4],
                'floor' => (int)$row[5],
                'options' => array_map('trim', explode(',', $row[6])),
                'price' => (float)$row[7],
            ]);
        }

        fclose($file);

        $this->command->info('Apartments seeded successfully!');
    }
}
