<?php

use App\Http\Controllers\SignatureController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function(){
    dd('something');
});
//Route::get('/signature', [SignatureController::class, 'index']);

Route::get('{any?}', function() {
    return view('application');
})->where('any', '.*');
