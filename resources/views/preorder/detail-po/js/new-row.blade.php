<script>
    function disableAllCheckboxes() {
        // Select all checkboxes with the class 'select-checkbox'
        const checkboxes = document.querySelectorAll('.select-checkbox');
        
        // Iterate over each checkbox and disable it
        checkboxes.forEach(checkbox => {
            checkbox.disabled = true;
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const tambahButton = document.getElementById('tambah-button');
        const tableBody = document.querySelector('#details-table tbody');
        const currentIndex = parseInt(document.getElementById('current-index').value, 10);

        let index = currentIndex; // Start index from the current index

        tambahButton.addEventListener('click', function() {
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
                <td class="text-center"></td>
                <td>New Name/Unit Jual</td>
                <td class="text-end">New Unit</td>
                <td class="text-end">0</td>
                <td class="text-end">0</td>
                <td class="text-end" id="order-view-text-${index}">0</td>
                <td class="text-end">
                    <div class="order-container">
                        <span class="order-text" id="order-text-${index}">0</span>
                        <input type="text" class="order-input" hidden disabled id="order-input-${index}" value="0" size="3">
                    </div>
                </td>
                <td class="text-end">
                    <div class="price-container">
                        <span class="price-text" id="price-text-${index}">0</span>
                        <input type="text" class="price-input" id="price-input-${index}" hidden disabled value="0" size="10">
                    </div>
                </td>
                <td class="text-end netto" id="netto-${index}">0</td>
                <td class="text-end field-total" id="field-total-${index}">0</td>
            `;
            
            tableBody.appendChild(newRow);
            disableAllCheckboxes();
        });
    });
</script>