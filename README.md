# Mpcs Core Extention : PushSse

## provider 실행

```
php .\artisan vendor:publish --provider="Exit11\PushSse\PushSseServiceProvider"
```

## migrate 실행

```
php .\artisan migrate
```

## config > filesystem.php 에 upload, temp 폴더 추가

```
    'upload' => [
                'driver' => 'local',
                'root' => storage_path('app/public/uploads'),
                'url' => env('APP_URL') . '/storage/uploads',
                'visibility' => 'public',
            ],

    'temp' => [
        'driver' => 'local',
        'root' => storage_path('app/temps'),
        'visibility' => 'public',
    ],
```
