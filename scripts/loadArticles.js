document.addEventListener('DOMContentLoaded', function() {
    fetch('../controllers/loadArticles.controller.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('articles-container').innerHTML = data;

            // Usar event delegation para manejar clics en los botones
            document.getElementById('articles-container').addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('toggle-visibility')) {
                    const button = event.target;
                    const articleId = button.getAttribute('data-id');
                    const currentVisibility = button.getAttribute('data-visible') === '1';
                    fetch('../controllers/toggleVisibility.controller.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: articleId, visible: !currentVisibility })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            button.textContent = data.visible ? 'Marcar como invisible' : 'Marcar como visible';
                            button.setAttribute('data-visible', data.visible ? '1' : '0');
                        } else {
                            alert('Error al actualizar la visibilidad del artículo.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        })
        .catch(error => console.error('Error loading articles:', error));
});