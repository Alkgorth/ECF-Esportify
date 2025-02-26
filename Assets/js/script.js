console.log("Je suis chargé");

const openBtn = document.getElementById("nav-burger");
const navBar = document.getElementById("navbar");


/*if (openBtn && navBar) {
    openBtn.addEventListener('click', () => {
        console.log("Click détecté !");
        console.log("navBar:", navBar);
        
        if (navBar.classList) {  // Vérifie que classList existe
            navBar.classList.toggle('visible');
        } else {
            console.error("L'élément navBar n'a pas classList !");
        }
    });
} else {
    console.error("L'élément navBar ou openBtn n'existe pas !");
}*/


openBtn.addEventListener('click', () => {
    const isNavVisible = navBar.classList.contains('visible');

    if (isNavVisible) {
        navBar.classList.remove('visible');
    } else {
        navBar.classList.add('visible');

    }
});