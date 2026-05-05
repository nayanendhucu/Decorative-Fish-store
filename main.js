document.addEventListener('DOMContentLoaded', function () {
    const removeLinks = document.querySelectorAll('.trash-link');
    removeLinks.forEach(link => {
        link.addEventListener('click', function () {
            return confirm('Remove this item from your cart?');
        });
    });
});
