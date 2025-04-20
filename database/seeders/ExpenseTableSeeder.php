<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('expenses')->insert([
            [
                'title' => 'Lunch at cafe',
                'amount' => 50000,
                'category' => 'food',
                'type' => 'expense',
                'description' => 'Ate nasi goreng at cafe near office',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Monthly internet bill',
                'amount' => 300000,
                'category' => 'bills',
                'type' => 'expense',
                'description' => 'Telkomsel Orbit 100 Mbps',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Monthly Salary',
                'amount' => 5000000,
                'category' => 'others',
                'type' => 'income',
                'description' => 'My Salary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Grab Ride to Campus',
                'amount' => 25000,
                'category' => 'transportation',
                'type' => 'expense',
                'description' => 'Naik grab ke kampus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Buy A Book',
                'amount' => 100000,
                'category' => 'education',
                'type' => 'expense',
                'description' => 'How to be who you want to be',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
