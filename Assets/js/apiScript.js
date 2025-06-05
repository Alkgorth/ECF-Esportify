import { APIKEY } from "./prod.env.js";

const input = document.getElementById("name_game");
const suggestionsContainer = document.getElementById("suggestions");

input.addEventListener("input", async() => {
    const query = input.value.trim();
    if (query.length >= 3) {
        const url = `https://api.rawg.io/api/games?key=${APIKEY}&search=${encodeURIComponent(query)}&page_size=5`;

        try {
            const response = await fetch(url);
            const data = await response.json();
            const games = data.results;

            suggestionsContainer.innerHTML = games.map(game =>
                `<div class="suggestion-item" data-name="${game.name}">
                ${game.name}
                </div>`
            ).join("");

        } catch (error) {
            console.error("Erreur lors de la récupération des jeux : ", error);
        }
        return;
    }
})

suggestionsContainer.addEventListener("click", (e) => {
    if (e.target.classList.contains("suggestion-item")) {
        input.value = e.target.dataset.name;
        suggestionsContainer.innerHTML = "";
    }
});