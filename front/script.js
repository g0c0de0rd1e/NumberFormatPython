document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('installButton');
    const result = document.getElementById('result');

    button.addEventListener('click', () => {
        fetch('/helper/install.php')
            .then(response => response.text())
            .then(data => {
                result.textContent = data;
            })
            .catch(error => {
                result.textContent = 'Ошибка: ' + error.message;
            });
    });
});
