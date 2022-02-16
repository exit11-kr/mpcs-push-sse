@if (Core::user() && config('mpcspushsse.enabled'))
    <script>
        function PUSHSSE() {}
        PUSHSSE.sseURL = "{{ route('stream.push_sse') }}";
        PUSHSSE.eventChannel = "{{ Core::user()->uidx }}";
        PUSHSSE.notifyTitle = "{{ config('mpcspushsse.notify_title') }}";
        PUSHSSE.notifyMessage = "{{ config('mpcspushsse.notify_message') }}";
    </script>

    @push('header_script')
        @component('mpcs-push-sse::script_templates')
        @endcomponent
    @endpush

    @push('after_app_src_scripts')
        <script src="{{ mix('/vendor/exit11/push-sse/js/index.js') }}"></script>
    @endpush
@endif
