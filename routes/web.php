<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResumeController;

// Default Laravel welcome page
Route::get('/', action: function () {
    return view('home');
});

Route::get('/about', action: function () {
    return view('about');
});

// POST route to handle the file upload
Route::post('/resume/upload', [ResumeController::class, 'generateCoverLetter'])->name('generate.letter');
// POST route to handle the PDF download
Route::post('/resume/download', [ResumeController::class, 'downloadPDF'])->name('download.pdf');