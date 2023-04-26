<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultValues = TransactionType::defaultValues();

        foreach ($defaultValues as $value) {
            TransactionType::updateOrCreate([
                'name' => $value,
            ]);
        }
    }
}
