<?php

use App\Http\Controllers\Apis\ShortUrlController;

Route::resource('urls', ShortUrlController::class)
    ->only(['index', 'store'])
    ->middleware('throttle:60,1');
