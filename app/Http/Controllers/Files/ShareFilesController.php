<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ShareFilesController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('files/ShareFiles', []);
    }
}
