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