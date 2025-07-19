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

        searchInputUnit.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                // Prevent form submission or other default behavior
                event.preventDefault();
                event.stopPropagation();

                // Check if the dropdown is visible
                const isVisible = selectItemsUnit.style.display === 'block';
                if (isVisible) {
                    // Find the first visible item
                    const visibleItems = Array.from(selectItemsUnit.getElementsByTagName('div'))
                        .filter(item => item.style.display !== 'none');

                    // If there are visible items, select the first one
                    if (visibleItems.length > 0) {
                        const firstItem = visibleItems[0];
                        // Populate input fields with data from the first item
                        searchInputUnit.value = firstItem.dataset.value;
                        namaUnit.value = firstItem.dataset.nama;
                        closeAllSelect(); // Close the dropdown after selection
                    }
                } else {
                    // If the dropdown is not open, you may want to trigger the filter function
                    event.stopPropagation(); // Prevent the click from propagating to the document
                    const isVisible = selectItemsUnit.style.display === 'block';
                    closeAllSelect(); // Close any other open dropdowns
                    selectItemsUnit.style.display = isVisible ? 'none' : 'block'; // Toggle visibility
                }
            }
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

        searchInputDepartemen.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                // Prevent form submission or other default behavior
                event.preventDefault();
                event.stopPropagation();

                // Check if the dropdown is visible
                const isVisible = selectItemsDepartemen.style.display === 'block';
                if (isVisible) {
                    // Find the first visible item
                    const visibleItems = Array.from(selectItemsDepartemen.getElementsByTagName('div'))
                        .filter(item => item.style.display !== 'none');

                    // If there are visible items, select the first one
                    if (visibleItems.length > 0) {
                        const firstItem = visibleItems[0];
                        // Populate input fields with data from the first item
                        searchInputDepartemen.value = firstItem.dataset.value;
                        namaDepartemen.value = firstItem.dataset.nama;
                        closeAllSelect(); // Close the dropdown after selection
                    }
                } else {
                    // If the dropdown is not open, you may want to trigger the filter function
                    event.stopPropagation(); // Prevent the click from propagating to the document
                    const isVisible = selectItemsDepartemen.style.display === 'block';
                    closeAllSelect(); // Close any other open dropdowns
                    selectItemsDepartemen.style.display = isVisible ? 'none' : 'block'; // Toggle visibility
                }
            }
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

        // Handle Enter key press
        searchInputSupplier.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                // Prevent form submission or other default behavior
                event.preventDefault();
                event.stopPropagation();

                // Check if the dropdown is visible
                const isVisible = selectItemsSupplier.style.display === 'block';
                if (isVisible) {
                    // Find the first visible item
                    const visibleItems = Array.from(selectItemsSupplier.getElementsByTagName('div'))
                        .filter(item => item.style.display !== 'none');

                    // If there are visible items, select the first one
                    if (visibleItems.length > 0) {
                        const firstItem = visibleItems[0];
                        // Populate input fields with data from the first item
                        searchInputSupplier.value = firstItem.dataset.kode;
                        namaSupplier.value = firstItem.dataset.nama;
                        closeAllSelect(); // Close the dropdown after selection
                    }
                } else {
                    // If the dropdown is not open, you may want to trigger the filter function
                    event.stopPropagation(); // Prevent the click from propagating to the document
                    const isVisible = selectItemsSupplier.style.display === 'block';
                    closeAllSelect(); // Close any other open dropdowns
                    selectItemsSupplier.style.display = isVisible ? 'none' : 'block'; // Toggle visibility
                }
            }
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
        const upperCaseValue = inputField.value.toUpperCase();

        namaBarang.textContent = `/${upperCaseValue}`;
        hargaPokok2.textContent = `HARGA POKOK/ ${upperCaseValue}`;
        hargaPokok.textContent = `HARGA POKOK/ ${upperCaseValue}`;
        stokAwal.textContent = `STOK AWAL/ ${upperCaseValue}`;
        hargaJual.textContent = `HARGA JUAL/ ${upperCaseValue}`;
        unitJual.value = `${upperCaseValue}`;
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
    
    // Function to calculate profit percentage
    function calculateProfit(hargaJual, hargaPokok) {
        const jual = parseFloat(hargaJual) || 0;
        const pokok = parseFloat(hargaPokok) || 0;

        if (jual <= 0 || pokok <= 0) return 0;

        const profit = ((jual - pokok) / pokok) * 100;
        return profit; // hasil number
    }

    function calculateHargaJualFromProfit(profitPercentage, hargaPokok) {
        const pokok = parseFloat(hargaPokok) || 0;
        const profitDecimal = profitPercentage / 100;
        const hargaJual = pokok * (1 + profitDecimal);
        return hargaJual; // hasil number
    }

    function calculateProfitFromHargaJual(hargaJual, hargaPokok) {
        const jual = parseFloat(hargaJual) || 0;
        const pokok = parseFloat(hargaPokok) || 0;
        if (pokok === 0) return 0;
        const profitPercentage = ((jual - pokok) / pokok) * 100;
        return profitPercentage; // hasil number
    }


