<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\RouteDiscovery\Discovery\Discover;

Route::prefix('')->group(function () {
    Discover::controllers()->in(app_path('Http/Controllers/Api'));
});
