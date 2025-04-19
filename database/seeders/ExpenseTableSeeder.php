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
                'balance' => 0,
                'title' => 'Lunch at cafe',
                'amount' => 50000,
                'category' => 'food',
                'type' => 'expense',
                'description' => 'Ate nasi goreng at cafe near office',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'balance' => 0,
                'title' => 'Monthly internet bill',
                'amount' => 300000,
                'category' => 'bills',
                'type' => 'expense',
                'description' => 'Telkomsel Orbit 100 Mbps',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'balance' => 0,
                'title' => 'Gaji Bulanan',
                'amount' => 5000000,
                'category' => 'others',
                'type' => 'income',
                'description' => 'Pendapatan utama dari kantor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'balance' => 0,
                'title' => 'Grab Ride to Campus',
                'amount' => 25000,
                'category' => 'transportation',
                'type' => 'expense',
                'description' => 'Naik grab ke kampus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'balance' => 0,
                'title' => 'Beli Buku',
                'amount' => 100000,
                'category' => 'education',
                'type' => 'expense',
                'description' => 'Buku pemrograman Laravel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
