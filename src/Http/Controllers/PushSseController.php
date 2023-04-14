<?php

namespace Mpcs\PushSse\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Mpcs\PushSse\Models\PushSse as Model;
use Illuminate\Support\Carbon;

class PushSseController extends BaseController
{

    public function stream(Model $model): StreamedResponse
    {

        $user = auth()->check() ? auth()->user() : exit;
        $uidx = $user ? $user->uidx : null;

        $response = new StreamedResponse();

        $response->setCallback(function () use ($model, $uidx) {

            // 클라이언트 연결 해제시 루프 종료
            if (connection_aborted()) {
                return;
            }

            // 오래된 로그 무효화된 데이터 삭제
            $model->deleteOlder();
            $model->updateDelivered();

            $deliveryModel = $model->deliverable()->get();

            echo ':' . str_repeat(' ', 2048) . "\n"; // 2 kB padding for IE
            echo "retry: 5000\n";

            if (!$deliveryModel) {
                // 새 푸시가 없을 경우 메세지
                echo ": heartbeat\n\n";
            } else {
                $clientId = $model->getClientId();

                foreach ($deliveryModel as $delivery) {

                    $clientModel = $model->CheckedPush($delivery->id)->first();

                    if ($clientModel) {
                        // 새 푸시가 없을 경우 메세지
                        echo ": heartbeat\n\n";
                    } else {
                        $deliveryData = json_encode([
                            'title'   => $delivery->title,
                            'message' => $delivery->message,
                            'params'  => $delivery->params,
                            'variant'  => $delivery->variant,
                            'url'  => $delivery->url,
                            'notification' => $delivery->notification,
                            'time' => Carbon::createFromTimeStamp(strtotime($delivery->pushed_at))->diffForHumans(),
                        ]);
                        if ($delivery->is_private) {
                            foreach ($delivery->uuids as $uuid) {
                                if ($uuid->uuid == $uidx) {
                                    echo "id: " . $delivery->id . "\n";
                                    echo "event: " . $uuid->uuid . "\n";
                                    echo "data: " . $deliveryData . "\n\n";
                                }
                            }
                        } else {
                            echo "id: " . $delivery->id . "\n";
                            echo "event: " . $delivery->event . "\n";
                            echo "data: " . $deliveryData . "\n\n";
                        }
                    }

                    $clientModel = new $model();
                    $clientModel->title = $delivery->title;
                    $clientModel->message = $delivery->message;
                    $clientModel->event = $delivery->event;
                    $clientModel->push_check_id = $delivery->id;
                    $clientModel->client = $clientId;
                    $clientModel->delivered = '1';
                    $clientModel->save();
                }
            }
            if (ob_get_level() > 0) {ob_flush();}
            flush();
            sleep(config('mpcspushsse.interval'));
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');
        return $response->send();
    }
}
