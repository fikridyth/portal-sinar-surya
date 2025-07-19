@php
    $counter = 1;
    $countPembayaran1 = count($pembayaran1);
    $entryCount = 0;
    $pageBreak = false;

    $counterGenap = 2;
    $countPembayaran2 = count($pembayaran2);
    $entryCountGenap = 0;
    $pageBreakGenap = false;
@endphp
<div class="container mt-4">
    <div class="row" style="height: 150%;">
        <div class="col-6">
            @foreach ($pembayaran1 as $bayar)
                <hr class="dashed-line">
                <div class="">TANGGAL : {{ \Carbon\Carbon::parse($bayar['data'][0]->date)->format('d/m/Y') }}</div>
                <div class="row">
                    <div class="col-8">{{ $bayar['data'][0]->supplier->nama }}</div>
                    <div class="col-4 d-flex justify-content-end">
                        {{ str_pad($counter, 3, 0, STR_PAD_LEFT) }}@php $counter+=2; @endphp</div>
                </div>
                <div class="row">
                    <div class="col-1">T</div>
                    <div class="col-2">{{ $bayar['data'][0]->nomor_giro }}</div>
                    <div class="col-5 d-flex justify-content-end">{{ $bayar['data'][0]->date_last }}</div>
                    <div class="col-4 d-flex justify-content-end">RP. {{ number_format($bayar['data'][0]->total_with_materai) }}
                    </div>
                </div>
                @if (isset($bayar['data'][1]))
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-5 d-flex justify-content-end">{{ $bayar['data'][1]->nomor_giro }}</div>
                        <div class="col-4 d-flex justify-content-end">RP. {{ number_format($bayar['data'][1]->total_with_materai) }}
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">-</div>
                    </div>
                @endif
                <hr class="dashed-line">
                @php
                    $entryCount++; // Increment the entry counter
                @endphp

                @if ($parameter == 0) @if ($entryCount % 11 == 0 && $entryCount < $countPembayaran1 && !$pageBreak)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreak = true; @endphp
                @endif @endif
                @if ($parameter == 1) @if ($entryCount % 10 == 0 && $entryCount < $countPembayaran1 && !$pageBreak)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreak = true; @endphp
                @endif @endif
                @if ($parameter == 2) @if ($entryCount % 9 == 0 && $entryCount < $countPembayaran1 && !$pageBreak)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreak = true; @endphp
                @endif @endif
                @if ($parameter == 3) @if ($entryCount % 8 == 0 && $entryCount < $countPembayaran1 && !$pageBreak)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreak = true; @endphp
                @endif @endif
                @if ($parameter == 4) @if ($entryCount % 7 == 0 && $entryCount < $countPembayaran1 && !$pageBreak)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreak = true; @endphp
                @endif @endif
                @if ($parameter == 5) @if ($entryCount % 6 == 0 && $entryCount < $countPembayaran1 && !$pageBreak)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreak = true; @endphp
                @endif @endif
                @if ($parameter == 6) @if ($entryCount % 5 == 0 && $entryCount < $countPembayaran1 && !$pageBreak)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreak = true; @endphp
                @endif @endif
                @if ($parameter == 7) @if ($entryCount % 4 == 0 && $entryCount < $countPembayaran1 && !$pageBreak)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreak = true; @endphp
                @endif @endif
                @if ($parameter == 8) @if ($entryCount % 3 == 0 && $entryCount < $countPembayaran1 && !$pageBreak)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreak = true; @endphp
                @endif @endif
                @if ($parameter == 9) @if ($entryCount % 2 == 0 && $entryCount < $countPembayaran1 && !$pageBreak)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreak = true; @endphp
                @endif @endif
                @if ($parameter == 10) @if ($entryCount % 1 == 0 && $entryCount < $countPembayaran1 && !$pageBreak)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreak = true; @endphp
                @endif @endif
            @endforeach
        </div>
        <div class="col-6">
            @foreach ($pembayaran2 as $index => $bayar)
                <hr class="dashed-line">
                <div class="">TANGGAL : {{ \Carbon\Carbon::parse($bayar['data'][0]->date)->format('d/m/Y') }}</div>
                <div class="row">
                    <div class="col-8">{{ $bayar['data'][0]->supplier->nama }}</div>
                    <div class="col-4 d-flex justify-content-end">
                        {{ str_pad($counterGenap, 3, 0, STR_PAD_LEFT) }}@php $counterGenap+=2; @endphp</div>
                </div>
                <div class="row">
                    <div class="col-1">T</div>
                    <div class="col-2">{{ $bayar['data'][0]->nomor_giro }}</div>
                    <div class="col-5 d-flex justify-content-end">{{ $bayar['data'][0]->date_last }}</div>
                    <div class="col-4 d-flex justify-content-end">RP. {{ number_format($bayar['data'][0]->total_with_materai) }}
                    </div>
                </div>
                @if (isset($bayar['data'][1]))
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-5 d-flex justify-content-end">{{ $bayar['data'][1]->nomor_giro }}</div>
                        <div class="col-4 d-flex justify-content-end">RP.
                            {{ number_format($bayar['data'][1]->total_with_materai) }}</div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">-</div>
                    </div>
                @endif
                <hr class="dashed-line">
                @php
                    $entryCountGenap++; // Increment the entry counter
                @endphp

                @if ($parameter == 0) @if ($entryCountGenap % 11 == 0 && $entryCountGenap < $countPembayaran2 && !$pageBreakGenap)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreakGenap = true; @endphp
                @endif @endif
                @if ($parameter == 1) @if ($entryCountGenap % 10 == 0 && $entryCountGenap < $countPembayaran2 && !$pageBreakGenap)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreakGenap = true; @endphp
                @endif @endif
                @if ($parameter == 2) @if ($entryCountGenap % 9 == 0 && $entryCountGenap < $countPembayaran2 && !$pageBreakGenap)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreakGenap = true; @endphp
                @endif @endif
                @if ($parameter == 3) @if ($entryCountGenap % 8 == 0 && $entryCountGenap < $countPembayaran2 && !$pageBreakGenap)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreakGenap = true; @endphp
                @endif @endif
                @if ($parameter == 4) @if ($entryCountGenap % 7 == 0 && $entryCountGenap < $countPembayaran2 && !$pageBreakGenap)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreakGenap = true; @endphp
                @endif @endif
                @if ($parameter == 5) @if ($entryCountGenap % 6 == 0 && $entryCountGenap < $countPembayaran2 && !$pageBreakGenap)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreakGenap = true; @endphp
                @endif @endif
                @if ($parameter == 6) @if ($entryCountGenap % 5 == 0 && $entryCountGenap < $countPembayaran2 && !$pageBreakGenap)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreakGenap = true; @endphp
                @endif @endif
                @if ($parameter == 7) @if ($entryCountGenap % 4 == 0 && $entryCountGenap < $countPembayaran2 && !$pageBreakGenap)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreakGenap = true; @endphp
                @endif @endif
                @if ($parameter == 8) @if ($entryCountGenap % 3 == 0 && $entryCountGenap < $countPembayaran2 && !$pageBreakGenap)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreakGenap = true; @endphp
                @endif @endif
                @if ($parameter == 9) @if ($entryCountGenap % 2 == 0 && $entryCountGenap < $countPembayaran2 && !$pageBreakGenap)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreakGenap = true; @endphp
                @endif @endif
                @if ($parameter == 10) @if ($entryCountGenap % 1 == 0 && $entryCountGenap < $countPembayaran2 && !$pageBreakGenap)
                    <div style="page-break-after: always;"></div>
                    @php $pageBreakGenap = true; @endphp
                @endif @endif
            @endforeach
        </div>
    </div>
</div>
