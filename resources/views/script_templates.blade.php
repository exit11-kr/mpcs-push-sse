{{-- blade-formatter-disable --}}
{{-- handlebarjs에서 조건문 공백이 생기면 에러납니다. 블레이드에서 포매터 적용을 안하려면 "blade-formatter-disable"를 추가합니다. --}}

{{-- 상단 알람 아이템 --}}
<script type="text/template" id="script-template-push_sse_notification_item">
    <div class="bg-light w-100 text-nowrap mt-2 p-2 rounded position-relative"  @{{#unless unread}} style="opacity:0.3" @{{/unless}}>
        <div class="d-flex justify-content-between">
            <p class="mb-0 text-truncate text-@{{ variant }} fw-bold text-start">
                <i class="mdi mdi-bell-outline mr-1"></i>
                <small>
                    @{{title}}
                </small>
            </p>
            <p class="mb-0 text-end text-muted ps-3">
                <small>
                    <i class="mdi mdi-clock-outline mr-1"></i>
                    @{{time}}
                </small>
            </p>
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
            <p class="mb-0 ps-2 text-truncate text-start">
                @{{message}}
            </p>
            @{{#if url}}
                <a href="@{{url}}" class="btn btn-dark btn-sm btn-icon">
                    <i class="mdi mdi-link mr-1"></i>
                </a>
            @{{/if}}
        </div>
        
        @{{#if unread}}
            <span class="position-absolute top-0 start-0 translate-middle p-1 rounded-circle bg-danger">
            <span class="visually-hidden">New Notification</span>
            </span>
        @{{/if}}
    </div>
</script>






































