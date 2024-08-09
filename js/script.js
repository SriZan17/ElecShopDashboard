function handleTransaction(productId, transactionType) {
    let quantity = prompt(`Enter quantity to ${transactionType}:`);

    if (quantity !== null && quantity > 0 && Number.isInteger(Number(quantity))) {
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        formData.append('transaction_type', transactionType);

        fetch('transaction.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    } else {
        alert("Please enter a valid quantity.");
    }
}

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();

        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});
