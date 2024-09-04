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

            index++;
            
            const newRow = document.createElement('tr');
            newRow.classList.add('fs-need');
            
            newRow.innerHTML = `
                <td>${index}</td>
                <td class="text-center">
                    <div class="select-container">
                        <input class="form-check-input select-checkbox" type="checkbox" id="checkbox-${index}" onchange="handleCheckboxChange(this)">
                        <button type="button" id="edit-save-${index}" style="display:none;" onclick="handleSaveClick(this)">Save</button>
                    </div>
                </td>
                <td class="text-center data-kode" id="data-kode"></td>
                <td colspan="2">
                    <select id="products-${index}" class="product-select" style="width: 270px;" onchange="handleSelectChange(event)">
                        <option value="">---Select Product---</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-kode="{{ $product->kode }}" data-isi="{{ $product->unit_jual }}" data-isi2="{{ $product->unit_jual }}"
                                data-jual="{{ $product->harga_jual }}">{{ $product->nama }}/{{ $product->unit_jual }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="text-end" hidden id="data-isi"></td>
                <td class="text-end" id="data-isi2"></td>
                <td class="text-end" id="data-jual"></td>
                <td class="text-end"><input type="number" size="1" class="order-input" min="1" step="1" style="width: 50px;"></td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
            `;
            
            tableBody.appendChild(newRow);

            // Initialize Select2 on the newly added select element
            $(`#products-${index}`).select2({
                placeholder: '---Select Product---',
                allowClear: true
            });

            disableAllCheckboxes();
        });
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

            fetch('{{ route("daftar-po.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: preorderId, data: data })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    var redirectUrl = @json(route('daftar-po.edit', $preorder->id));
                    window.location.href = redirectUrl;
                } else {
                    alert(`Validation Errors:\n${result.errors.join('\n')}`);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>