// for canceling order
document.querySelectorAll('.cancel-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('cancelOrderId').value = btn.dataset.orderId;
                document.getElementById('cancelModal').style.display = 'flex';
            });
        });
        function closeModal() {
            document.getElementById('cancelModal').style.display = 'none';
        }