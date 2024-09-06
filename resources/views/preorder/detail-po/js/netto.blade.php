<script>
    function updateNettoValues() {
        // Ambil nilai diskon
        const discountInput = document.getElementById('diskon4-input');
        const discountValue = parseFloat(discountInput.value) || 0;

        // Ambil semua elemen input harga
        const priceInputs = document.querySelectorAll('.price-input');
        priceInputs.forEach(input => {
            input.addEventListener('input', function() {
                const iteration = this.id.split('-').pop();
                const nettoElement = document.getElementById('netto-' + iteration);

                if (nettoElement) {
                    // Ambil nilai input dan format menjadi angka
                    const price = parseFloat(this.value.replace(/[^0-9.-]+/g, ''));
                    if (!isNaN(price)) {
                        nettoElement.setAttribute('data-initial-price', price);
                        updateNettoForAll();
                    } else {
                        nettoElement.textContent = '0';
                    }
                }
            });
        });

        // Tambahkan event listener untuk diskon
        discountInput.addEventListener('input', function() {
            updateNettoForAll();
        });

        // Tambahkan event listener untuk checkbox
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateNettoForAll();
            });
        });

        // Tambahkan event listener untuk order input
        document.querySelectorAll('.order-input').forEach(orderInput => {
            orderInput.addEventListener('input', function() {
                updateFieldTotalForAll();
            });
        });
    }

    function updateNettoForAll() {
        // Ambil nilai diskon
        const discountInput = document.getElementById('diskon4-input');
        const discountValue = parseFloat(discountInput.value) || 0;
        // const discount2Input = document.getElementById('diskon5-input');
        // const discount2Value = parseFloat(discount2Input.value) || 0;
        // const discount2Input = document.getElementById('diskon6-input');
        // const discount2Value = parseFloat(discount2Input.value) || 0;

        // Update semua elemen netto
        document.querySelectorAll('.netto').forEach(nettoElement => {
            const index = nettoElement.id.split('-')[1];
            const priceInput = document.getElementById('price-input-' + index);
            const checkbox = document.getElementById('checkbox-' + index);

            let initialPrice = parseFloat(priceInput.value.replace(/[^0-9.-]+/g, '')) || 0;

            // Cek status checkbox dan diskon
            if (checkbox && checkbox.checked) {
                if (discountValue >= 1 && discountValue <= 99) {
                    // Diskon persentase
                    initialPrice -= (initialPrice * discountValue) / 100;
                } else if (discountValue > 99) {
                    // Diskon flat
                    initialPrice -= discountValue;
                }
            }

            // Perbarui nilai netto
            nettoElement.textContent = new Intl.NumberFormat().format(initialPrice);
        });

        // Update field-total after netto values are updated
        updateFieldTotalForAll();
    }

    function updateFieldTotalForAll() {
        document.querySelectorAll('.netto').forEach(nettoElement => {
            const index = nettoElement.id.split('-')[1];
            const orderInput = document.getElementById('order-input-' + index);

            nettoValue = nettoElement.textContent.replace(/[.,]/g, '');
            let orderValue = parseFloat(orderInput.value) || 0;
            // Hitung field-total
            let fieldTotal = nettoValue * orderValue;
            // console.log(nettoValue,orderValue,fieldTotal)

            // Update nilai field-total
            const fieldTotalElement = document.getElementById('field-total-' + index);
            if (fieldTotalElement.textContent !== '0') {
                fieldTotalElement.textContent = new Intl.NumberFormat().format(fieldTotal);
            }
        });
    }

    // Inisialisasi fungsi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        updateNettoValues();
    });
</script>
