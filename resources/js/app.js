import './bootstrap';
import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.bootstrap5.css';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    // 1. Flatpickr for Date & Time
    const dateInputs = document.querySelectorAll('input[type="date"]');
    if (dateInputs.length > 0) {
        flatpickr(dateInputs, {
            dateFormat: "Y-m-d",
            disableMobile: "true",
            altInput: true,
            altFormat: "d F Y",
        });
    }

    const timeInputs = document.querySelectorAll('input[type="time"]');
    if (timeInputs.length > 0) {
        flatpickr(timeInputs, {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            disableMobile: "true",
        });
    }

    // 2. TomSelect for all form-select
    document.querySelectorAll('.form-select').forEach((el) => {
        new TomSelect(el, {
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: "Pilih opsi...",
            plugins: ['clear_button']
        });
    });

    // 3. Staggered Card Animation (Fade Up)
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${(index + 1) * 0.1}s`;
    });

    // 4. Loading State on Form Submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            // Check if form is valid before showing spinner
            if (this.checkValidity && !this.checkValidity()) {
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                if (!submitBtn.classList.contains('is-loading')) {
                    submitBtn.classList.add('is-loading');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.setAttribute('data-original-text', originalText);
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memproses...';
                    
                    // Note: Don't disable immediately or the form might not submit the button's value if needed
                    setTimeout(() => {
                        submitBtn.disabled = true;
                    }, 10);
                }
            }
        });
    });
});
