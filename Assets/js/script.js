console.log("Je suis chargé");

//Affichage du menu en -600px
const openBtn = document.getElementById("nav-burger");
const navBar = document.getElementById("navbar");

openBtn.addEventListener("click", () => {
  const isNavVisible = navBar.classList.contains("visible");

  if (isNavVisible) {
    navBar.classList.remove("visible");
  } else {
    navBar.classList.add("visible");
  }
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
              'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({eventId: eventId}),
          });

          if (!response.ok) {
            const errorData = await response.json();
            console.error("Erreur HTTP ou réponse non JSON:", response.status, errorDetails);
            alert(`Erreur du serveur (${response.status}) : ${errorDetails.substring(0, 100)}...`);
            alert ("Erreur lors de l'inscription ! " + errorData.message || `HTTP-Error: ${response.status}`);
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
        console.error("Erreur lors de la récupération de l'évènement : ", error);
        alert("Une erreur inattendue est survenue. Veuillez réessayer.")
      }
    });
  });
});
