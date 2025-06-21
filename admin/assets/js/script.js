document.querySelector('form').addEventListener('submit', (e) => {
    if (document.getElementById('filter_type').value === 'custom') {
        const start = new Date(document.getElementById('start_date').value);
        const end = new Date(document.getElementById('end_date').value);
        if (start && end && start > end) {
            e.preventDefault();
            alert('End date must be after start date.');
        }
    }
});