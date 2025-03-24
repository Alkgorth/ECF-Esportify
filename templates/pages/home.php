<?php

require_once _ROOTPATH_ . '\templates\head.php';
require_once _ROOTPATH_ . '\templates\header.php';

?>

<main class="">
    <section class="container mt-4 sectionHaute">
        <div class="banniere container-fluid">
            <img src="../../Assets/Documentation/Images/Logo-Bannière/Banniere_Esportify.png" alt="bannière esportify">
        </div>
        <div class="container p-4">
            <p class="text-align-center fs-4">
                Fondée en 2021, Esportify est une startup innovante spécialisée dans l’organisation
                de compétitions de jeux vidéo. Après un succès grandissant, nous développons une
                plateforme en ligne dédiée pour offrir aux joueurs une expérience optimisée
                : inscription aux tournois, suivi des performances et interactions entre passionnés.
                Notre mission ? Révolutionner l’e-sport en rendant les compétitions plus accessibles et dynamiques.
            </p>
        </div>

        <!-- Carrousel -->
        <div class="container">
            <div id="carouselExampleRide" class="carousel slide mx-auto justify-content-center col-8" data-bs-ride="true">
                <div class="carousel-inner mb-2">
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
                <div class="d-flex flex-nowrap justify-content-center col-8 mx-auto miniature">
                    <div class="col-3 mx-2" data-bs-target="#carouselExample" data-bs-slide-to="0">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-3.jpg" class="img-fluid w-100" alt="Image 1">
                    </div>
                    <div class="col-3 mx-2" data-bs-target="#carouselExample" data-bs-slide-to="1">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-1.jpg" class="img-fluid w-100" alt="Image 2">
                    </div>
                    <div class="col-3 mx-2" data-bs-target="#carouselExample" data-bs-slide-to="2">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-2.jpg" class="img-fluid w-100" alt="Image 3">
                    </div>
                    <div class="col-3 mx-2" data-bs-target="#carouselExample" data-bs-slide-to="3">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-4.jpg" class="img-fluid w-100" alt="Image 4">
                    </div>
                    <div class="col-3 mx-2" data-bs-target="#carouselExample" data-bs-slide-to="4">
                        <img src="../../Assets/Documentation/Images/gameurs/e-sport-equipe.jpg" class="img-fluid w-100" alt="Image 5">
                    </div>
                </div>
            </div>
        </div>


    </section>
    <section class="section-light mt-2">

        <h2 class="titleEvent">Les derniers évènements</h2>
        <?php foreach ($events as $event) { ?>
            <div class="container">
                <div class="row row-cols-1 row-cols-md-4 g-4 events">
                    <div class="col">
                        <div class="card h-100">
                            <a href="index.php?controller=event&action=event&id=<?= $event['id'] ?> id=derniersEvent" class="text-decoration-none text-white">
                                <img src="..." class="card-img-top" alt="<?= $event['name'] ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?= $event['name'] ?></h5>
                                <p class="card-text"><?= $event['plateforme_name'] ?></p>
                                <p class="card-text">Début : <?= $event['start'] ?></p>
                                <p class="card-text">Fin : <?= $event['end'] ?></p>
                                <p class="card-text">Joueurs inscrits : <?= $event['joueurs'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php foreach ($events as $event) { ?>
            <div class="container">
                <div class="row row-cols-1 row-cols-md-4 g-4 events">
                    <div class="col">
                        <div class="card h-100">
                            <a href="index.php?controller=event&action=event&id=<?= $event['id'] ?> id=derniersEvent" class="text-decoration-none text-white">
                                <img src="..." class="card-img-top" alt="<?= $event['name'] ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?= $event['name'] ?></h5>
                                <p class="card-text"><?= $event['plateforme_name'] ?></p>
                                <p class="card-text">Début : <?= $event['start'] ?></p>
                                <p class="card-text">Fin : <?= $event['end'] ?></p>
                                <p class="card-text">Joueurs inscrits : <?= $event['joueurs'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </section>
</main>

<?php

require_once _ROOTPATH_ . '\templates\footer.php';

?>