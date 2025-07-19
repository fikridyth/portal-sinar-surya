{{-- Supplier 1 --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const supplier = document.getElementById('supplier_1');
        const namaSupplier = document.getElementById('nama_supplier_1');
        const selectItemsSupplier = document.getElementById('select-items-supplier_1');
        const searchInputSupplier = document.querySelector('.search-input-supplier_1');

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

                    $.ajax({
                        url: '/preorder-get-supplier-data',
                        method: 'GET',
                        data: {
                            nama: namaSupplier.value
                        },
                        success: function(response) {
                            $('#supplierName11').text(response.nama);
                            $('#supplierAddress11').text(response.alamat1);
                            $('#supplierAddress12').text(response.alamat2);

                            $('#targetRow1').removeAttr('hidden');
                            $('#targetRow4').attr('hidden', 'hidden');
                            // $('#dataSupplier2').removeAttr('disabled');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred:", error);
                            $('#targetRow1').attr('hidden', 'hidden');
                            $('#targetRow4').removeAttr('hidden');
                            // $('#targetRow2').attr('hidden', 'hidden');
                            // $('#targetRow3').attr('hidden', 'hidden');
                            // $('#dataSupplier2').attr('disabled', 'disabled');
                            // $('#dataSupplier3').attr('disabled', 'disabled');
                            // $('#dataSupplier2').val('');
                            // $('#dataSupplier3').val('');
                        }
                    });
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

        searchInputSupplier.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                event.stopPropagation();

                const isVisible = selectItemsSupplier.style.display === 'block';
                if (isVisible) {
                    const visibleItems = Array.from(selectItemsSupplier.getElementsByTagName('div'))
                        .filter(item => item.style.display !== 'none');

                    if (visibleItems.length > 0) {
                        const firstItem = visibleItems[0];

                        // Simulate click on the first visible item
                        searchInputSupplier.value = firstItem.dataset.kode;
                        namaSupplier.value = firstItem.dataset.nama;
                        closeAllSelect();

                        $.ajax({
                            url: '/preorder-get-supplier-data',
                            method: 'GET',
                            data: { nama: namaSupplier.value },
                            success: function(response) {
                                $('#supplierName11').text(response.nama);
                                $('#supplierAddress11').text(response.alamat1);
                                $('#supplierAddress12').text(response.alamat2);
                                $('#targetRow1').removeAttr('hidden');
                                $('#targetRow4').attr('hidden', 'hidden');
                            },
                            error: function(xhr, status, error) {
                                console.error("An error occurred:", error);
                                $('#targetRow1').attr('hidden', 'hidden');
                                $('#targetRow4').removeAttr('hidden');
                            }
                        });
                    }
                } else {
                    filterFunction();
                    selectItemsSupplier.style.display = 'block';
                }
            }
        });

        closeAllSelect();
    });
</script>

