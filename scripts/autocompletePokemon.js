document.getElementById('pokemon-dropdown').addEventListener('change', async function() {
    const pokemonUrl = this.value;
    if (pokemonUrl) {
        try {
            const response = await fetch(pokemonUrl);
            const pokemon = await response.json();
            document.getElementById('nombre').value = pokemon.name;
            document.getElementById('cuerpo').value = pokemon.description || 'No description available.';
            document.getElementById('imagen-preview').src = pokemon.sprites.front_default;
        } catch (error) {
            console.error('Error fetching Pokémon details:', error);
        }
    }
});