<?php

namespace Exit11\PushSse\Facades;

use Illuminate\Support\Facades\Facade;

class PushSse extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Exit11\PushSse\PushSse::class;
    }
}
