<?php

use Illuminate\Support\Facades\Route;

Route::get('/download-project', function () {
    $zipPath = base_path('../L-T.zip');
    
    if (file_exists($zipPath)) {
        return response()->download($zipPath);
    }
    
    return response()->json(['error' => 'Zip file not found'], 404);
});