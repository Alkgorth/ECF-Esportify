document.addEventListener("DOMContentLoaded", () => {
    const filterButtons = document.querySelectorAll("#filters button");
    const eventsContainer = document.getElementById("events-container");

    filterButtons.forEach(button => {
        button.addEventListener("click", () => {
            const filterType = button.getAttribute("data-filter");
            const cards = Array.from(eventsContainer.querySelectorAll(".event-card"));

            cards.sort((a, b) => {
                if (filterType === "date") {
                    const dateA = new Date(a.dataset.start);
                    const dateB = new Date(b.dataset.start);
                    return dateA - dateB; // plus proche d'abord
                }

                if (filterType === "organisateur") {
                    const orgaA = a.dataset.organisateur;
                    const orgaB = b.dataset.organisateur;
                    return orgaA.localeCompare(orgaB); // ordre alpha
                }

                if (filterType === "joueurs") {
                    const nbA = parseInt(a.dataset.joueurs, 10);
                    const nbB = parseInt(b.dataset.joueurs, 10);
                    return nbA - nbB; // croissant
                }

                return 0;
            });

            // Réinjection dans le DOM dans l'ordre trié
            cards.forEach(card => eventsContainer.appendChild(card));
        });
    });
});