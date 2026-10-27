@php
    $ctx = app(\App\Support\BscContext::class);
    $company = $ctx->company();
    $period = $ctx->period();
@endphp
<a href="{{ url('/admin') }}" class="arah-mobile-mark" aria-label="{{ config('bsc.brand_name') }}">
    <img src="{{ asset('images/favicon.svg') }}" alt="Rucisoft" width="30" height="30">
</a>
@if ($company && $period)
    <a href="{{ \App\Filament\Pages\PilihKonteks::getUrl() }}" class="arah-context" title="{{ $company->name }} · {{ $period->name }} — pilih satker BLU & perioda">
        <span class="arah-dot" style="background: {{ $period->status === 'berjalan' ? '#2E7D4F' : '#C88A12' }}"></span>
        <span>{{ $company->code }} · <span class="arah-context-long">{{ $period->name }}</span><span class="arah-context-short">{{ $period->year }}</span></span>
        @if ($period->status !== 'berjalan') <span class="arah-context-long" style="font-weight:500">({{ $period->status }})</span> @endif
    </a>
@endif
