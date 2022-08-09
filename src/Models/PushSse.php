<?php

namespace Mpcs\PushSse\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PushSse extends Model
{
    protected $table = 'push_sses';
    protected $guarded = ['id'];

    /**
     * scopeDelivered
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeDeliverable($query)
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $query = $query->where('pushed_at', '<=', $now);
        return $query->where('delivered', 0);
    }

    /**
     * scopeCheckedPush
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeCheckedPush($query, $deliveryId)
    {
        return $query
            ->where('push_check_id', $deliveryId)
            ->where('client', $this->getClientId());
    }

    /**
     * scopeDeleteOlder
     * 클라이언트에 전송돤 푸시가 있는 경우 이전 푸시를 삭제한다.
     * @param  mixed $query
     * @return void
     */
    public function scopeDeleteOlder($query)
    {
        return $query
            ->where('created_at', '<=', $this->delayTime())
            ->where('client', '!=', '')
            ->delete();
    }

    /**
     * scopeUpdateDelivered
     * 푸시 시간과 생성시간이 딜레이타임보다 작은 경우에만 전송상태로 변경한다.
     * @param  mixed $query
     * @return void
     */
    public function scopeUpdateDelivered($query)
    {
        return $query
            ->where('pushed_at', '<=', $this->delayTime())
            ->where('created_at', '<=', $this->delayTime())
            ->update(['delivered' => '1']);
    }

    /**
     * delayTime
     *
     * @return void
     */
    public function delayTime()
    {
        $now = Carbon::now();
        $date = $now->modify('-' . (config('mpcspushsse.interval') * 2) . ' seconds');
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * getClientId
     *
     * @return string
     */
    public function getClientId(): string
    {
        return md5(php_uname('n') . $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
    }

    /**
     * setPushedAtAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function setPushedAtAttribute($value)
    {
        $this->attributes['pushed_at'] = $value ?? Carbon::now()->format('Y-m-d H:i:s');
    }


    /**
     * uuids
     *
     * @return void
     */
    public function uuids()
    {
        return $this->hasMany(PushSseUuid::class, 'push_sse_id');
    }

    /**
     * Deletes already processed events
     */
    public static function deleteProcessed()
    {
        if (!config('mpcspushsse.keep_events_logs')) {
            return PushSse::where('delivered', 1)->delete();
        }
    }

    /**
     * 부팅시 관계로부터 모델을 분리
     *
     * 삭제를 하더라도 기존의 권한을 유지하려면 boot 펑션 삭제
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->uuids()->delete();
        });
    }
}
