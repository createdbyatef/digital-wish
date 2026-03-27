<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Item;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin PUNB',
            'email' => 'admin@punb.gov.my',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'department' => 'IT',
        ]);

        // Create Staff User
        User::create([
            'name' => 'Wan Muhammad Atef',
            'email' => 'atef@punb.gov.my',
            'password' => bcrypt('staff123'),
            'role' => 'staff',
            'department' => 'Technology',
        ]);

        // Create Sample Inventory Items
        $items = [
            ['item_name' => 'A4 Paper (Ream)', 'category' => 'Paper', 'stock_quantity' => 50, 'unit_price' => 12.50, 'min_threshold' => 10],
            ['item_name' => 'Blue Pen', 'category' => 'Writing', 'stock_quantity' => 200, 'unit_price' => 1.50, 'min_threshold' => 30],
            ['item_name' => 'Black Pen', 'category' => 'Writing', 'stock_quantity' => 180, 'unit_price' => 1.50, 'min_threshold' => 30],
            ['item_name' => 'Stapler', 'category' => 'Equipment', 'stock_quantity' => 15, 'unit_price' => 18.90, 'min_threshold' => 5],
            ['item_name' => 'Stapler Bullet', 'category' => 'Equipment', 'stock_quantity' => 100, 'unit_price' => 3.90, 'min_threshold' => 20],
            ['item_name' => 'Sticky Notes (Pack)', 'category' => 'Paper', 'stock_quantity' => 60, 'unit_price' => 5.50, 'min_threshold' => 10],
            ['item_name' => 'Correction Tape', 'category' => 'Writing', 'stock_quantity' => 40, 'unit_price' => 4.90, 'min_threshold' => 10],
            ['item_name' => 'Paper Clip (Box)', 'category' => 'Equipment', 'stock_quantity' => 3, 'unit_price' => 2.50, 'min_threshold' => 5],
            ['item_name' => 'Toner Cartridge HP', 'category' => 'IT Equipment', 'stock_quantity' => 4, 'unit_price' => 280.00, 'min_threshold' => 2],
            ['item_name' => 'Whiteboard Marker', 'category' => 'Writing', 'stock_quantity' => 25, 'unit_price' => 6.90, 'min_threshold' => 8],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
