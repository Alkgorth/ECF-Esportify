<?php

    require_once _ROOTPATH_ . '/templates/head.php';
    require_once _ROOTPATH_ . '/templates/header.php';

?>

<main class="container">
    <section>
        <h1>Page événements Administrateur</h1>
    </section>

       <section class="ms-5 mt-2 container row">

        <?php if (empty($error) && isset($_POST['valider'])) {?>
        <div class="alert alert-primary" role="alert">
            <?php echo $affichage; ?>
        </div>
        <?php }?>

        <?php if (! empty($error)) {
                foreach ($error as $fieldName => $errors) {
                    if (is_array($errors)) {
                    foreach ($errors as $errorMessage) {?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $errorMessage; ?>
                            </div>
                        <?php }
                                } else {?>
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
                                    <p class="card-text">Début :<?php echo $event['start'] ?></p>
                                    <p class="card-text">Fin :<?php echo $event['end'] ?></p>
                                    <p class="card-text">Joueurs inscrits :<?php echo $event['joueurs'] ?></p>
                                    <p class="card-text">Status :<?php echo $event['status'] ?></p>
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
                                    <h1 class="modal-title fs-5" id="eventModalLabel<?php echo $event['id'] ?>">Détails de l'événement :<?php echo $event['name'] ?></h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div>

                                    <form class="m-5 p-4" method="POST" enctype="multipart/form-data">

                                        <?php if (empty($error) && isset($_POST['valider'])) {?>
                                            <div class="alert alert-primary" role="alert">
                                                <?php echo $affichage; ?>
                                            </div>
                                        <?php }?>

                                        <?php if (! empty($error)) {
                                                foreach ($error as $fieldName => $errors) {
                                                    if (is_array($errors)) {
                                                    foreach ($errors as $errorMessage) {?>
                                                        <div class="alert alert-danger" role="alert">
                                                            <?php echo $errorMessage; ?>
                                                        </div>
                                                    <?php }
                                                            } else {?>
                                                    <div class="alert alert-danger" role="alert">
                                                        <?php echo $errors; ?>
                                                    </div>
                                                <?php }
                                                        }
                                                }?>

                                        <!-- Input pour CSRF -->
                                        <input type="hidden" name="csrfToken" value="<?php echo $_COOKIE['csrfToken']; ?>">

                                        <!-- Input pour l'id de l'évènement -->
                                        <input type="hidden" name="id_event" value="<?php echo $event['id']; ?>">

                                        <!-- Modal pour la modification de l'évènement -->
                                        <div class="mb-3 text-center">
                                            <label for="cover_image_path">Modifier l'image de couverture</label>
                                            <input type="file" id="cover_image_path" name="cover_image_path" accept="image/png, image/jpeg" />
                                        </div>

                                        <div class="mb-3 text-center">
                                            <label for="image_path">Modifier les images de diaporama</label>
                                            <input type="file" id="image_path" name="image_path[]" accept="image/png, image/jpeg, image/jpg, image/webp" multiple/>
                                        </div>

                                        <div class="mb-3 text-center">
                                            <label for="name_event" class="form-label">Nom évènement</label>
                                                <input type="text" class="form-control
                                                <?php echo(isset($error['name_event']) ? 'is-invalid' : '') ?>" id="name_event"
                                                    name="name_event" value="<?php echo htmlspecialchars($event['name']) ?>" required>
                                                <?php if (isset($error['name_event'])) {?>
                                                    <div class="invalid-feedback"><?php echo $error['name_event'] ?></div>
                                                <?php }?>
                                        </div>
                                        <div class="mb-3 text-center">
                                            <label for="name_game" class="form-label">Nom du jeux</label>
                                            <input type="text" class="form-control
                                            <?php echo(isset($error['name_game']) ? 'is-invalid' : '') ?>" id="name_game"
                                                name="name_game" value="<?php echo htmlspecialchars($event['game_name']) ?>" required>
                                            <?php if (isset($error['name_game'])) {?>
                                                <div class="invalid-feedback"><?php echo $error['name_game'] ?></div>
                                            <?php }?>
                                        </div>

                                        <div class="mb-3 text-center">
                                            <label for="name_plateforme" class="form-label">Plateforme</label>
                                            <select name="fk_id_plateforme" id="fk_id_plateforme">
                                                <?php foreach ($plateformes as $plateforme): ?>
                                                <option value="<?php echo $plateforme['id_plateforme']?>">
                                                    <?php echo $plateforme['id_plateforme'] == $event['plateforme_name'] ? 'selected' : ''?>>
                                                    <?php echo htmlspecialchars($plateforme['name'])?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3 text-center">
                                            <label for="date_hour_start" class="form-label">Date et heure de début</label>
                                            <input type="datetime-local" class="form-control
                                            <?php echo(isset($error['date_hour_start']) ? 'is-invalid' : '') ?>" id="date_hour_start"
                                                name="date_hour_start" value="<?php echo htmlspecialchars($event['start']) ?>" min="" max="" required>
                                            <?php if (isset($error['date_hour_start'])) {?>
                                                <div class="invalid-feedback"><?php echo $error['date_hour_start'] ?></div>
                                            <?php }?>
                                        </div>
                                        <div class="mb-3 text-center">
                                            <label for="date_hour_end" class="form-label">Date et heure de fin</label>
                                            <input type="datetime-local" class="form-control
                                            <?php echo(isset($error['date_hour_end']) ? 'is-invalid' : '') ?>" id="date_hour_end"
                                                name="date_hour_end" value="<?php echo htmlspecialchars($event['end']) ?>" required>
                                            <?php if (isset($error['date_hour_end'])) {?>
                                                <div class="invalid-feedback"><?php echo $error['date_hour_end'] ?></div>
                                            <?php }?>
                                        </div>
                                        <div class="mb-3 text-center">
                                            <label for="nombre_de_joueurs" class="form-label">Nombre de joueurs</label>
                                            <input type="number" class="form-control<?php echo(isset($error['nombre_de_joueurs']) ? 'is-invalid' : '') ?>"
                                                id="nombre_de_joueurs"
                                                name="nombre_de_joueurs"
                                                min="10"
                                                value="<?php echo htmlspecialchars($event['joueurs']) ?>"
                                            required>
                                            <?php if (isset($error['nombre_de_joueurs'])) {?>
                                                <div class="invalid-feedback"><?php echo $error['nombre_de_joueurs'] ?></div>
                                            <?php }?>
                                        </div>
                                        <div class="mb-3 text-center">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control<?php echo(isset($error['description']) ? 'is-invalid' : '') ?>"
                                                id="description"
                                                name="description"
                                                rows="5" cols="33"
                                                required>
                                                <?php echo htmlspecialchars($event['description']) ?>
                                            </textarea>
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

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit" name="saveUpdate" class="btn btn-primary">Sauvegarder modifications</button>
                                            <button type="button" class="btn btn-dark m-4"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmDeleteModal<?php echo $event['id']; ?>">
                                                Supprimer l'évènement
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                            <!-- Modal de confirmation de suppression -->
                            <div class="modal fade" id="confirmDeleteModal<?php echo $event['id']; ?>" tabindex="-1"
                                aria-labelledby="confirmDeleteLabel<?php echo $event['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-danger">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-danger" id="confirmDeleteLabel<?php echo $event['id']; ?>">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <p>Voulez-vous vraiment supprimer l'évènement <strong><?php echo htmlspecialchars($event['name']); ?></strong> ? Cette action est irréversible.</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <form method="POST">
                                            <!-- Pour envoyer l'ID de l'évènement à supprimer -->
                                            <input type="hidden" name="csrfToken" value="<?php echo $_COOKIE['csrfToken']; ?>">
                                            <input type="hidden" name="id_event" value="<?php echo $event['id']; ?>">
                                            <button type="submit" name="delete" class="btn btn-danger">Oui, supprimer</button>
                                            </form>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
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