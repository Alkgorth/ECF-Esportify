let openBtn = document.getElementById("nav-burger");



const openNav = () => {
    
    navbar.style.left = "0";
};

const closeNav = () => {
    
    navbar.style.left = "-100%";
};

openBtn.addEventListener("click", openNav);
closeBtn.addEventListener("click", closeNav);
navWrapper.addEventListener("click", closeNav);

// document.addEventListener("DOMContentLoaded", function () {
//     const navbar = document.querySelector(".navbar");
//     const navBurger = document.getElementById("nav-burger");

//     navBurger.addEventListener("click", function (event) {
//         event.preventDefault();
//         navbar.classList.toggle("active");
//     });

//     // Fermer le menu si on clique en dehors
//     document.addEventListener("click", function (event) {
//         if (!navbar.contains(event.target) && !navBurger.contains(event.target)) {
//             navbar.classList.remove("active");
//         }
//     });
// });