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

const checkVisibilityPublic = document.getElementById("public");
const checkVisibilityPivate = document.getElementById("privé");

checkVisibilityPublic.addEventListener('change', () => {
    if (checkVisibilityPublic.checked) {
        checkVisibilityPivate.checked = false;
    }
});

checkVisibilityPivate.addEventListener('change', () => {
    if (checkVisibilityPivate.checked) {
        checkVisibilityPublic.checked = false;
    }
});