@extends('main')

@section('content')
    <div class="container mb-3">
        <div class="card">
            <div class="card-body">
                <div style="overflow-x: auto; height: 600px; border: 1px solid #ccc;">
                    <table class="table table-bordered">
                        <h6 class="text-center mt-1 mb-3">DAFTAR TAGIHAN LANGGANAN</h6>
                        <thead>
                            <tr>
                                <th class="text-center">NAMA SUPPLIER</th>
                                <th class="text-center">NO DOKUMEN</th>
                                <th class="text-center">TANGGAL</th>
                                <th class="text-center">JUMLAH</th>
                                <th class="text-center">BONUS</th>
                                <th class="text-center">MATERAI</th>
                                <th class="text-center">TOTAL</th>
                                <th class="text-center">V</th>
                                {{-- <th class="text-center">SEMUA</th>
                                <th class="text-center">CICIL</th>
                                <th class="text-center">CICILAN</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (json_decode($piutang->detail, true) as $detail)
                                <tr>
                                    <td>{{ $detail['nama'] }}</td>
                                    <td>{{ $detail['nomor_bukti'] }}</td>
                                    <td class="text-center">{{ $detail['date'] }}</td>
                                    <td class="text-end">{{ number_format($detail['total_with_materai'], 0) }}</td>
                                    <td class="text-end">0</td>
                                    <td class="text-end">{{ number_format($detail['beban_materai'], 0) }}</td>
                                    <td class="text-end">{{ number_format($detail['total_with_materai'] + $detail['beban_materai'], 0) }}</td>
                                    <td class="text-center"><input type="checkbox"></td>
                                    {{-- <td class="text-center"><input type="checkbox" disabled></td>
                                    <td class="text-center"><input type="checkbox" disabled></td>
                                    <td></td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </form>
                <div class="d-flex justify-content-center align-items-center mt-4">
                    <div class="mx-2">
                        <a href="{{ route('daftar-tagihan.cetak', enkrip($piutang->id)) }}" class="btn btn-warning">CETAK</a>
                    </div>
                    <div class="mx-2">
                        <a href="{{ route('daftar-tagihan.proses', enkrip($piutang->id)) }}" class="btn btn-primary disabled-link">PROSES BAYAR</a>
                    </div>
                    <div class="mx-2">
                        <a href="{{ route('daftar-tagihan.index') }}" class="btn btn-danger">BATAL</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const processLink = document.querySelector('.disabled-link');

            // Fungsi untuk mengecek apakah semua checkbox dicentang
            function checkAllChecked() {
                const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                if (allChecked) {
                    processLink.classList.remove('disabled-link');
                } else {
                    processLink.classList.add('disabled-link');
                }
            }

            // Tambahkan event listener pada semua checkbox
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', checkAllChecked);
            });
        });
    </script>
@endsection
