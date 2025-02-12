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