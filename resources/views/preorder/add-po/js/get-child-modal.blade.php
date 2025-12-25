<script>
    const childProducts = @json($getChildProducts);
    let selectedToParent = [];
    let currentParentRow = null;
    $(document).ready(function () {
        $('.select-product-modal').on('change', function () {
            let $currentCheckbox = $(this);
            let kodeDipilih = $currentCheckbox.data('kode');

            if (this.checked) {
                // Save row
                currentParentRow = $currentCheckbox.closest('tr');

                // 1️⃣ Uncheck checkbox lain
                $('.select-product-modal').not(this).prop('checked', false);

                // 2️⃣ Reset table selected
                $('#selected-table tbody').html(`
                    <tr id="empty-selected">
                        <td colspan="8" class="text-center text-muted">
                            Belum ada barang dipilih
                        </td>
                    </tr>
                `);

                // 3️⃣ Filter child product dari JSON
                let filteredChild = Object.values(childProducts).filter(item =>
                    item.kode_sumber == kodeDipilih
                );

                // 4️⃣ Render ke table
                if (filteredChild.length > 0) {

                    $('#empty-selected').remove();

                    filteredChild.forEach(item => {
                        $('#selected-table tbody').append(`
                            <tr id="selected-${item.kode}">
                                <td>${item.nama}/${item.unit_jual}</td>
                                <td class="text-end">${item.isi}</td>
                                <td class="text-end">0</td>
                                <td class="text-end">0</td>
                                <td class="text-end">${item.stok}</td>
                                <td class="text-end">0</td>
                                <td class="text-end">0</td>
                                <td class="text-center">Order 
                                    <input 
                                        type="checkbox"
                                        class="select-child-to-parent"
                                        data-kode="${item.kode}" 
                                        data-nama="${item.nama}"
                                        data-unit="${item.unit_jual}"
                                        data-stok="${item.stok}"
                                        data-isi="${item.isi}"
                                        data-harga="${item.harga_pokok ?? 0}"
                                    >
                                </td>
                            </tr>
                        `);
                    });

                }

            } else {
                currentParentRow = null;

                // 👉 JIKA UNCHECK
                $('#selected-table tbody').html(`
                    <tr id="empty-selected">
                        <td colspan="8" class="text-center text-muted">
                            Belum ada barang dipilih
                        </td>
                    </tr>
                `);
            }
        });

        // tombol hapus manual
        $(document).on('click', '.remove-selected', function () {
            let kode = $(this).data('kode');
            $('#selected-' + kode).remove();

            if ($('#selected-table tbody tr').length === 0) {
                $('#selected-table tbody').html(`
                    <tr id="empty-selected">
                        <td colspan="8" class="text-center text-muted">
                            Belum ada barang dipilih
                        </td>
                    </tr>
                `);
            }
        });

        $(document).on('change', '.select-child-to-parent', function () {
            let checkbox = $(this);

            let childData = {
                kode: checkbox.data('kode'),
                nama: checkbox.data('nama'),
                unit_jual: checkbox.data('unit'),
                stok: checkbox.data('stok'),
                isi: checkbox.data('isi'),
                harga: checkbox.data('harga'),
            };

            if (!currentParentRow) {
                alert('Parent belum dipilih');
                checkbox.prop('checked', false);
                return;
            }

            if (this.checked) {
                // ➕ PUSH ke array
                selectedToParent.push(childData);
                let key = childData.kode;

                // ➕ insert row child tepat setelah parent
                currentParentRow.after(`
                    <tr class="child-row" data-kode="${childData.kode}">
                        <td style="display:none">
                            <input type="hidden" name="name[${key}]" value="${childData.nama}/${childData.unit_jual}/${childData.stok}/${childData.harga}">
                            <input type="hidden" name="stock[${key}]" value="${childData.stok}">
                            <input type="hidden" name="harga[${key}]" value="${childData.harga}">
                        </td>
                        <td class="ps-3">↳ ${childData.nama}/${childData.unit_jual}</td>
                        <td class="text-end">${childData.isi}</td>
                        <td class="text-end">0</td>
                        <td class="text-end">0.00</td>
                        <td class="text-end">${childData.stok}</td>
                        <td class="text-end">0.00</td>
                        <td class="text-center">
                            <input type="text"
                                class="order-child"
                                size="3"
                                value="0"
                                name="orderPo[${key}]"
                                onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                            >
                        </td>
                        <td class="text-end price-child">${number_format(childData.harga)}</td>
                        <td class="text-end total-child">0</td>
                        <td class="totally total-child-hidden" hidden>0</td>
                        <td class="text-center">
                            <input type="checkbox" disabled>
                        </td>
                    </tr>
                `);
            } else {
                // ➖ REMOVE dari array
                selectedToParent = selectedToParent.filter(item =>
                    item.kode !== childData.kode
                );
            }
        });

        $(document).on('input', '.order-child', function () {
            updateTotalChild(this);
        });

        function updateTotalChild(input) {
            let row = input.closest('tr');

            let qty = parseFloat(input.value) || 0;
            if (qty < 0) qty = Math.abs(qty);
            input.value = qty;

            let price = parseFloat(
                row.querySelector('.price-child').textContent.replace(/,/g, '')
            ) || 0;

            let total = qty * price;

            row.querySelector('.total-child').textContent = total.toLocaleString();
            row.querySelector('.total-child-hidden').textContent = total;

            updateTotalPrice();
        }
    });
</script>
