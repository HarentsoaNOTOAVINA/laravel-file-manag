<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class SharedFilesController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('files/SharedFiles', []);
    }

}
