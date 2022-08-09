<?php

namespace Mpcs\PushSse;

use Mpcs\PushSse\Models\PushSse as Model;
use Illuminate\Support\Facades\DB;
use Exception;

class PushSse
{


    /**
     * sseQueue
     *
     * @param  mixed $data : ["title" => "title", "message" => "message", "params" => "params"]
     * @param  mixed $event
     * @param  mixed $isPrivate
     * @param  mixed $notification
     * @return bool
     */
    public static function sseQueue($message, $title = null, $options = [], $event = "publicMessage")
    {

        if (!config('mpcspushsse.enabled')) {
            return false;
        }

        Model::deleteProcessed();

        DB::beginTransaction();
        try {
            $model = new Model;
            $model->event =   $event;
            $model->title =   $title ?? null;
            $model->message = $message;
            $model->params =  $options['params'] ?? null;
            $model->url =  $options['url'] ?? null;
            $model->variant =  $options['variant'] ?? "info";
            $model->pushed_at =  $options['pushed_at'] ?? now()->format('Y-m-d H:i:s');
            $model->is_private =   $options['is_private'] ?? false;
            $model->notification =   $options['notification'] ?? false;
            $model->delivered =   0; // 배달전

            if ($model->save()) {
                if ($model->is_private && !empty($options['uuids'])) {
                    foreach ($options['uuids'] as $uuid) {
                        $model->uuids()->create([
                            'uuid' => $uuid
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (Exception $e) {
            /* DB 트랜젝션 롤 */
            DB::rollback();
            throw $e;
        }

        return true;
    }
}
