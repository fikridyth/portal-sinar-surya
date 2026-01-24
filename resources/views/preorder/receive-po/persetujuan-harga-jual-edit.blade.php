@extends('main')

@section('content')
    <div class="container mb-7">
        <div class="card mt-n3">
            <div class="card-body mt-n3">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">NOMOR BUKTI</label>
                            <input type="text" class="mx-2" disabled value="{{ $preorder->nomor_po }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">SUPPLIER</label>
                            <input type="text" class="mx-2" size="30" disabled value="{{ $preorder->supplier->nama }}">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">ALAMAT</label>
                            <input type="text" class="mx-2" size="35" disabled value="{{ $preorder->supplier->alamat1 }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('persetujuan-harga-jual-update', enkrip($preorder->id)) }}" method="POST" class="form" id="filter-form">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div style="overflow-x: auto; height: 600px; border: 1px solid #ccc;">
                        <table class="table table-bordered" style="width: 100%; table-layout: auto;">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center">HARGA SEMENTARA</th>
                                    <th class="text-center"></th>
                                    <th colspan="2" class="text-center">HARGA SATUAN</th>
                                    <th class="text-center"></th>
                                    <th colspan="2" class="text-center">HARGA SATUAN</th>
                                    <th colspan="2" class="text-center"></th>
                                </tr>
                                <tr>
                                    <th class="text-center">&#9989;</th>
                                    <th class="text-center">NAMA BARANG</th>
                                    <th class="text-center">QTY</th>
                                    <th class="text-center">BELI LAMA</th>
                                    <th class="text-center">BELI BARU</th>
                                    <th class="text-center">NAIK(%)</th>
                                    <th class="text-center">JUAL LAMA</th>
                                    <th class="text-center">JUAL BARU</th>
                                    <th class="text-center">MK UP</th>
                                    <th class="text-center">GANTI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detail as $index => $dtl)
                                    @php
                                        $product = App\Models\Product::where('kode', $dtl['kode'])->first();
                                        $productHarga = App\Models\HargaSementara::where('id_product', $product->id)->orderBy('created_at', 'desc')->first();
                                        $changeTextColor = (($product->harga_jual - $dtl['price']) / $dtl['price']) * 100;

                                        $hargaSetelahDiskonHeader = round($product->harga_pokok);
                                        if ($product->diskon1 > 0 && $product->diskon1 <= 99) {
                                            $hargaSetelahDiskonHeader = round($product->harga_pokok - ($product->harga_pokok * $product->diskon1 / 100));
                                        } else if ($product->diskon1 >= 100) {
                                            $hargaSetelahDiskonHeader = round($product->harga_pokok - $product->diskon1);
                                        } else {
                                            $hargaSetelahDiskonHeader = round($product->harga_pokok);
                                        }

                                        if ($product->ppn > 0) {
                                            $hargaSetelahDiskonHeader += round($hargaSetelahDiskonHeader * $product->ppn / 100);
                                        }
                                    @endphp
                                    <tr>
                                        <input type="text" name="preorder" value="{{ $preorder->id }}" hidden>
                                        <input type="text" name="no_preorder" value="{{ $preorder->nomor_po }}" hidden>
                                        <td class="text-center"></td>
                                        <td class="text-center">{{ $dtl['nama'] . '/' . $dtl['unit_jual'] }}</td>
                                        <td class="text-center">{{ $dtl['order'] }}</td>
                                        <input type="text" hidden name="harga_pokok[{{ $index }}]" id="persetujuan_harga_pokok_{{ $index }}" value="{{ $dtl['price'] }}">
                                        <input type="text" hidden name="nama[{{ $index }}]" value="{{ $dtl['nama'] . '/' . $dtl['unit_jual'] . '/' . $dtl['kode'] . '/' . $dtl['price'] }}">
                                        <td class="text-center">{{ number_format($product->harga_lama) }}</td>
                                        <td class="text-center" style="color: <?= $product->harga_lama > $dtl['price'] ? 'red' : 'black'; ?>">{{ number_format($hargaSetelahDiskonHeader) }}</td>
                                        <td class="text-center">{{ number_format((($dtl['price'] - $product->harga_lama) / $product->harga_lama) * 100, 2) }}</td>
                                        <td class="text-center" style="color: <?= $changeTextColor < 0 ? 'red' : 'black'; ?>">{{ number_format($product->harga_jual) }}</td>
                                        
                                        @php
                                            $roundedPrice = ((($hargaSetelahDiskonHeader) * $product->profit) / 100) + $hargaSetelahDiskonHeader;
                                            $roundedValue = round($roundedPrice / 50) * 50
                                            // if (strlen(($hargaSetelahDiskonHeader)) >= 6) {
                                            //     $roundedValue = round($roundedPrice, -3);
                                            // } elseif (strlen(($hargaSetelahDiskonHeader)) >= 4) {
                                            //     $roundedValue = round($roundedPrice, -2);
                                            // } elseif (strlen(($hargaSetelahDiskonHeader)) >= 2) {
                                            //     $roundedValue = round($roundedPrice, -1);
                                            // } else {
                                            //     $roundedValue = $roundedPrice;
                                            // }
                                        @endphp
                                        <td class="text-center"><input type="text" name="harga_jual[{{ $index }}]" id="persetujuan_harga_jual_{{ $index }}" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ $roundedValue }}" size="10"></td>

                                        <td class="text-center"><input type="text" name="mark_up[{{ $index }}]" id="persetujuan_mark_up_{{ $index }}" onkeypress='return validateNumberInput(event)' value="{{ $product->profit }}" size="5"></td>
                                        {{-- <td class="text-center"><input type="text" name="mark_up[{{ $index }}]" id="persetujuan_mark_up_{{ $index }}" onkeypress='return validateNumberInput(event)' value="{{ number_format((($product->harga_jual - $dtl['price']) / $dtl['price']) * 100, 2) }}" size="5"></td> --}}
                                        <td class="text-center"><input type="checkbox" data-kode="{{ $dtl['kode'] }}" data-harga-setelah-diskon="{{ $hargaSetelahDiskonHeader }}" data-harga-jual="{{ $product->harga_jual }}" value="{{ $index }}" class="select-product"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- <div class="row mt-4" style="margin-left: 25px;">
                        <div class="col-7"></div>
                        <div class="col-5">
                            <div class="form-group mx-2">
                                <label for="">TOTAL FAKTUR</label>
                                <input type="text" class="text-end mx-2 readonly-input" id="total_faktur" size="12" readonly>
                            </div>
                        </div>
                    </div> --}}
                    <div class="d-flex justify-content-center align-items-center mt-4">
                        <div class="mx-2">
                            <a class="btn btn-danger" href="{{ route('persetujuan-harga-jual') }}">KEMBALI</a>
                        </div>
                        <div class="mx-2">
                            <button type="submit" disabled id="ganti-harga-btn" class="btn btn-primary">GANTI HARGA</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize index counter
            let indexCounter = document.querySelectorAll('table tbody tr').length;

            // Handle changes to product selection checkboxes
            document.querySelectorAll('.select-product').forEach(function(checkbox) {
                checkbox.addEventListener('change', function(event) {
                    const kode = event.target.getAttribute('data-kode');
                    if (event.target.checked) {
                        // event.target.disabled = true;
                        document.getElementById('ganti-harga-btn').disabled = false;

                        // Fetch product data based on kode
                        fetch(`/get-products-by-kode/${kode}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.products) {
                                    const tbody = document.querySelector('table tbody');
                                    const parentRow = event.target.closest('tr');
                                    data.products.forEach(product => {
                                        const hargaPokok = product.harga_pokok;
                                        const diskon = product.diskon1 || 0;
                                        let hargaSetelahDiskon = hargaPokok;
                                        if (diskon > 0 && diskon <= 99) {
                                            hargaSetelahDiskon = hargaPokok - (hargaPokok * diskon / 100);
                                        } else if (diskon >= 100) {
                                            hargaSetelahDiskon = hargaPokok - diskon;
                                        }
                                        
                                        hargaSetelahDiskon = Math.max(0, hargaSetelahDiskon);
                                        const hargaJual = Math.round(hargaSetelahDiskon + (hargaSetelahDiskon * product.profit / 100));
                                        const hargaKelipatan = Math.round(hargaJual / 50) * 50;

                                        // Create a new row
                                        const newRow = document.createElement('tr');
                                        newRow.innerHTML = `
                                            <td class="text-center"></td>
                                            <td class="text-center">${product.nama}/${product.unit_jual}</td>
                                            <td class="text-center">0</td>
                                            <input type="text" hidden name="nama[${indexCounter}]" value="${product.nama}/${product.unit_jual}/${product.kode}/${product.harga_pokok}">
                                            <input type="text" hidden name="harga_pokok[${indexCounter}]" id="persetujuan_harga_pokok_${indexCounter}" value="${hargaSetelahDiskon}">
                                            <td class="text-center">${number_format(product.harga_lama)}</td>
                                            <td class="text-center"
                                                style="color: ${product.harga_lama > hargaSetelahDiskon ? 'red' : 'black'}">
                                                ${number_format(hargaSetelahDiskon)}
                                            </td>
                                            <td class="text-center">${number_format_mark_up(((hargaSetelahDiskon - product.harga_lama) / product.harga_lama) * 100)}</td>
                                            <td class="text-center" style="color: ${hargaSetelahDiskon > product.harga_jual ? 'red' : 'black'}">${number_format(product.harga_jual)}</td>
                                            <td class="text-center">
                                                <input type="text" name="harga_jual[${indexCounter}]" id="persetujuan_harga_jual_${indexCounter}" value="${hargaKelipatan}" size="10">
                                            </td>
                                            <td class="text-center">
                                                <input type="text" name="mark_up[${indexCounter}]" id="persetujuan_mark_up_${indexCounter}" value="${product.profit}" size="5">
                                            </td>
                                            <td class="text-center"><input type="checkbox" class="select-product" data-harga-setelah-diskon="${hargaSetelahDiskon}" data-harga-jual="${product.harga_jual}" value="${indexCounter}" checked></td>
                                        `;
                                        // tbody.appendChild(newRow);
                                        parentRow.insertAdjacentElement('afterend', newRow);
                                        
                                        // Attach event listeners for newly added rows
                                        attachEventListeners(indexCounter);
                                        indexCounter++;
                                    });
                                }
                            });
                    } else {
                        // Optional: Remove rows based on the unselected checkbox
                        const tbody = document.querySelector('table tbody');
                        // document.getElementById('ganti-harga-btn').disabled = true;
                        tbody.querySelectorAll(`input[name="kode[]"][value="${kode}"]`).forEach(input => input.closest('tr').remove());
                        // updateTotalFaktur();
                    }
                });
            });

            // Attach event listeners for pre-existing rows
            function attachEventListeners(index) {
                document.getElementById(`persetujuan_harga_jual_${index}`).addEventListener('input', function() {
                    updateMarkUp(index);
                });
                document.getElementById(`persetujuan_mark_up_${index}`).addEventListener('input', function() {
                    updateHargaJual(index);
                });
            }

            // Update markup percentage
            function updateMarkUp(index) {
                const hargaPokok = parseFloat(document.getElementById(`persetujuan_harga_pokok_${index}`).value) || 0;
                const hargaJual = parseFloat(document.getElementById(`persetujuan_harga_jual_${index}`).value) || 0;

                if (!isNaN(hargaPokok) && hargaPokok > 0) {
                    const markUpValue = ((hargaJual - hargaPokok) / hargaPokok) * 100;
                    document.getElementById(`persetujuan_mark_up_${index}`).value = markUpValue.toFixed(2);
                }
            }

            // Update selling price based on markup
            function updateHargaJual(index) {
                const hargaPokok = parseFloat(document.getElementById(`persetujuan_harga_pokok_${index}`).value) || 0;
                const markUp = parseFloat(document.getElementById(`persetujuan_mark_up_${index}`).value) || 0;

                if (!isNaN(hargaPokok) && hargaPokok > 0) {
                    const hargaJualValue = ((hargaPokok * markUp) / 100) + hargaPokok;
                    document.getElementById(`persetujuan_harga_jual_${index}`).value = number_format_harga_jual(Math.round(hargaJualValue / 50) * 50);
                }
            }

            @foreach ($detail as $index => $dtl)
                document.getElementById('persetujuan_harga_jual_{{ $index }}').addEventListener('input', function() {
                    updateMarkUp({{ $index }});
                });
                document.getElementById('persetujuan_mark_up_{{ $index }}').addEventListener('input', function() {
                    updateHargaJual({{ $index }});
                });
            @endforeach
        });

        function validateNumberInput(event) {
            var charCode = event.charCode || event.keyCode;
            var char = String.fromCharCode(charCode);

            // Allow digits (0-9) and dot (.)
            if ((charCode >= 48 && charCode <= 57) || char === '.' || char === '-') {
                return true;
            }
            return false;
        }

        function number_format(number) {
            return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function number_format_mark_up(number) {
            return Number(number).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function number_format_harga_jual(number) {
            return Number(number).toFixed(0);
        }

        const form = document.getElementById('filter-form');
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const checkboxes = document.querySelectorAll('.select-product');
            let adaHargaLebihKecil = false;

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const index = checkbox.value;

                    const hargaSetelahDiskon = parseFloat(
                        checkbox.dataset.hargaSetelahDiskon
                    );

                    const hargaJual = parseFloat(
                        checkbox.dataset.hargaJual
                    );

                    // const hargaJual = parseFloat(
                    //     document.getElementById(`persetujuan_harga_jual_${index}`).value
                    // );

                    if (hargaJual < hargaSetelahDiskon) {
                        adaHargaLebihKecil = true;
                    }
                }
            });

            const lanjutSubmit = () => {
                checkboxes.forEach(checkbox => {
                    if (!checkbox.checked) {
                        const index = checkbox.value;
                        [
                            `input[name="harga_pokok[${index}]"]`,
                            `input[name="nama[${index}]"]`,
                            `input[name="harga_jual[${index}]"]`,
                            `input[name="mark_up[${index}]"]`
                        ].forEach(selector => {
                            const el = document.querySelector(selector);
                            if (el) el.remove();
                        });
                    }
                });

                form.submit();
            };

            if (adaHargaLebihKecil) {
                Swal.fire({
                    title: 'Konfirmasi Harga',
                    text: 'Ada harga jual di bawah harga preorder. Tetap lanjutkan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan',
                    cancelButtonText: 'Tidak'
                }).then(result => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Masukkan Password Owner',
                            input: 'password',
                            inputPlaceholder: 'Password',
                            showCancelButton: true,
                            confirmButtonText: 'Verifikasi',
                            preConfirm: (password) => {
                                if (!password) {
                                    Swal.showValidationMessage('Password wajib diisi');
                                }
                                return password;
                            }
                        }).then(resultPassword => {
                            if (resultPassword.isConfirmed) {
                                fetch('/master/cek-password-owner', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document
                                            .querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        password: resultPassword.value
                                    })
                                })
                                .then(res => {
                                    if (!res.ok) throw new Error('Password salah');
                                    return res.json();
                                })
                                .then(data => {
                                    lanjutSubmit();
                                })
                                .catch(() => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: 'Password owner salah'
                                    });
                                });
                            }
                        });
                    }
                });
            } else {
                lanjutSubmit();
            }
        });
    </script>
@endsection
