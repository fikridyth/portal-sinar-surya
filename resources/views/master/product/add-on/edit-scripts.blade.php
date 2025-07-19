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
