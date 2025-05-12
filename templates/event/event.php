<?php

    require_once _ROOTPATH_ . '\templates\head.php';
    require_once _ROOTPATH_ . '\templates\header.php';

    $cheminCouverture = '/Assets/Documentation/Images/Couverture/';
    $cheminDiapo      = '/Assets/Documentation/Images/Diapo/';

?>

<main>
    <section class="container mt-5 mb-4">

        <h1>Page événements visiteur</h1>

        <?php if ($eventDetail): ?>

            <div class="row">
                <div class="col-md-6">
                    <img src="<?php echo $cheminCouverture . $eventDetail['cover']?>" class="img-fluid rounded" alt="<?php echo $eventDetail['name']?>">
                </div>

                <div class="col-md-6">
                    <h2 class="text-center"><?php echo $eventDetail['name']?></h2>
                    <p class="lead text-center">Jeu : <?php echo $eventDetail['game_name']?></p>
                    <p class="text-center fs-2">Plateforme : <?php echo $eventDetail['plateforme_name']?></p>
                </div>

                <div>
                    <p class="fs-2">Organisateur : <?php echo $eventDetail['organisateur']?></p>
                </div>

                <div class="row row-cols-1 row-cols-md-2 align-items-center">
                    <p class="text-center fs-2">Début : <?php echo $eventDetail['start']?></p>
                    <p class="text-center fs-2">Fin : <?php echo $eventDetail['end']?></p>
                </div>
                    <p>Nombre de joueurs inscrits : <?php echo $eventDetail['joueurs']?></p>
                    <p class="mt-3"><?php echo nl2br($eventDetail['description'])?></p>

                    <?php if (! empty($eventDetail['diaporama'])): ?>
                            <h3>Diaporama</h3>
                        
                        <div class="container mt-4">
                            <div id="carouselExampleRide" class="carousel slide mx-auto justify-content-center col-8" data-bs-ride="true">
                                <div class="carousel-inner mb-2">
                                        <?php
                                        $diapoImages = explode(',', $eventDetail['diaporama']);
                                        $first = true;
                                        foreach ($diapoImages as $imageName): 
                                        $activeClass = $first ? 'active' : '';
                                        ?>
                                    <div class="carousel-item <?= $activeClass ?>">
                                            <img src="<?php echo $cheminDiapo . trim($imageName)?>" class="img-fluid rounded d-block w-100" alt="Image du diaporama">
                                    </div>
                                        <?php
                                        $first = false;
                                        endforeach;
                                        ?>
                                </div>
                                
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>

                </div>
            </div>
        <?php else: ?>
            <p class="alert alert-warning">L'événement demandé n'a pas été trouvé.</p>
        <?php endif; ?>
    </section>

    <!-- Chat asynchrone / à rendre visible uniquement si l'utilisateur est enregistré sur l'évènement-->
     <section class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="mb-3">
                <label for="chatAsynchrone" class="form-label">Exprimez-vous sur l'événement*</label>
                <textarea class="form-control" id="chatAsynchrone" rows="5" placeholder="Ecrivez votre message"></textarea>
                <div class="form-text">
                </div>
            </div>
        </div>
    </div>

     </section>
</main>

<?php

    require_once _ROOTPATH_ . '\templates\footer.php';

?>