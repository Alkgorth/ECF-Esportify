<?php

    require_once _ROOTPATH_ . '/templates/head.php';
    // require_once _ROOTPATH_ . '/templates/header.php';

    $cheminCouverture = '/Assets/Documentation/Images/Couverture/';
    $cheminDiapo      = '/Assets/Documentation/Images/Diapo/';
?>

<main class="eventGeneral">
    <section class="container mt-5 mb-4">
        <h1>Page événements générale</h1>

    </section>
<section class="container">

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                <?php
                    $errorsList = is_array($error) ? $error : [$error];
                    foreach ($errorsList as $fieldErrors) {
                        if (is_array($fieldErrors)) {
                            foreach ($fieldErrors as $msg) {
                                echo '<li>' . htmlspecialchars($msg) . '</li>';
                            }
                        } else {
                            echo '<li>' . htmlspecialchars($fieldErrors) . '</li>';
                        }
                    }
                ?>
            </ul>
        </div>
    <?php endif; ?>

    <div id="filters" class="d-flex justify-content-center gap-3 my-4">
        <button data-filter="date" class="btn btn-outline-primary">Date</button>
        <button data-filter="organisateur" class="btn btn-outline-primary">Organisateur</button>
        <button data-filter="joueurs" class="btn btn-outline-primary">Nombre de joueurs</button>
    </div>

    <div class="container ms-4">
        <div id="events-container" class="container-fluid ms-4">
            <?php foreach ($allEvent as $event) {?>
                <div class="card mb-3 col-10 event-card carte"
                    data-start="<?php echo (new DateTime($event['start']))->format('Y-m-d H:i') ?>"
                    data-organisateur="<?php echo htmlspecialchars(strtolower($event['organisateur'])) ?>"
                    data-joueurs="<?php echo (int)$event['joueurs'] ?>">
                    <div class="row g-0">
                        <div class="col-md-4 image-carte">
                            <a href="index.php?controller=event&action=eventDetail&id=<?php echo $event['id'] ?>" id=derniersEvent class="text-decoration-none text-white">
                                <img src="<?php echo htmlspecialchars($cheminCouverture . $event['cover']) ?>" class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($event['name']) ?>">
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body ">
                                <h5 class="card-title carteTitre"><?php echo htmlspecialchars($event['name']) ?></h5>
                                <p class="card-text carteTexte"><small>Jeu : <?php echo htmlspecialchars($event['game_name']) ?></small></p>
                                <p class="card-text carteTexte"><small>Plateforme : <?php echo htmlspecialchars($event['plateforme']) ?></small></p>
                                <p class="card-text carteTexte"><small>Début : <?php echo (new DateTime($event['start']))->format('d/m/Y H:i') ?><br>Fin : <?php echo (new DateTime($event['end']))->format('d/m/Y H:i') ?></small></p>
                                <p class="card-text carteTexte"><small>Joueurs : <?php echo htmlspecialchars($event['joueurs']) ?></small></p>

                                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEventDetail_<?php echo htmlspecialchars($event['id']) ?>" aria-expanded="false" aria-controls="collapseEventDetail_<?php echo htmlspecialchars($event['id']) ?>">
                                    Voir les détails
                                </button>
                            </div>
                        </div>
                    </div>


                    <div class="collapse carteTexte" id="collapseEventDetail_<?php echo htmlspecialchars($event['id']) ?>">
                        <div class="card-footer">
                            <p class="card-text"><strong>Description : </strong><?php echo nl2br(htmlspecialchars($event['description'])) ?></p>
                            <p class="card-text"><strong>Organisateur : </strong><?php echo htmlspecialchars($event['organisateur']) ?></p>
                            <p class="card-text"><strong>Visibilité : </strong><?php echo htmlspecialchars($event['visibilite']) ?></p>

                            <?php if (! empty($event['diaporama'])): ?>
                                <p class="card-text"><strong>Diaporama:</strong></p>

                                <div class="container mt-4">
                                    <div id="carouselExampleRide_<?php echo htmlspecialchars($event['id']) ?>" class="carousel slide mx-auto justify-content-center col-8" data-bs-ride="true">
                                        <div class="carousel-inner mb-2">
                                            <?php
                                                $diapoImages = explode(', ', $event['diaporama']);
                                                $first       = true;
                                                foreach ($diapoImages as $imageName):
                                                    $activeClass = $first ? 'active' : '';
                                            ?>
                                            
                                            <div class="carousel-item <?php echo $activeClass?>">
                                                <img src="<?php echo htmlspecialchars($cheminDiapo . trim($imageName)) ?>" class="img-fluid rounded d-block w-100" alt="Image du diaporama">
                                            </div>
                                            
                                            <?php
                                                    $first = false;
                                                endforeach;
                                            ?>
                                        </div>

                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleRide_<?php echo htmlspecialchars($event['id']) ?>" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleRide_<?php echo htmlspecialchars($event['id']) ?>" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="d-flex">
                            <button class="btn btn-primary mb-3 inscription mx-auto" type="submit" name="inscription">S'inscrire</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
</section>

</main>

<?php

    require_once _ROOTPATH_ . '/templates/footer.php';

?>