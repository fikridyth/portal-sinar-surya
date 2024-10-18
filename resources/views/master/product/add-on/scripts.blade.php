{{-- Unit --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const unit = document.getElementById('unit');
        const namaUnit = document.getElementById('nama_unit');
        const selectItemsUnit = document.getElementById('select-items-unit');
        const searchInputUnit = document.querySelector('.search-input-unit');
        
        // Populate options from the original select element
        Array.from(unit.options).forEach(option => {
            if (option.value) {
                const div = document.createElement('div');
                div.textContent = option.textContent;
                div.dataset.value = option.value;
                div.dataset.nama = option.getAttribute('data-nama');
                div.onclick = function() {
                    searchInputUnit.value = option.value;
                    namaUnit.value = this.dataset.nama;
                    closeAllSelect();
                }
                selectItemsUnit.appendChild(div);
            }
        });

        function filterFunction() {
            const input = searchInputUnit.value.toUpperCase();
            const items = selectItemsUnit.getElementsByTagName('div');
            Array.from(items).forEach(item => {
                const text = item.textContent || item.innerText;
                item.style.display = text.toUpperCase().includes(input) ? 'block' : 'none';
            });
        }

        function closeAllSelect() {
            selectItemsUnit.style.display = 'none';
        }

        // Filter options when typing in the search input
        searchInputUnit.addEventListener('keyup', filterFunction);

        // Toggle dropdown visibility on input click
        searchInputUnit.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent the click from propagating to the document
            const isVisible = selectItemsUnit.style.display === 'block';
            closeAllSelect(); // Close any other open dropdowns
            selectItemsUnit.style.display = isVisible ? 'none' : 'block'; // Toggle visibility
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            closeAllSelect();
        });

        closeAllSelect();
    });
</script>

{{-- Departemen --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const departemen = document.getElementById('departemen');
        const namaDepartemen = document.getElementById('nama_departemen');
        const selectItemsDepartemen = document.getElementById('select-items-departemen');
        const searchInputDepartemen = document.querySelector('.search-input-departemen');

        // Populate options from the original select element
        Array.from(departemen.options).forEach(option => {
            if (option.value) {
                const div = document.createElement('div');
                div.textContent = option.textContent;
                div.dataset.value = option.value;
                div.dataset.nama = option.getAttribute('data-nama');
                div.onclick = function() {
                    searchInputDepartemen.value = option.value;
                    namaDepartemen.value = this.dataset.nama;
                    closeAllSelect();
                }
                selectItemsDepartemen.appendChild(div);
            }
        });

        function filterFunction() {
            const input = searchInputDepartemen.value.toUpperCase();
            const items = selectItemsDepartemen.getElementsByTagName('div');
            Array.from(items).forEach(item => {
                const text = item.textContent || item.innerText;
                item.style.display = text.toUpperCase().includes(input) ? 'block' : 'none';
            });
        }

        function closeAllSelect() {
            selectItemsDepartemen.style.display = 'none';
        }

        // Filter options when typing in the search input
        searchInputDepartemen.addEventListener('keyup', filterFunction);

        // Toggle dropdown visibility on input click
        searchInputDepartemen.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent the click from propagating to the document
            const isVisible = selectItemsDepartemen.style.display === 'block';
            closeAllSelect(); // Close any other open dropdowns
            selectItemsDepartemen.style.display = isVisible ? 'none' : 'block'; // Toggle visibility
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            closeAllSelect();
        });

        closeAllSelect();
    });
</script>

{{-- Supplier --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const supplier = document.getElementById('supplier');
        const namaSupplier = document.getElementById('nama_supplier');
        const selectItemsSupplier = document.getElementById('select-items-supplier');
        const searchInputSupplier = document.querySelector('.search-input-supplier');

        // Populate options from the original select element
        Array.from(supplier.options).forEach(option => {
            if (option.value) {
                const div = document.createElement('div');
                div.textContent = option.textContent;
                div.dataset.value = option.value;
                div.dataset.kode = option.getAttribute('data-kode');
                div.dataset.nama = option.getAttribute('data-nama');
                div.onclick = function() {
                    searchInputSupplier.value = this.dataset.kode;
                    namaSupplier.value = this.dataset.nama;
                    closeAllSelect();
                }
                selectItemsSupplier.appendChild(div);
            }
        });

        function filterFunction() {
            const input = searchInputSupplier.value.toUpperCase();
            const items = selectItemsSupplier.getElementsByTagName('div');
            Array.from(items).forEach(item => {
                const text = item.textContent || item.innerText;
                item.style.display = text.toUpperCase().includes(input) ? 'block' : 'none';
            });
        }

        function closeAllSelect() {
            selectItemsSupplier.style.display = 'none';
        }

        // Filter options when typing in the search input
        searchInputSupplier.addEventListener('keyup', filterFunction);

        // Toggle dropdown visibility on input click
        searchInputSupplier.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent the click from propagating to the document
            const isVisible = selectItemsSupplier.style.display === 'block';
            closeAllSelect(); // Close any other open dropdowns
            selectItemsSupplier.style.display = isVisible ? 'none' : 'block'; // Toggle visibility
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            closeAllSelect();
        });

        closeAllSelect();
    });
