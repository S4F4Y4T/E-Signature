<?php

use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SignerController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SignatureController;
use Illuminate\Support\Facades\Route;

Route::post('/document', [DocumentController::class, 'store']);
Route::get('/signer/{signer:short_url}', [SignerController::class, 'show']);
Route::post('/otp/{signer:short_url}', [SignerController::class, 'sendOTP']);
Route::get('/history/{signer:short_url}', [SignerController::class, 'history']);

Route::post('/review/{Signer:short_url}', [ReviewController::class, 'document']);
Route::post('/signature/{Signer:short_url}', [SignatureController::class, 'signature']);



