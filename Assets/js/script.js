console.log("Je suis chargé");

//Affichage du menu déroulant mobile (≤575px)
const burgerBtn = document.getElementById("nav-burger");
const mobileMenu = document.getElementById("mobile-menu");

// Fonction pour gérer l'affichage de la navigation selon la résolution
function handleNavigation() {
  if (burgerBtn && mobileMenu) {
    
    // Fonction pour ouvrir le menu mobile
    function openMobileMenu() {
      burgerBtn.classList.add("menu-open");
      mobileMenu.classList.add("menu-open");
      burgerBtn.querySelector('a').setAttribute('aria-expanded', 'true');
      document.body.style.overflow = 'hidden';
    }
    
    // Fonction pour fermer le menu mobile
    function closeMobileMenu() {
      burgerBtn.classList.remove("menu-open");
      mobileMenu.classList.remove("menu-open");
      burgerBtn.querySelector('a').setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
    }
    
    // Gérer le clic sur le burger menu/croix
    burgerBtn.addEventListener("click", (e) => {
      e.preventDefault();
      const isMenuOpen = burgerBtn.classList.contains("menu-open");
      
      if (isMenuOpen) {
        closeMobileMenu();
      } else {
        openMobileMenu();
      }
    });

    // Fermer le menu si la fenêtre est redimensionnée vers desktop
    window.addEventListener('resize', () => {
      const windowWidth = window.innerWidth;
      if (windowWidth > 575 && burgerBtn.classList.contains("menu-open")) {
        closeMobileMenu();
      }
    });
    
    // Fermer le menu si on clique en dehors (sur l'overlay)
    mobileMenu.addEventListener('click', (e) => {
      if (e.target === mobileMenu) {
        closeMobileMenu();
      }
    });
    
    // Fermer le menu si on clique sur un lien (navigation)
    const mobileLinks = mobileMenu.querySelectorAll('.mobile-nav-link');
    mobileLinks.forEach(link => {
      link.addEventListener('click', () => {
        closeMobileMenu();
      });
    });

  } else {
    console.warn("❌ Éléments de navigation mobile non trouvés");
  }
}

// Initialiser la navigation
handleNavigation();

// Affichage modal détails évènements
document.addEventListener('DOMContentLoaded', () => {
  const eventDetailModal = document.getElementById('eventModal');
  const eventModalContent = document.getElementById('eventModalContent');
  
  eventDetailModal.addEventListener('show.bs.modal', (event) => {
    const anchor = event.relatedTarget;
    const eventId = anchor.getAttribute('data-event-id');

    console.log('ID de l\'événement :', eventId); 
    console.log('URL de la requête :',
      `index.php?controller=event&action=eventDetail&id=${eventId}&ajax=1`);

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
      const formData = new URLSearchParams();
      formData.append('eventId', eventId);
      formData.append('csrfToken', csrfToken);

      console.log("ID de l'évènement cliqué :", eventId);
      
      const url = `index.php?controller=subscription&action=subscribe`;
      try {
          const response = await fetch(url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData.toString(),
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
