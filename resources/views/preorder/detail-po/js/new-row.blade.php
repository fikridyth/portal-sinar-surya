<script>
    function disableAllCheckboxes() {
        // Select all checkboxes with the class 'select-checkbox'
        const checkboxes = document.querySelectorAll('.select-checkbox');

        // Iterate over each checkbox and disable it
        checkboxes.forEach(checkbox => {
            checkbox.disabled = true;
        });
    }

    function handleSelectChange(event) {
        const selectElement = event.target;
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const kode = selectedOption.getAttribute('data-kode');
        const isi = selectedOption.getAttribute('data-isi');
        const numericIsi = isi.replace(/\D/g, '');
        const isi2 = selectedOption.getAttribute('data-isi2');
        const numericIsi2 = isi2.replace(/\D/g, '');
        const jual = selectedOption.getAttribute('data-jual');

        // Find the closest row and update the data-kode cell
        const row = selectElement.closest('tr');
        const kodeCell = row.querySelector('#data-kode');
        const isiCell = row.querySelector('#data-isi');
        const isi2Cell = row.querySelector('#data-isi2');
        const jualCell = row.querySelector('#data-jual');
        kodeCell.textContent = kode;
        isiCell.textContent = numericIsi;
        isi2Cell.textContent = numericIsi2;
        jualCell.textContent = jual;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const tambahButton = document.getElementById('tambah-button');
        const tableBody = document.querySelector('#details-table tbody');
        const element = document.getElementById('current-index');
        const currentIndex = element && element.value ? parseInt(element.value, 10) || 0 : 0;

        let index = currentIndex; // Start index from the current index

        tambahButton.addEventListener('click', function() {
            const simpanButton = document.getElementById('simpan-button');
            simpanButton.disabled = false;
            tambahButton.disabled = true;

            index++;

            const newRow = document.createElement('tr');
            newRow.classList.add('fs-need');

            // <td>${index}</td>
            newRow.innerHTML = `
                <td class="text-center">
                    <div class="select-container">
                        <input class="form-check-input select-checkbox" type="checkbox" id="checkbox-${index}" onchange="handleCheckboxChange(this)">
                        <button type="button" id="edit-save-${index}" style="display:none;" onclick="handleSaveClick(this)">Save</button>
                    </div>
                </td>
                <td
                    class="text-center data-kode" id="data-kode"><input type="number" size="1" autofocus class="kode-input" min="1" step="1" style="width: 200px;"
                    onkeydown="handleEnterKode(event)">
                </td>
                <td></td>
                <td class="text-end" hidden id="data-isi"></td>
                <td class="text-end" id="data-isi2"></td>
                <td class="text-end" id="data-jual"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
            `;

            // tableBody.appendChild(newRow);
            tableBody.insertBefore(newRow, tableBody.firstChild);

            // Initialize Select2 on the newly added select element
            $(`#products-${index}`).select2({
                placeholder: '---Select Product / Barcode---',
                allowClear: true,
                dropdownAutoWidth: true
            });

            $(`#products-${index}`).on('select2:open', function(e) {
                // Fokuskan kotak pencarian di dalam dropdown Select2 setelah dropdown terbuka
                const searchBox = $(this).data('select2').dropdown.$search[0];
                if (searchBox) {
                    searchBox.focus();
                }
            });

            disableAllCheckboxes();
        });
    });

    // Enter di input kode
    function handleEnterKode(event) {
        if (event.key === 'Enter') {
            // Mengambil nilai dari input
            const inputValue = document.querySelector('.kode-input').value.trim();

            // Jika nilai kosong, klik tombol tambah list
            if (inputValue === '') {
                document.getElementById('tambah-list-button').click();
            } else {
                // Jika nilai terisi, lakukan AJAX untuk memproses input
                ajaxProses(inputValue);
            }
        }
    }

    var preorderId = <?php echo json_encode($preorder->id); ?>;
    var supplierId = <?php echo json_encode($preorder->supplier->id); ?>;

    function ajaxProses(inputValue) {
        $.ajax({
            url: '/get-data-from-barcode', // Ganti dengan URL yang sesuai
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                kode: inputValue,
                preorderId: preorderId,
                supplierId: supplierId
            },
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                } else {
                    var products = response.data;
                    var productRows = '';

                    // Loop melalui produk dan buat HTML untuk setiap baris tabel
                    products.forEach(function(product) {
                        productRows += `
                            <tr>
                                <td>${product.nama} / ${product.unit_jual}</td>
                                <td>${product.stok}</td>
                                <td>${number_format(product.harga_pokok)}</td>
                                <td>${number_format(product.harga_jual)}</td>
                                <td><input type="checkbox" class="product-checkbox" data-product-id="${product.id}" data-nama="${product.nama}" data-kode="${product.kode}"></td>
                            </tr>
                        `;
                    });

                    // Masukkan baris produk ke dalam tabel
                    $('#productDetails').html(productRows);

                    // Tampilkan modal
                    $('#productModal').fadeIn();
                }
            },
            error: function(xhr, status, error) {
                console.error('Terjadi kesalahan:', error);
            }
        });
    }
    
    // Fungsi untuk menutup modal
    $('.close-btn').click(function() {
        $('#productModal').fadeOut();
    });

    // Menutup modal jika pengguna mengklik area luar modal
    $(window).click(function(event) {
        if ($(event.target).is('#productModal')) {
            $('#productModal').fadeOut();
        }
    });

    function number_format(number) {
        return Number(number).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    // Fungsi untuk menangani klik tombol Simpan
    $('#saveBtn').click(function() {
        // Mengambil data produk yang dipilih
        var selectedProducts = [];
        $('.product-checkbox:checked').each(function() {
            var product = {
                id: $(this).data('product-id'),
                nama: $(this).data('nama'),
                kode: $(this).data('kode')
            };
            selectedProducts.push(product);
        });

        // Cek apakah ada produk yang dipilih
        if (selectedProducts.length > 0) {
            // Kirim data yang dipilih ke server menggunakan AJAX
            $.ajax({
                url: '/store-data-from-barcode', // Ganti dengan URL yang sesuai
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    products: selectedProducts,
                    preorderId: preorderId,
                    supplierId: supplierId
                },
                success: function(response) {
                    if (response.success) {
                        // alert('Produk berhasil disimpan!');
                        $('#productModal').fadeOut();
                        window.location.reload();
                    } else {
                        alert('Gagal menyimpan produk.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan:', error);
                }
            });
        } else {
            alert('Silakan pilih produk untuk disimpan.');
        }
    });

    // Store Data
    document.addEventListener('DOMContentLoaded', function() {
        const simpanButton = document.getElementById('simpan-button');
        const preorderId = @json($preorder->id);

        simpanButton.addEventListener('click', function() {
            const rows = document.querySelectorAll('#details-table tbody tr');
            const data = [];

            rows.forEach(row => {
                // Use class selectors for cells if ids are not unique
                const kodeElement = row.querySelector('.data-kode');
                const orderElement = row.querySelector('.order-input');

                const kode = kodeElement ? kodeElement.textContent.trim() : '';
                const order = orderElement ? orderElement.value.trim() : '';

                if (kode && order) {
                    data.push({
                        kode: kode,
                        order: order,
                    });
                }
            });

            fetch('{{ route('daftar-po.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        id: preorderId,
                        data: data
                    })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        var redirectUrl = @json(route('daftar-po.edit', enkrip($preorder->id)));
                        window.location.href = redirectUrl;
                    } else {
                        alert(`Validation Errors:\n${result.errors.join('\n')}`);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>
