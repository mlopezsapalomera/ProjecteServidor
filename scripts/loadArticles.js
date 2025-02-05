document.getElementById('fetch-articles').addEventListener('click', function() {
    fetch('../controllers/fetchData.controller.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error(data.message);
                return;
            }
            const container = document.getElementById('articles-container');
            let html = '<div class="pokemons-container">';
            data.data.forEach(pokemon => {
                html += `<div class="pokemon-card">
                            <img src="../img/${pokemon.imatge}" alt="${pokemon.title}">
                            <div class="pokemon-info">
                                <h3>${pokemon.title}</h3>
                                <p>${pokemon.content}</p>
                                <p>Força: ${pokemon.força}</p>
                                <p>Vida: ${pokemon.vida}</p>
                                <p>Daño: ${pokemon.dany}</p>
                                <p>Tipus: ${pokemon.tipus}</p>
                                <button class="toggle-visibility" data-id="${pokemon.id}" data-visible="${pokemon.visible ? '1' : '0'}">
                                    ${pokemon.visible ? 'Marcar como invisible' : 'Marcar como visible'}
                                </button>
                            </div>
                         </div>`;
            });
            html += '</div>';
            container.innerHTML = html;

            // Añadir event listeners para los botones de visibilidad
            document.querySelectorAll('.toggle-visibility').forEach(button => {
                button.addEventListener('click', function() {
                    const pokemonId = this.getAttribute('data-id');
                    const visible = this.getAttribute('data-visible') === '1' ? '0' : '1';
                    fetch(`../controllers/toggleVisibility.controller.php?id=${pokemonId}&visible=${visible}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.textContent = visible === '1' ? 'Marcar como invisible' : 'Marcar como visible';
                                this.setAttribute('data-visible', visible);
                            } else {
                                console.error(data.message);
                            }
                        })
                        .catch(error => console.error('Error updating visibility:', error));
                });
            });
        })
        .catch(error => console.error('Error fetching data:', error));
});