<?php

    require_once _ROOTPATH_ . '\templates\head.php';
    require_once _ROOTPATH_ . '\templates\header.php';

    $cheminCouverture = '/Assets/Documentation/Images/Couverture/';
    $cheminDiapo      = '/Assets/Documentation/Images/Diapo/';
?>
<main class="container">
   <header class="container mt-5 mb-4 row row-cols-1 text-center">
        <h1>Page mes évènements</h1>
   </header>

   <section class="ms-5 mt-2 container row">

       <?php if (empty($error) && isset($_POST['valider'])) {?>
      <div class="alert alert-primary" role="alert">
        <?php echo $affichage; ?>
      </div>
    <?php }?>

    <?php if (!empty($error)) {
            foreach ($error as $fieldName => $errors) {
                if (is_array($errors)) {
                    foreach ($errors as $errorMessage) {?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php }
                } else { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errors; ?>
                    </div>
                <?php }
            }
        }?>

    <h2 class="mb-3 mt-3 text-center">Évènements suggérés</h2>
    <div class="container mb-3 cardEvent">
        <div class="row row-cols-1 row-cols-md-1 row-cols-lg-4 g-4 events justify-content-center align-items-center d-flex">
            <?php foreach ($events as $event) {?>
                <div class="col">
                    <div class="card h-100">
                        <a href="index.php?controller=event&action=event&id=<?php echo $event['id'] ?>" id=derniersEvent class="text-decoration-none text-white">
                            <img src="<?php echo $cheminCouverture . $event['cover'] ?>" class="card-img-top" alt="<?php echo $event['name'] ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $event['name'] ?></h5>
                            <p class="card-text">Début : <?php echo $event['start'] ?></p>
                            <p class="card-text">Fin : <?php echo $event['end'] ?></p>
                            <p class="card-text">Joueurs inscrits : <?php echo $event['joueurs'] ?></p>
                            <p class="card-text">Status : <?php echo $event['status'] ?></p>
                            <div class="row mt-4">
                                <div class="text-center col-12">
                                    <button type="button" class="btn btn-primary text-center" data-bs-toggle="modal" data-bs-target="#eventModal<?php echo $event['id'] ?>">
                                        Détails / Modifier
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

                            <div>
                                 <form class="m-5 p-4" method="POST" enctype="multipart/form-data">

                            <?php if (empty($error) && isset($_POST['valider'])) {?>
                            <div class="alert alert-primary" role="alert">
                                <?php echo $affichage; ?>
                            </div>
                            <?php }?>

                            <?php if (!empty($error)) {
                                    foreach ($error as $fieldName => $errors) {
                                        if (is_array($errors)) {
                                            foreach ($errors as $errorMessage) {?>
                                                <div class="alert alert-danger" role="alert">
                                                    <?php echo $errorMessage; ?>
                                                </div>
                                            <?php }
                                        } else { ?>
                                            <div class="alert alert-danger" role="alert">
                                                <?php echo $errors; ?>
                                            </div>
                                        <?php }
                                    }
                                }?>

                                <!-- Input pour CSRF -->
                                <input type="hidden" name="csrf_token" value="<?php echo $_COOKIE['csrf_token']; ?>">

                                <div class="mb-3 text-center">
                                <label for="cover_image_path">Image de couverture</label>
                                <input type="file" id="cover_image_path" name="cover_image_path" accept="image/png, image/jpeg" />
                                </div>

                                <div class="mb-3 text-center">
                                <label for="image_path">Choisissez les images de diaporama</label>
                                <input type="file" id="image_path" name="image_path[]" accept="image/png, image/jpeg, image/jpg, image/webp" multiple/>
                                </div>

                                <div class="mb-3 text-center">
                                    <label for="eventName<?php echo $event['id'] ?>" class="form-label">Nom de l'événement</label>
                                        <input type="text" class="form-control" id="eventName<?php echo $event['id'] ?>" name="name" value="<?php echo htmlspecialchars($event['name']) ?>">
                                    <label for="name_event" class="form-label">Nom évènement</label>
                                        <input type="text" class="form-control
                                        <?php echo(isset($error['name_event']) ? 'is-invalid' : '') ?>" id="name_event" name="name_event" required>
                                        <?php if (isset($error['name_event'])) {?>
                                            <div class="invalid-feedback"><?php echo $error['name_event'] ?></div>
                                        <?php }?>
                                </div>
                                <div class="mb-3 text-center">
                                    <label for="name_game" class="form-label">Nom du jeux</label>
                                    <input type="text" class="form-control
                                    <?php echo(isset($error['name_game']) ? 'is-invalid' : '') ?>" id="name_game" name="name_game" required>
                                    <?php if (isset($error['name_game'])) {?>
                                        <div class="invalid-feedback"><?php echo $error['name_game'] ?></div>
                                    <?php }?>
                                </div>

                                <div class="mb-3 text-center">
                                    <label for="name_plateforme" class="form-label">Plateforme</label>
                                    <select name="fk_id_plateforme" id="fk_id_plateforme">
                                        <option value="">Choisisez une plateforme</option>
                                        <?php foreach ($plateformes as $plateforme): ?>
                                            <option value="<?php echo $plateforme['id_plateforme'] ?>"><?php echo $plateforme['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3 text-center">
                                    <label for="date_hour_start" class="form-label">Date et heure de début</label>
                                    <input type="datetime-local" class="form-control
                                    <?php echo(isset($error['date_hour_start']) ? 'is-invalid' : '') ?>" id="date_hour_start" name="date_hour_start" value="" min="" max="" required>
                                    <?php if (isset($error['date_hour_start'])) {?>
                                        <div class="invalid-feedback"><?php echo $error['date_hour_start'] ?></div>
                                    <?php }?>
                                </div>
                                <div class="mb-3 text-center">
                                    <label for="date_hour_end" class="form-label">Date et heure de fin</label>
                                    <input type="datetime-local" class="form-control
                                    <?php echo(isset($error['date_hour_end']) ? 'is-invalid' : '') ?>" id="date_hour_end" name="date_hour_end" required>
                                    <?php if (isset($error['date_hour_end'])) {?>
                                        <div class="invalid-feedback"><?php echo $error['date_hour_end'] ?></div>
                                    <?php }?>
                                </div>
                                <div class="mb-3 text-center">
                                    <label for="nombre_de_joueurs" class="form-label">Nombre de joueurs</label>
                                    <input type="number" class="form-control
                                    <?php echo(isset($error['nombre_de_joueurs']) ? 'is-invalid' : '') ?>" id="nombre_de_joueurs" name="nombre_de_joueurs" min="10" required>
                                    <?php if (isset($error['nombre_de_joueurs'])) {?>
                                        <div class="invalid-feedback"><?php echo $error['nombre_de_joueurs'] ?></div>
                                    <?php }?>
                                </div>
                                <div class="mb-3 text-center">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea type="text" class="form-control
                                    <?php echo(isset($error['description']) ? 'is-invalid' : '') ?>" id="description" name="description" rows="5" cols="33" required></textarea>
                                    <?php if (isset($error['description'])) {?>
                                        <div class="invalid-feedback"><?php echo $error['description'] ?></div>
                                    <?php }?>
                                </div>

                                <div>
                                <fieldset id="checkVisibility">
                                    <legend>Visibilité</legend>
                                    <div>
                                        <input type="checkbox" id="public" name="visibility" value="public" checked />
                                        <label for="public">Public</label>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="private" name="visibility" value="privé" />
                                        <label for="private">Privé</label>
                                    </div>
                                    </fieldset>
                                </div>


                                    <div class="text-center">
                                        <button type="submit" name="valider" class="btn btn-warning m-4">Valider</button>
                                    </div>

                            </form>
                            </div>
                            <div class="modal-body">
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
</main>


 
<?php

    require_once _ROOTPATH_ . '\templates\footer.php';

?>