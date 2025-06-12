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

const inscription = document.getElementById("inscription");

inscription.addEventListener("click", async(e) => {
  e.preventDefault();
  const url = `http://esportify:8000/index.php?controller=subscription&action=subscribe&id=${$event['id']}`;

  try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({eventId}),
      });

      const result = await response.json();

      if (result.success) {
        alert("Vous êtes insrit à l'évènement !");
      }
      
  } catch (error) {
    console.error("Erreur lors de la récupération de l'évènement : ", error);
  }
})
