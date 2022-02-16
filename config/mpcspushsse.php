<?php

return [
    // SSE 사용여부
    'enabled' => env('MPCS_PUSH_SSE_ENABLED', false),

    // 지연시간 (초)
    'interval' => env('MPCS_PUSH_SSE_INTERVAL', 15),

    // 윈도우 초기설정 알림 타이틀
    'notify_title' => env('MPCS_PUSH_SSE_NOTIFY_TITLE', "MPCS Push Title"),

    // 윈도우 초기설정 알림 내용
    'notify_message' => env('MPCS_PUSH_SSE_NOTIFY_MESSAGE', "MPCS Push Message"),

    // 이벤트 로그 유지 여부
    'keep_events_logs' => false,
];
