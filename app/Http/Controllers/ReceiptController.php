<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReceiptController extends Controller
{
    public function show($filename)
    {
        $path = storage_path('app/public/payments/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'Receipt image file not found on disk.');
        }

        return response()->file($path, [
            'Content-Type' => mime_content_type($path) ?: 'image/jpeg',
            'Cache-Control' => 'no-cache, private',
        ]);
    }
}