</script>

{{-- Trigger Value Unit Beli Ke Form Lain  --}}
<script>
    const inputField = document.getElementById('unit_beli');
    const namaBarang = document.getElementById('label_nama_barang');
    const hargaPokok2 = document.getElementById('label_harga_pokok_2');
    const hargaPokok = document.getElementById('label_harga_pokok');
    const stokAwal = document.getElementById('label_stok_awal');
    const hargaJual = document.getElementById('label_harga_jual');
    const unitJual = document.getElementById('unit_jual');
    
    // Function to update the label text
    function updateLabel() {
        const konversi = document.getElementById('konversi');
        const numericValue = inputField.value.replace(/\D/g, '');

        namaBarang.textContent = `/${inputField.value}`;
        hargaPokok2.textContent = `HARGA POKOK/ ${inputField.value}`;
        hargaPokok.textContent = `HARGA POKOK/ ${inputField.value}`;
        stokAwal.textContent = `STOK AWAL/ ${inputField.value}`;
        hargaJual.textContent = `HARGA JUAL/ ${inputField.value}`;
        unitJual.value = `${inputField.value}`;
        if (konversi) {
            konversi.value = `${numericValue}.00/${numericValue}.00`;
        }
    }

    // Set initial label text
    updateLabel();

    // Add event listener to update label when input changes
    inputField.addEventListener('input', updateLabel);
</script>

{{-- Update Harga Pokok, Jual, Profit, PPN --}}
<script>
    // Get elements
    const hargaJualProfit = document.getElementById('harga_jual');
    const profitField = document.getElementById('profit');
    const hargaPokokProfit = document.getElementById('harga_pokok');
    const inputField2 = document.getElementById('harga_pokok_2');

    // Function to format number as Rupiah
    function formatAsRupiah(value) {
        const numericValue = value === null || isNaN(Number(value)) ? 0 : Number(value);
        return `${numericValue.toLocaleString('id-ID')}`;
    }

    // Function to parse numeric value from Rupiah formatted string
    function parseNumericValue(value) {
        const numericValue = value.replace(/[^0-9]/g, '');
        return numericValue === '' ? 0 : Number(numericValue);
    }

    // Function to calculate profit percentage
    function calculateProfit(hargaJual, hargaPokok) {
        const jual = parseNumericValue(hargaJual);
        const pokok = parseNumericValue(hargaPokok);

        if (isNaN(jual) || jual <= 0 || isNaN(pokok) || pokok <= 0) return 0;

        const profit = ((jual - pokok) / pokok) * 100;
        return profit;
    }

    // Function to calculate harga_jual based on profit percentage
    function calculateHargaJualFromProfit(profitPercentage, hargaPokok) {
        const pokok = parseNumericValue(hargaPokok);
        const profitDecimal = profitPercentage / 100;
        return pokok * (1 + profitDecimal);
    }

    // Function to update all values based on inputs
    function updateValues() {
        const hargaJualValue = hargaJualProfit.value;
        const hargaPokokValue = hargaPokokProfit.value;

        // Calculate profit percentage
        const profitPercentage = calculateProfit(hargaJualValue, hargaPokokValue);
        profitField.value = `${profitPercentage.toFixed(2)}`;

        // Format and update fields
        hargaJualProfit.value = formatAsRupiah(parseNumericValue(hargaJualProfit.value));
        hargaPokokProfit.value = formatAsRupiah(parseNumericValue(hargaPokokProfit.value));
        inputField2.value = formatAsRupiah(parseNumericValue(hargaPokokProfit.value));
    }

    // Function to handle input changes
    function handleInput(event) {
        const field = event.target;
        field.value = parseNumericValue(field.value);

        // Delay the formatting update to ensure smooth typing experience
        setTimeout(() => {
            // Update all fields after a short delay
            updateValues();
        }, 0);
    }

    // Function to handle profit input change
    function handleProfitInput() {
        const profitPercentage = parseFloat(profitField.value) || 0;
        const hargaPokokValue = hargaPokokProfit.value;
        const hargaJual = calculateHargaJualFromProfit(profitPercentage, hargaPokokValue);
        hargaJualProfit.value = formatAsRupiah(hargaJual);
        updateValues(); // Ensure all fields are updated after setting harga_jual
    }

    // Add event listeners
    hargaJualProfit.addEventListener('input', handleInput);
    hargaPokokProfit.addEventListener('input', handleInput);
    inputField2.addEventListener('input', handleInput);
    profitField.addEventListener('input', handleProfitInput);

    // Initial setup
    updateValues();
</script>
