<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Mpcs\Core\Facades\Core;

// Push SSE Route
Route::group([
    'as'            => "stream.",
    'prefix'        => "stream",
    'namespace'     => 'Mpcs\PushSse\Http\Controllers',
    'middleware'    => Core::getUniversalMiddlewares('ui'),
], function (Router $router) {
    $router->get('push_sse', 'PushSseController@stream')->name('push_sse');
});
