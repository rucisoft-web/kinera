@php($notice = \App\Support\DemoNotice::current())
@if ($notice)
    <div class="arah-demo-banner" role="note">
        <span class="arah-demo-tag">Data demo</span>
        <span>{{ $notice }}</span>
    </div>
@endif
