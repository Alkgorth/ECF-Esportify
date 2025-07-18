<?php

    require_once _ROOTPATH_ . '/templates/head.php';
    require_once _ROOTPATH_ . '/templates/header.php';

    $cheminCouverture = '/Assets/Documentation/Images/Couverture/';
    $cheminDiapo      = '/Assets/Documentation/Images/Diapo/';

?>

<main>
    <section class="container mt-4 sectionHaute">
        <div class="banniere container-fluid text-center">
            <img src="../../Assets/Documentation/Images/Logo-Bannière/Banniere-01.png" alt="bannière esportify" class="img-fluid banniere-img">
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
                <div class="carousel-inner mb-2 zoneCarousel">
                    <div class="carousel-item active">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-3.jpg"
                            class="d-block w-100" alt="Gameuse victoire">
                    </div>
                    <div class="carousel-item">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-1.jpg"
                            class="d-block w-100" alt="Gameuse Ingame">
                    </div>
                    <div class="carousel-item">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-2.jpg"
                            class="d-block w-100" alt="Team Gamers">
                    </div>
                    <div class="carousel-item">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-4.jpg"
                            class="d-block w-100" alt="Team Gamers">
                    </div>
                    <div class="carousel-item">
                        <img src="../../Assets/Documentation/Images/gameurs/e-sport-equipe.jpg"
                            class="d-block w-100" alt="Team Gamers">
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
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-3 miniature-wrapper">

                    <div class="miniature-thumb" data-bs-target="#carouselExampleRide" data-bs-slide-to="0">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-3.jpg"
                            class="img-fluid w-100" alt="Miniature Gameuse victoire">
                    </div>
                    <div class="miniature-thumb" data-bs-target="#carouselExampleRide" data-bs-slide-to="1">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-1.jpg"
                            class="img-fluid w-100" alt="Miniature Gameuse Ingame">
                    </div>
                    <div class="miniature-thumb" data-bs-target="#carouselExampleRide" data-bs-slide-to="2">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-2.jpg"
                            class="img-fluid w-100" alt="Miniature Team Gamers">
                    </div>
                    <div class="miniature-thumb" data-bs-target="#carouselExampleRide" data-bs-slide-to="3">
                        <img src="../../Assets/Documentation/Images/gameurs/Competition-esport-4.jpg"
                            class="img-fluid w-100" alt="Miniature Team Gamers">
                    </div>
                    <div class="miniature-thumb" data-bs-target="#carouselExampleRide" data-bs-slide-to="4">
                        <img src="../../Assets/Documentation/Images/gameurs/e-sport-equipe.jpg"
                            class="img-fluid w-100" alt="Miniature Team Gamers">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cartes des évènements validés -->
    <section class="sectionBasse mt-2">
        <h2 class="mb-4 text-center">Les derniers évènements</h2>
        <div class="container mb-3">
            <div class="row row-cols-1 row-cols-md-4 g-4 events">
                <?php foreach ($events as $event) {?>
                    <div class="col">
                        <div class="card h-100 carte">
                            <a href="#" class="text-decoration-none text-white open-event-modal" data-bs-toggle="modal"
                                data-bs-target="#eventModal" data-event-id="<?php echo $event['id']; ?>">
                                    <img src="<?php echo $cheminCouverture . $event['cover'] ?>" class="card-img-top"
                                        alt="<?php echo $event['name'] ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title carteTitre"><?php echo $event['name'] ?></h5>
                                <p class="card-text carteTexte"><?php echo $event['plateforme_name'] ?></p>
                                <p class="card-text carteTexte">Début :<?php echo $event['start'] ?></p>
                                <p class="card-text carteTexte">Fin :<?php echo $event['end'] ?></p>
                                <p class="card-text carteTexte">Joueurs inscrits :<?php echo $event['joueurs'] ?></p>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
    </section>
    
    <!-- Modal détails évènement -->
     <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header titleModal">
                    <h5 class="modal-title" id="eventModalLabel">Détail de l'évènement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body" id="eventModalContent">
                    </div>
                </div>
            </div>
        </div>
     </div>
</main>

<?php

    require_once _ROOTPATH_ . '/templates/footer.php';

?>