@if (Core::user() && config('mpcspushsse.enabled'))
    <li id="pushSseNotification" class="nav-item">
        <div class="dropdown">
            <button id="pushSseNotificationBtn" class="btn btn-icon btn-nav-icon text-white position-relative"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-bell"></i>
                <span id="pushSseNotificationBadge"
                    class="position-absolute top-0 start-100 translate-middle p-1 rounded-circle d-none bg-danger border border-primary">
                    <span class="visually-hidden">New Notifications</span>
                </span>
            </button>
            <div class="dropdown-menu pb-0" style="width:340px;">
                <div class="d-flex justify-content-between align-items-center px-3">
                    <h6 class="mb-0 fw-bold">{{ trans('mpcs-push-sse::word.toast_notification_onoff') }}</h6>
                    <div class="form-check form-switch ps-0 mb-0">
                        <input id="pushSseNotificationChecked" class="form-check-input ms-0" type="checkbox"
                            name="push_sse_notification_checked" checked="">
                        <label class="form-check-label"></label>
                    </div>
                </div>
                <hr class="dropdown-divider">
                <h6 class="dropdown-header" data-title-list="{{ trans('mpcs-push-sse::word.new_notification_list') }}"
                    data-title-empty="{{ trans('mpcs-push-sse::word.empty_notification_datas') }}"></h6>
                <div id="pushSseNotificationItemWrap" class="overflow-scroll px-2"
                    style="max-height: calc(100vh - 200px)">
                    {{-- 알림메세지 리스트 --}}
                </div>
                <button id="pushSseNotificationClearBtn"
                    class="btn btn-sm btn-primary w-100 rounded-0 rounded-bottom mt-2">
                    {{ trans('mpcs-push-sse::word.all_clear_notifications') }}
                </button>
            </div>
        </div>
    </li>
@endif
