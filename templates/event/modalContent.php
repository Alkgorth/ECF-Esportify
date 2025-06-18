<?php
    use App\Tools\EventValidator;

    $cheminCouverture = EventValidator::getCoverDir();
    $cheminDiapo      = EventValidator::getDiaporamaDir();
?>

<?php if ($eventDetail): ?>
            <div class="row align-items-stretch">
                <div class="col-md-6 mb-3">
                    <img src="<?php echo $cheminCouverture . $eventDetail['cover'] ?>"
                    class="img-fluid rounded w-100 h-100 object-fit-cover" alt="<?php echo $eventDetail['name'] ?>">
                </div>
                <div class="col-md-6 d-flex flex-column justify-content-evenly">
                    <h2 class="text-center"><?php echo $eventDetail['name'] ?></h2>
                    <p class="lead text-center">Jeu : <?php echo $eventDetail['game_name'] ?></p>
                    <p class="text-center fs-2">Plateforme : <?php echo $eventDetail['plateforme_name'] ?></p>
                    <p class="text-center fs-2">Organisateur : <?php echo $eventDetail['organisateur'] ?></p>
                </div>
                
                
                <div class="d-flex flex-column align-items-center mt-3">
                    <p class="text-center fs-2">Début : <?php echo $eventDetail['start'] ?></p>
                    <p class="text-center fs-2">Fin : <?php echo $eventDetail['end'] ?></p>
                </div>
                    <p>Nombre de joueurs inscrits :<?php echo $eventDetail['joueurs'] ?></p>
                    <p class="mt-3"><?php echo nl2br($eventDetail['description']) ?></p>

                    <?php if (! empty($eventDetail['diaporama'])): ?>
                            <h4 class="my-3">Diaporama</h4>

                        <div class="container mt-4">
                            <div id="carouselModal" class="carousel slide mx-auto justify-content-center col-8" data-bs-ride="true">
                                <div class="carousel-inner mb-2">
                                        <?php
                                            $diapoImages = explode(',', $eventDetail['diaporama']);
                                            $first       = true;
                                            foreach ($diapoImages as $imageName):
                                                $activeClass = $first ? 'active' : '';
                                            ?>
	                                    <div class="carousel-item <?php echo $activeClass?>">
	                                            <img src="<?php echo $cheminDiapo . trim($imageName) ?>"
                                                class="img-fluid rounded d-block w-100"
                                                alt="Image <?php echo htmlspecialchars(trim($imageName)) ?>">
	                                    </div>
	                                        <?php $first = false; endforeach; ?>
                                </div>

                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselModal" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Précédent</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselModal" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Suivant</span>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                <div class="d-flex mt-4">
                    <!-- Input pour CSRF -->
                    <input type="hidden" id="csrfTokenInput" name="csrf_token" value="<?php echo htmlspecialchars($_COOKIE['csrf_token'] ?? ''); ?>">
                    <button class="btn btn-primary mb-3 bouton-inscription mx-auto"
                    type="button"
                    name="inscription"
                    data-event-id="<?php echo htmlspecialchars($event['id']) ?>">S'inscrire</button>
                </div>
            </div>
        <?php else: ?>
            <p class="alert alert-warning">L'événement demandé n'a pas été trouvé.</p>
        <?php endif; ?>