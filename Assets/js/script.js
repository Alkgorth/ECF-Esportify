console.log("Je suis chargé");

const openBtn = document.getElementById("nav-burger");
const navBar = document.getElementById("navbar");

openBtn.addEventListener('click', () => {
    const isNavVisible = navBar.classList.contains('visible');

    if (isNavVisible) {
        navBar.classList.remove('visible');
    } else {
        navBar.classList.add('visible');
    }
});

document.addEventListener("DOMContentLoaded", () => {
const checkVisibilityPublic = document.getElementById("public");
const checkVisibilityPrivate = document.getElementById("private");

if (checkVisibilityPublic && checkVisibilityPrivate) {
    checkVisibilityPublic.addEventListener('change', () => {
        if (checkVisibilityPublic.checked) {
            checkVisibilityPrivate.checked = false;
        }
    });

    checkVisibilityPrivate.addEventListener('change', () => {
        if (checkVisibilityPrivate.checked) {
            checkVisibilityPublic.checked = false;
        }
    });
} else {
    console.error("Les checkboxes 'public' et 'private' n'ont pas été trouvées.");
}
})

