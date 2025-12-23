<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class FileListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_their_files(): void
    {
        $user = User::factory()->create();
        
        $folder = File::factory()->create([
            'name' => 'My Folder',
            'is_folder' => true,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $file = File::create([
            'name' => 'My File.txt',
            'is_folder' => false,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'parent_id' => $folder->id,
            'mime' => 'text/plain',
            'size' => 1024,
        ]);

        $response = $this->actingAs($user)
            ->get(route('files'));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('files/ListFile')
            ->has('files', 1) // files is now the direct array
            ->where('files.0.label', 'My Folder')
            ->where('files.0.children.0.label', 'My File.txt')
        );
    }
}
