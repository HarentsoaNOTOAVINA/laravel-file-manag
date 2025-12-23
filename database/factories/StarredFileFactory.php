<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\StarredFile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StarredFileFactory extends Factory
{
    protected $model = StarredFile::class;

    public function definition(): array
    {
        return [
            'file_id' => File::factory(),
            'user_id' => User::factory(),
        ];
    }
}
