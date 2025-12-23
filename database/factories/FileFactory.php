<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'path' => $this->faker->filePath(),
            'is_folder' => false,
            'mime' => $this->faker->mimeType(),
            'size' => $this->faker->numberBetween(1024, 1024 * 1024 * 10), // 1KB to 10MB
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }

    public function folder(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_folder' => true,
            'mime' => null,
            'size' => null,
        ]);
    }
}
