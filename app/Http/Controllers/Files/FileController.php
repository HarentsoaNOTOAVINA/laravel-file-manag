<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Http\Services\FilesServices;
use App\Models\File;
use Inertia\Inertia;

class FileController extends Controller
{

    public function __construct(
        private readonly FilesServices $filesServices
    ){}

    public function __invoke()
    {
        $files = $this->filesServices->getAllFilesForConnectedUser();

        $response =  $files->map(fn($file) => new FileResource($file));

        return Inertia::render('files/ListFile', [
            'files' => $response
        ]);
    }

    public function createFolder()
    {

    }

    public function uploadFile()
    {

    }

    public function download()
    {

    }

    public function share()
    {

    }

    public function trash()
    {

    }

    public function restore()
    {

    }

    public function delete()
    {

    }
}

