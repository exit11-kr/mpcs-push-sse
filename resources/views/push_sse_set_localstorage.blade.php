@if (config('mpcspushsse.enabled'))
    @push('header_script')
        <script>
            const pushSseNotificationChecked = localStorage.getItem("pushSseNotificationChecked");
            if (!pushSseNotificationChecked) {
                localStorage.setItem("pushSseNotificationChecked", "true");
            }
            localStorage.setItem("pushSseStorage", JSON.stringify([]));
        </script>
    @endpush
@endif
