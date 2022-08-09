<?php

namespace Mpcs\PushSse\Models;

use Illuminate\Database\Eloquent\Model;

class PushSseUuid extends Model
{
    protected $table = 'push_sse_uuids';
    protected $guarded = ['id'];
    public $timestamps = false;

    /**
     * Tries to identify different SSE connections
     *
     * @return string
     */
    public function getClientId(): string
    {
        return md5(php_uname('n') . $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
    }
}
