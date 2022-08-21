<?php

use App\Controllers\ApiController;
use App\Middleware\Before\ApiMiddlewareBefore;
use Hleb\Constructor\Routes\Route;


Route::prefix("/api");
Route::getGroup("API");

Route::post("/")->controller('ApiController@handler');

Route::endGroup("API");
