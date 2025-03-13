<?php

require_once _ROOTPATH_ . '\templates\head.php';
require_once _ROOTPATH_ . '\templates\header.php';

?>

<main class="w-100">
    <section class="d-flex flex-column mx-auto justify-content-center">
        <div class="banniere container">
            <img src="../../Assets/Documentation/Images/Logo-Bannière/Banniere_Esportify.png" alt="bannière esportify">
        </div>
        <div class="bienvenue container p-4">
            <p class="text-align-center fs-4">
                Fondée en 2021, Esportify est une startup innovante spécialisée dans l’organisation
                de compétitions de jeux vidéo. Après un succès grandissant, nous développons une
                plateforme en ligne dédiée pour offrir aux joueurs une expérience optimisée
                : inscription aux tournois, suivi des performances et interactions entre passionnés.
                Notre mission ? Révolutionner l’e-sport en rendant les compétitions plus accessibles et dynamiques.
            </p>
        </div>

        <!-- Carrousel -->
        <div class="container toutCarousel d-flex flex-column justify-content-center">
            <div id="carouselExampleRide" class="carousel slide mx-auto justify-content-center" data-bs-ride="true">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-3.jpg" class="d-block w-100" alt="Gameuse victoire">
                    </div>
                    <div class="carousel-item">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-1.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-2.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-4.jpg" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="../../Assets/Documentation/Images/gameurs/e-sport-equipe.jpg" class="d-block w-100" alt="...">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>

                <!-- Miniatures -->
                <div class="gap-2 mt-3 d-flex justify-content-center flex-nowrap thumbnails-container miniature">
                    <div class="col-4 thumbnail active" data-bs-target="#carouselExample" data-bs-slide-to="0">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-3.jpg" class="img-fluid" alt="Image 1">
                    </div>
                    <div class="col-4 thumbnail" data-bs-target="#carouselExample" data-bs-slide-to="1">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-1.jpg" class="img-fluid" alt="Image 2">
                    </div>
                    <div class="col-4 thumbnail" data-bs-target="#carouselExample" data-bs-slide-to="2">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-2.jpg" class="img-fluid" alt="Image 3">
                    </div>
                    <div class="col-4 thumbnail" data-bs-target="#carouselExample" data-bs-slide-to="2">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-4.jpg" class="img-fluid" alt="Image 3">
                    </div>
                    <div class="col-4 thumbnail" data-bs-target="#carouselExample" data-bs-slide-to="2">
                        <img src="../../Assets/Documentation/Images/gameurs/e-sport-equipe.jpg" class="img-fluid" alt="Image 3">
                    </div>
                </div>
            </div>
        </div>

    </section>
</main>

<?php

require_once _ROOTPATH_ . '\templates\footer.php';

?>