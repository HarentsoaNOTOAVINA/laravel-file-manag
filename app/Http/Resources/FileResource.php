<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property bool $is_folder
 * @property string|null $mime
 * @property int|null $size
 * @property Collection|null $children
 */
class FileResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'key' => (string) $this->id,
            'label' => $this->name,
            'data' => $this->is_folder ? 'Folder' : 'File',
            'icon' => $this->is_folder ? 'pi pi-fw pi-folder' : 'pi pi-fw pi-file',
            'children' => $this->whenLoaded('children', function() {
                return $this->children->map(fn($file) => new FileResource($file));
            }, []),
            'is_folder' => $this->is_folder,
            'mime' => $this->mime,
            'size' => $this->size,
        ];
    }
}