{{-- Supplier 2 --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const supplier = document.getElementById('supplier_2');
        const namaSupplier = document.getElementById('nama_supplier_2');
        const selectItemsSupplier = document.getElementById('select-items-supplier_2');
        const searchInputSupplier = document.querySelector('.search-input-supplier_2');

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

                    $.ajax({
                        url: '/preorder-get-supplier-data',
                        method: 'GET',
                        data: {
                            nama: namaSupplier.value
                        },
                        success: function(response) {
                            $('#supplierName21').text(response.nama);
                            $('#supplierAddress21').text(response.alamat1);
                            $('#supplierAddress22').text(response.alamat2);
            
                            $('#targetRow2').removeAttr('hidden');
                            $('#targetRow4').attr('hidden', 'hidden');
                            $('#dataSupplier3').removeAttr('disabled');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred:", error);
                            $('#targetRow2').attr('hidden', 'hidden');
                            $('#targetRow3').attr('hidden', 'hidden');
                            $('#dataSupplier3').attr('disabled', 'disabled');
                            $('#dataSupplier3').val('');
                        }
                    });
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

        searchInputSupplier.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                event.stopPropagation();

                const isVisible = selectItemsSupplier.style.display === 'block';
                if (isVisible) {
                    const visibleItems = Array.from(selectItemsSupplier.getElementsByTagName('div'))
                        .filter(item => item.style.display !== 'none');

                    if (visibleItems.length > 0) {
                        const firstItem = visibleItems[0];

                        // Simulate click on the first visible item
                        searchInputSupplier.value = firstItem.dataset.kode;
                        namaSupplier.value = firstItem.dataset.nama;
                        closeAllSelect();

                        $.ajax({
                            url: '/preorder-get-supplier-data',
                            method: 'GET',
                            data: { nama: namaSupplier.value },
                            success: function(response) {
                                $('#supplierName21').text(response.nama);
                                $('#supplierAddress21').text(response.alamat1);
                                $('#supplierAddress22').text(response.alamat2);
                                $('#targetRow2').removeAttr('hidden');
                                $('#targetRow4').attr('hidden', 'hidden');
                                $('#dataSupplier3').removeAttr('disabled');
                            },
                            error: function(xhr, status, error) {
                                console.error("An error occurred:", error);
                                $('#targetRow2').attr('hidden', 'hidden');
                                $('#targetRow3').attr('hidden', 'hidden');
                                $('#dataSupplier3').attr('disabled', 'disabled');
                            }
                        });
                    }
                } else {
                    filterFunction();
                    selectItemsSupplier.style.display = 'block';
                }
            }
        });

        closeAllSelect();
    });
</script>

{{-- Supplier 3 --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const supplier = document.getElementById('supplier_3');
        const namaSupplier = document.getElementById('nama_supplier_3');
        const selectItemsSupplier = document.getElementById('select-items-supplier_3');
        const searchInputSupplier = document.querySelector('.search-input-supplier_3');

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

                    $.ajax({
                        url: '/preorder-get-supplier-data',
                        method: 'GET',
                        data: {
                            nama: namaSupplier.value
                        },
                        success: function(response) {
                            $('#supplierName31').text(response.nama);
                            $('#supplierAddress31').text(response.alamat1);
                            $('#supplierAddress32').text(response.alamat2);
            
                            $('#targetRow3').removeAttr('hidden');
                            $('#targetRow4').attr('hidden', 'hidden');
                        },
                        error: function(xhr, status, error) {
                            console.error("An error occurred:", error);
                            $('#targetRow3').attr('hidden', 'hidden');
                        }
                    });
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

        searchInputSupplier.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                event.stopPropagation();

                const isVisible = selectItemsSupplier.style.display === 'block';
                if (isVisible) {
                    const visibleItems = Array.from(selectItemsSupplier.getElementsByTagName('div'))
                        .filter(item => item.style.display !== 'none');

                    if (visibleItems.length > 0) {
                        const firstItem = visibleItems[0];

                        // Simulate click on the first visible item
                        searchInputSupplier.value = firstItem.dataset.kode;
                        namaSupplier.value = firstItem.dataset.nama;
                        closeAllSelect();

                        $.ajax({
                            url: '/preorder-get-supplier-data',
                            method: 'GET',
                            data: { nama: namaSupplier.value },
                            success: function(response) {
                                $('#supplierName31').text(response.nama);
                                $('#supplierAddress31').text(response.alamat1);
                                $('#supplierAddress32').text(response.alamat2);
                
                                $('#targetRow3').removeAttr('hidden');
                                $('#targetRow4').attr('hidden', 'hidden');
                            },
                            error: function(xhr, status, error) {
                                console.error("An error occurred:", error);
                                $('#targetRow3').attr('hidden', 'hidden');
                            }
                        });
                    }
                } else {
                    filterFunction();
                    selectItemsSupplier.style.display = 'block';
                }
            }
        });

        closeAllSelect();
    });
</script>
