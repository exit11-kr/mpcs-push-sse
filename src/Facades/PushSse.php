<?php

namespace Mpcs\PushSse\Facades;

use Illuminate\Support\Facades\Facade;

class PushSse extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Mpcs\PushSse\PushSse::class;
    }
}
