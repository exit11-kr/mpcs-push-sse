<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Mpcs\Core\Facades\Core;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

$tenantyMiddlewares = [InitializeTenancyByDomain::class, PreventAccessFromCentralDomains::class];

// Push SSE Route
Route::group([
    'as'            => "stream.",
    'prefix'        => "stream",
    'namespace'     => 'Mpcs\PushSse\Http\Controllers',
    'middleware'    => array_merge($tenantyMiddlewares, ['web']),
], function (Router $router) {
    $router->get('push_sse', 'PushSseController@stream')->name('push_sse');
});
