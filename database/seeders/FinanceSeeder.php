<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Finances;

class FinanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Finances::factory(10)->create(); // Cria 10 registros
    }
}
