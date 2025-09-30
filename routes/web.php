<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return json_encode(['message' => 'API is working']);
});

