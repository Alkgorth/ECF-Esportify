<?php

    require_once _ROOTPATH_ . '\templates\head.php';
    require_once _ROOTPATH_ . '\templates\header.php';

    $cheminCouverture = '/Assets/Documentation/Images/Couverture/';
    $cheminDiapo      = '/Assets/Documentation/Images/Diapo/';
?>

   <section class="container mt-5 mb-4 row row-cols-1 text-center">
        <h1>Page mes événements</h1>
   </section>

   <section class="ms-5 mt-2 container row text-center justify-content-center align-items-center d-flex">

    <h2 class="mb-3 mt-3 d-flex">Mes évènements</h2>
    <div class="container mb-3">
        <div class="row row-cols-1 row-cols-md-1 row-cols-lg-4 g-4 events">
            <?php foreach ($events as $event) {?>
                <div class="col">
                    <div class="card h-100">
                        <a href="index.php?controller=event&action=event&id=<?php echo $event['id'] ?>" id=derniersEvent class="text-decoration-none text-white">
                            <img src="<?php echo $cheminCouverture . $event['cover'] ?>" class="card-img-top" alt="<?php echo $event['name'] ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $event['name'] ?></h5>
                            <p class="card-text"><?php echo $event['plateforme_name'] ?></p>
                            <p class="card-text">Début :<?php echo $event['start'] ?></p>
                            <p class="card-text">Fin :<?php echo $event['end'] ?></p>
                            <p class="card-text">Joueurs inscrits :<?php echo $event['joueurs'] ?></p>
                            <div class="row mt-4">
                                <div class="text-center col-12">
                                    <button type="button" class="btn btn-primary text-center" data-bs-toggle="modal" data-bs-target="#eventModal<?php echo $event['id'] ?>">
                                        Voir détails
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="eventModal<?php echo $event['id'] ?>" tabindex="-1" aria-labelledby="eventModalLabel<?php echo $event['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="eventModalLabel<?php echo $event['id'] ?>">Détails de l'événement : <?php echo $event['name'] ?></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <p>Nom : <?php echo $event['name'] ?></p>
                                <p>Plateforme : <?php echo $event['plateforme_name'] ?></p>
                                <p>Début : <?php echo $event['start'] ?></p>
                                <p>Fin : <?php echo $event['end'] ?></p>
                                <p>Joueurs inscrits : <?php echo $event['joueurs'] ?></p>
                                <hr>
                                <h4>Modifier l'événement</h4>
                                <form action="index.php?controller=event&action=updateEvent" method="POST">
                                    <input type="hidden" name="event_id" value="<?php echo $event['id'] ?>">
                                    <div class="mb-3">
                                        <label for="eventName<?php echo $event['id'] ?>" class="form-label">Nom de l'événement</label>
                                        <input type="text" class="form-control" id="eventName<?php echo $event['id'] ?>" name="name" value="<?php echo htmlspecialchars($event['name']) ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                                </form>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
</section>



 
<?php

    require_once _ROOTPATH_ . '\templates\footer.php';

?>