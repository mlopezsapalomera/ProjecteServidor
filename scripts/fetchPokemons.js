async function fetchPokemons() {
    try {
        const response = await fetch('https://pokeapi.co/api/v2/pokemon?limit=1000');
        const data = await response.json();
        populatePokemonDropdown(data.results);
    } catch (error) {
        console.error('Error fetching Pokémon list:', error);
    }
}

function populatePokemonDropdown(pokemons) {
    const dropdown = document.getElementById('pokemon-dropdown');
    pokemons.forEach(pokemon => {
        const option = document.createElement('option');
        option.value = pokemon.name; // Cambiar a pokemon.name
        option.textContent = pokemon.name;
        dropdown.appendChild(option);
    });
}

async function fetchPokemonDetails(url) {
    try {
        const response = await fetch(url);
        const pokemon = await response.json();
        document.getElementById('força').value = pokemon.stats.find(stat => stat.stat.name === 'attack').base_stat;
        document.getElementById('dany').value = pokemon.stats.find(stat => stat.stat.name === 'special-attack').base_stat;
        document.getElementById('vida').value = pokemon.stats.find(stat => stat.stat.name === 'hp').base_stat;
        document.getElementById('tipus').value = pokemon.types.map(type => type.type.name).join(', ');
    } catch (error) {
        console.error('Error fetching Pokémon details:', error);
    }
}

document.addEventListener('DOMContentLoaded', fetchPokemons);

document.getElementById('pokemon-dropdown').addEventListener('change', function() {
    const pokemonUrl = this.value;
    if (pokemonUrl) {
        fetchPokemonDetails(`https://pokeapi.co/api/v2/pokemon/${pokemonUrl}`);
    }
});