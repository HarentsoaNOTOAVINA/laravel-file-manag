<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\FileShare;
use App\Models\StarredFile;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\StarredFileFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        File::factory(10)->create();
        FileShare::factory(3)->create();
        StarredFile::factory(3)->create();
    }
}
