# Mpcs Core Extention : PushSse

## install

```bash
php artisan mpcs-push-sse:install
```

## ENV variables

```
## PUSH SSE 사용여부 설정
MPCS_PUSH_SSE_ENABLED=false

## PUSH SSE 서버 딜레이 설정
MPCS_PUSH_SSE_INTERVAL=5

## PUSH SSE 윈도우 푸시 환영 타이틀 설정
MPCS_PUSH_SSE_NOTIFY_TITLE=

## PUSH SSE 윈도우 푸시 환영 메시지 설정
MPCS_PUSH_SSE_NOTIFY_MESSAGE=
```

## vendor:publish

```bash
php artisan vendor:publish --provider="Mpcs\PushSse\PushSseServiceProvider"

## or

php artisan vendor:publish --tag="mpcs-push-sse-assets"
php artisan vendor:publish --tag="mpcs-push-sse-config"
```

## 적용방법

### Back-end

> 옵션에 ARRAY 형식의 배열은 반드시 JSON_ENCODE를 사용해야 합니다.

```php
### Article Repository에 적용된 예시

use Exit11\PushSse\Facades\PushSse;

// PushSse 클래스가 있는지, 사용가능한지 확인
if (class_exists('Exit11\PushSse\Facades\PushSse') && config('mpcspushsse.enabled')) {
    // PushSse 클래스가 있으면 푸시 전송
    PushSse::sseQueue($pushMessage, $title, [
        'is_private' => false, // 공개, 비공개 여부
        'notification' => $is_push_notification, // 토스트 알림여부
        'pushed_at' => $this->model->released_at, // 푸시 알림 시간 지정
        'params' => json_encode([
            'id'    => $this->model->id,
            'title' => $this->model->title,
            'summary' => $this->model->summary,
            'article_category_ids' => json_encode($this->model->article_category_ids),
            'released_at' => $this->model->released_at,
        ]), // 푸시 알림 시 파라미터 전달
        'uuids' => $uidxs, // 비공개 푸시 알림 시 푸시 대상 유저 전달
    ], $eventName);
}
```

### Front-end

```javascript
// Blade 에서 사용할 예시
@push('after_app_src_scripts')
        <script>
            // Customize the notification channel
            PUSHSSEMESSAGE.getChannel({$eventName}, (data) => {
                // custom code
                console.log(data);
            });
        </script>
@endpush
```
