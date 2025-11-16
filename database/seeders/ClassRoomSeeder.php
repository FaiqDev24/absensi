<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClassRoom;

class ClassRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            ['name' => 'X IPA 1'],
            ['name' => 'X IPA 2'],
            ['name' => 'XI IPA 1'],
            ['name' => 'XI IPA 2'],
            ['name' => 'XII IPA 1'],
            ['name' => 'XII IPA 2'],
        ];

        foreach ($classes as $class) {
            ClassRoom::create($class);
        }
    }
}
