console.log("Je suis chargé");

//Affichage du menu en -600px
const openBtn = document.getElementById("nav-burger");
const navBar = document.getElementById("navbar");

if (openBtn && navBar) {
  openBtn.addEventListener("click", () => {
    const isNavVisible = navBar.classList.contains("visible");
  
    if (isNavVisible) {
      navBar.classList.remove("visible");
    } else {
      navBar.classList.add("visible");
    }
  });
} else {
  console.warn("Éléments de navigation (nav-burger, navbar) non trouvés.");
}

// Affichage modal détails évènements
document.addEventListener('DOMContentLoaded', () => {
  const eventDetailModal = document.getElementById('eventModal');
  const eventModalContent = document.getElementById('eventModalContent');
  
  eventDetailModal.addEventListener('show.bs.modal', (event) => {
    const anchor = event.relatedTarget;
    const eventId = anchor.getAttribute('data-event-id');

    console.log('ID de l\'événement :', eventId); 
    console.log('URL de la requête :', `http://esportify:8000/index.php?controller=event&action=eventDetail&id=${eventId}&ajax=1`);

    eventModalContent.innerHTML = `
      <div class="text-center">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Chargement...</span>
        </div>
      </div>`;

      fetch(`index.php?controller=event&action=eventDetail&id=${eventId}&ajax=1`)
        .then(response => {
          console.log('Réponse du serveur (brute):', response);
            if (!response.ok) {
              throw new Error(`Erreur HTTP : ${response.status} ${response.statusText}`);
            }
            return response.text();
        })
        .then(html => {
          console.log('Contenu HTML reçu : ', html);
          eventModalContent.innerHTML = html;

          const newCarousel = eventModalContent.querySelector('#carouselExampleRide');
          if (newCarousel) {
            new bootstrap.Carousel(newCarousel);
          }
        })
        .catch(error => {
          console.error('Erreur lors du chargement des détails de l\'évènement : ', error);
          eventModalContent.innerHTML = `
              <div class="alert alert-danger" role="alert">
                  Impossible de charger les détails de l'évènement : ${error.message}
                  </div>`;
        });
  });
});

// Fonction pour valider une seule checkbox dans visibility
document.addEventListener("DOMContentLoaded", () => {
  const checkVisibilityPublic = document.getElementById("public");
  const checkVisibilityPrivate = document.getElementById("private");

  if (checkVisibilityPublic && checkVisibilityPrivate) {
    checkVisibilityPublic.addEventListener("change", () => {
      if (checkVisibilityPublic.checked) {
        checkVisibilityPrivate.checked = false;
      }
    });

    checkVisibilityPrivate.addEventListener("change", () => {
      if (checkVisibilityPrivate.checked) {
        checkVisibilityPublic.checked = false;
      }
    });
  } else {
    console.error(
      "Les checkboxes 'public' et 'private' n'ont pas été trouvées."
    );
  }
});

// Récupération bouton inscription joueur à un évènement
document.addEventListener("DOMContentLoaded", () => {

  const inscriptionButtons = document.querySelectorAll(".bouton-inscription");
  const csrfTokenInput = document.getElementById('csrfTokenInput');
  const csrfToken = csrfTokenInput ? csrfTokenInput.value : null

  if (!csrfToken) {
    console.error("Jeton CSRF introuvable dans l'input hidden. Sécurité de l'application compromise.");
  }

  inscriptionButtons.forEach(button => {
    button.addEventListener("click", async(e) => {
      e.preventDefault();

      const eventId = button.dataset.eventId;

      console.log("ID de l'évènement cliqué :", eventId);
      
      const url = `http://esportify:8000/index.php?controller=subscription&action=subscribe`;
      try {
          const response = await fetch(url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({eventId: eventId, csrfToken: csrfToken}),
          });

          if (!response.ok) {
            const errorData = await response.json();
            console.error("Erreur HTTP ou réponse non JSON:", response.status, errorData.message);
            alert(`Erreur du serveur (${response.status}) : ${errorData.message || 'Détails non disponibles.'}`);
            return;
          }

          const result = await response.json();
      
          if (result.success) {
            alert("Vous êtes inscrit à l'évènement !");
            //Ajouter changement du bouton en désinscription
          } else {
            alert("Erreur : " + result.message || "Inscription impossible.");
          }
      
      } catch (error) {
        console.error("Erreur lors de la requête d'inscription : ", error);
        alert("Une erreur inattendue est survenue. Veuillez réessayer.")
      }      
    });
  });
});
