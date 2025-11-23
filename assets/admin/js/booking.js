document.addEventListener('DOMContentLoaded', function() {
    const tourSelect = document.getElementById('booking-tour-id');
    const customerSelect = document.getElementById('booking-customer-id');
    const totalPriceInput = document.getElementById('booking-total-price');
    const companionList = document.getElementById('booking-companion-list');
    const addCompanionBtn = document.getElementById('booking-add-companion-btn');
    const companionTemplate = document.getElementById('companion-template');

    // Update total price when tour changes
    tourSelect.addEventListener('change', function() {
        if (this.value) {
            const price = this.options[this.selectedIndex].dataset.price || 0;
            totalPriceInput.value = parseInt(price) || 0;
            document.getElementById('booking-summary-tour').textContent = this.options[this.selectedIndex].text;
        } else {
            totalPriceInput.value = 0;
            document.getElementById('booking-summary-tour').textContent = '--';
        }
        updateSummary();
    });

    // Update customer name in summary
    customerSelect.addEventListener('change', function() {
        if (this.value) {
            document.getElementById('booking-summary-customer').textContent = this.options[this.selectedIndex].text.split('(')[0].trim();
        } else {
            document.getElementById('booking-summary-customer').textContent = '--';
        }
        updateSummary();
    });

    // Add companion button
    addCompanionBtn.addEventListener('click', function(e) {
        e.preventDefault();
        addCompanionItem();
    });

    function addCompanionItem(data = null) {
        const clone = companionTemplate.content.cloneNode(true);
        
        if (data) {
            clone.querySelector('[name="companion_name[]"]').value = data.name || '';
            clone.querySelector('[name="companion_gender[]"]').value = data.gender || '';
            clone.querySelector('[name="companion_birth_date[]"]').value = data.birth_date || '';
            clone.querySelector('[name="companion_phone[]"]').value = data.phone || '';
            clone.querySelector('[name="companion_id_card[]"]').value = data.id_card || '';
            clone.querySelector('[name="companion_room_type[]"]').value = data.room_type || '';
            clone.querySelector('[name="companion_special_request[]"]').value = data.special_request || '';
        }

        const removeBtn = clone.querySelector('.remove-companion');
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            this.closest('.companion-item').remove();
            updateSummary();
        });

        companionList.appendChild(clone);
        updateSummary();
    }

    function updateSummary() {
        const companionCount = document.querySelectorAll('.companion-item').length;
        document.getElementById('booking-summary-companion-count').textContent = companionCount;
        document.getElementById('booking-summary-price').textContent = 
            (parseInt(totalPriceInput.value) || 0).toLocaleString('vi-VN');
    }

    // Set today's date as default booking date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('booking-booking-date').value = today;

    // Initialize summary
    updateSummary();
});