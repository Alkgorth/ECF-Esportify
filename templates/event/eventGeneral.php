<?php

    require_once _ROOTPATH_ . '/templates/head.php';
    require_once _ROOTPATH_ . '/templates/header.php';

    $cheminCouverture = '/Assets/Documentation/Images/Couverture/';
    $cheminDiapo      = '/Assets/Documentation/Images/Diapo/';
?>

<main>
    <section class="container mt-5 mb-4">
        <h1>Page événements générale</h1>

    </section>
<section class="container ">

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


    <?php foreach ($allEvent as $event) {?>
        <div class="card mb-3 container-fluid col-10">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="<?php echo htmlspecialchars($cheminCouverture . $event['cover']) ?>" class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($event['name']) ?>">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($event['name']) ?></h5>
                        <p class="card-text"><small class="text-muted">Jeu : <?php echo htmlspecialchars($event['game_name']) ?></small></p>
                        <p class="card-text"><small class="text-muted">Plateforme : <?php echo htmlspecialchars($event['plateforme']) ?></small></p>
                        <p class="card-text"><small class="text-muted">Début : <?php echo (new DateTime($event['start']))->format('d/m/Y H:i') ?><br>Fin : <?php echo (new DateTime($event['end']))->format('d/m/Y H:i') ?></small></p>
                        <p class="card-text"><small class="text-muted">Joueurs : <?php echo htmlspecialchars($event['joueurs']) ?></small></p>

                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEventDetail_<?php echo htmlspecialchars($event['id']) ?>" aria-expanded="false" aria-controls="collapseEventDetail_<?php echo htmlspecialchars($event['id']) ?>">
                            Voir les détails
                        </button>
                    </div>
                </div>
            </div>


            <div class="collapse" id="collapseEventDetail_<?php echo htmlspecialchars($event['id']) ?>">
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
                    
                    <button class="btn btn-primary" type="submit">S'inscrire</button>
                    </div>
            </div>
        </div>
    <?php }?>
</section>
</main>

<?php

    require_once _ROOTPATH_ . '/templates/footer.php';

?>