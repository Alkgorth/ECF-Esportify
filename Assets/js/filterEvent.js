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
                    return dateA - dateB;
                }

                if (filterType === "organisateur") {
                    const orgaA = a.dataset.organisateur;
                    const orgaB = b.dataset.organisateur;
                    return orgaA.localeCompare(orgaB);
                }

                if (filterType === "joueurs") {
                    const nbA = parseInt(a.dataset.joueurs, 10);
                    const nbB = parseInt(b.dataset.joueurs, 10);
                    return nbA - nbB;
                }

                return 0;
            });

            cards.forEach(card => eventsContainer.appendChild(card));
        });
    });
});