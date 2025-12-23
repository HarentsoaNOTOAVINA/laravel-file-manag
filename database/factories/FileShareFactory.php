<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\FileShare;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileShareFactory extends Factory
{
    protected $model = FileShare::class;

    public function definition(): array
    {
        return [
            'file_id' => File::factory(),
            'user_id' => User::factory(),
        ];
    }
}