// Fungsi untuk memperbarui semua nilai berdasarkan input
function updateValues() {
    const hargaJualValue = parseFloat(hargaJualProfit.value.replace(/[^0-9]/g, '')) || 0;
    const hargaPokokValue = parseFloat(hargaPokokProfit.value.replace(/[^0-9]/g, '')) || 0;

    // Hitung dan tampilkan persentase profit
    const profitPercentage = calculateProfitFromHargaJual(hargaJualValue, hargaPokokValue);
    profitField.value = profitPercentage;

    // Perbarui field lainnya jika perlu
    inputField2.value = hargaPokokValue;
}

// Fungsi untuk menangani input perubahan angka
function handleInput(event) {
    const field = event.target;
    field.value = field.value;

    setTimeout(() => {
        updateValues();
    }, 0);
}

// Fungsi untuk menangani perubahan pada input profit
function handleProfitInput() {
    let profitPercentage = parseFloat(profitField.value.replace(/[^0-9.]/g, '')) || 0;
    profitPercentage = profitPercentage % 1 === 0 ? Math.floor(profitPercentage) : profitPercentage;

    const hargaPokokValue = parseFloat(hargaPokokProfit.value.replace(/[^0-9]/g, '')) || 0;
    const hargaJual = calculateHargaJualFromProfit(profitPercentage, hargaPokokValue);

    hargaJualProfit.value = hargaJual;
    updateValues();
}


    // Event listener untuk menangani keydown pada input profit
    let firstInputProfit = true;
    let originalProfitValue = profitField.value;
    // Saat input mendapatkan fokus
    profitField.addEventListener('focus', function() {
        if (firstInputProfit) {
            originalProfitValue = profitField.value; // Simpan nilai sebelum dihapus
            profitField.value = ''; // Kosongkan input saat pertama kali fokus
            firstInputProfit = false;
        }
    });

    // Saat input kehilangan fokus
    profitField.addEventListener('blur', function() {
        // Jika input tetap kosong setelah kehilangan fokus, kembalikan ke nilai semula
        if (profitField.value.trim() === '') {
            profitField.value = originalProfitValue;
        } else {
            handleProfitInput(); // Proses jika user mengisi nilai
        }
    });

    // Event listener untuk menangani keydown pada input profit
    profitField.addEventListener('keydown', function(event) {
        // Mengecek apakah yang ditekan adalah angka atau tanda khusus seperti backspace
        if (event.key >= '0' && event.key <= '9') {
            if (firstInputProfit) {
                // Reset nilai saat pertama kali angka dimasukkan
                profitField.value = '';  // Reset ke kosong
                firstInputProfit = false;     // Tandai bahwa reset sudah terjadi
            }
        }
    });

    // Jika ingin mengatur ulang reset jika sudah selesai menginput (misalnya jika input diubah lagi):
    profitField.addEventListener('blur', function() {
        firstInputProfit = true;  // Reset flag saat input kehilangan fokus
    });

    // Event listener untuk menangani keydown pada input profit
    let firstInputPokok = true;
    hargaPokokProfit.addEventListener('focus', function() {
        // Reset nilai saat input mendapatkan fokus (klik atau tab)
        if (firstInputPokok) {
            hargaPokokProfit.value = '';  // Reset ke kosong
            profitField.value = '';
            hargaJualProfit.value = '';
            firstInputPokok = false;     // Tandai bahwa reset sudah terjadi
        }
    });

    // Event listener untuk menangani keydown pada input profit
    hargaPokokProfit.addEventListener('keydown', function(event) {
        // Mengecek apakah yang ditekan adalah angka atau tanda khusus seperti backspace
        if (event.key >= '0' && event.key <= '9') {
            if (firstInputPokok) {
                // Reset nilai saat pertama kali angka dimasukkan
                hargaPokokProfit.value = '';  // Reset ke kosong
                profitField.value = '';
                hargaJualProfit.value = '';
                firstInputPokok = false;     // Tandai bahwa reset sudah terjadi
            }
        }
    });

    // Jika ingin mengatur ulang reset jika sudah selesai menginput (misalnya jika input diubah lagi):
    hargaPokokProfit.addEventListener('blur', function() {
        firstInputPokok = true;  // Reset flag saat input kehilangan fokus
    });

    // Event listener untuk mengupdate nilai setiap kali ada perubahan input
    profitField.addEventListener('input', function() {
        // Pastikan hanya angka yang dimasukkan dan menghapus titik desimal
        const inputValue = profitField.value; // Hapus semua karakter selain angka

        // Set nilai ke input profit yang benar
        profitField.value = inputValue;

        // Panggil handleProfitInput untuk memperbarui harga jual
        handleProfitInput();
    });

    // Add event listeners
    hargaJualProfit.addEventListener('input', handleInput);
    hargaPokokProfit.addEventListener('input', handleInput);
    inputField2.addEventListener('input', handleInput);

    // Initial setup
    updateValues();
</script>

