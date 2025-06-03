<?php

    require_once _ROOTPATH_ . '/templates/head.php';
    require_once _ROOTPATH_ . '/templates/header.php';

    $cheminCouverture = '/Assets/Documentation/Images/Couverture/';
    $cheminDiapo      = '/Assets/Documentation/Images/Diapo/';

?>

<main>
    <section>
        <h1>Gestion des événements Administrateur</h1>
    </section>

    <section class="container ">

        <?php if (! empty($error)): ?>
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
                            <p class="card-text"><small class="text-muted">Jeu :<?php echo htmlspecialchars($event['game_name']) ?></small></p>
                            <p class="card-text"><small class="text-muted">Plateforme :<?php echo htmlspecialchars($event['plateforme']) ?></small></p>
                            <p class="card-text"><small class="text-muted">Début :<?php echo(new DateTime($event['start']))->format('d/m/Y H:i') ?><br>Fin :<?php echo(new DateTime($event['end']))->format('d/m/Y H:i') ?></small></p>
                            <p class="card-text"><small class="text-muted">Joueurs :<?php echo htmlspecialchars($event['joueurs']) ?></small></p>

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

	                                        <div class="carousel-item<?php echo $activeClass ?>">
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

                        <button type="button" class="btn btn-primary text-center" data-bs-toggle="modal" data-bs-target="#eventModal<?php echo $event['id'] ?>">Modifier</button>
                    </div>
                </div>
            </div>

<div class="modal fade cardEvent" id="eventModal<?php echo $event['id'] ?>" tabindex="-1" aria-labelledby="eventModalLabel<?php echo $event['id'] ?>" aria-hidden="true">
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
                                        <input type="hidden" name="csrf_token" value="<?php echo $_COOKIE['csrf_token']; ?>">

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
                                                <?php echo(isset($error['name_event']) ? 'is-invalid' : '') ?>" id="name_event" name="name_event" value="<?php echo htmlspecialchars($event['name']) ?>" required>
                                                <?php if (isset($error['name_event'])) {?>
                                                    <div class="invalid-feedback"><?php echo $error['name_event'] ?></div>
                                                <?php }?>
                                        </div>
                                        <div class="mb-3 text-center">
                                            <label for="name_game" class="form-label">Nom du jeux</label>
                                            <input type="text" class="form-control
                                            <?php echo(isset($error['name_game']) ? 'is-invalid' : '') ?>" id="name_game" name="name_game" value="<?php echo htmlspecialchars($event['game_name']) ?>" required>
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
                                            <?php echo(isset($error['date_hour_start']) ? 'is-invalid' : '') ?>" id="date_hour_start" name="date_hour_start" value="<?php echo htmlspecialchars($event['start']) ?>" min="" max="" required>
                                            <?php if (isset($error['date_hour_start'])) {?>
                                                <div class="invalid-feedback"><?php echo $error['date_hour_start'] ?></div>
                                            <?php }?>
                                        </div>
                                        <div class="mb-3 text-center">
                                            <label for="date_hour_end" class="form-label">Date et heure de fin</label>
                                            <input type="datetime-local" class="form-control
                                            <?php echo(isset($error['date_hour_end']) ? 'is-invalid' : '') ?>" id="date_hour_end" name="date_hour_end" value="<?php echo htmlspecialchars($event['end']) ?>" required>
                                            <?php if (isset($error['date_hour_end'])) {?>
                                                <div class="invalid-feedback"><?php echo $error['date_hour_end'] ?></div>
                                            <?php }?>
                                        </div>
                                        <div class="mb-3 text-center">
                                            <label for="nombre_de_joueurs" class="form-label">Nombre de joueurs</label>
                                            <input type="number" class="form-control                                                                                     <?php echo(isset($error['nombre_de_joueurs']) ? 'is-invalid' : '') ?>"
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
                                            <textarea class="form-control                                                                          <?php echo(isset($error['description']) ? 'is-invalid' : '') ?>"
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
                            <div class="modal fade cardEvent" id="confirmDeleteModal<?php echo $event['id']; ?>" tabindex="-1" aria-labelledby="confirmDeleteLabel<?php echo $event['id']; ?>" aria-hidden="true">
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
                                            <input type="hidden" name="csrf_token" value="<?php echo $_COOKIE['csrf_token']; ?>">
                                            <input type="hidden" name="id_event" value="<?php echo $event['id']; ?>">
                                            <button type="submit" name="delete" class="btn btn-danger">Oui, supprimer</button>
                                            </form>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

        <?php }?>
</section>

</main>

<?php

    require_once _ROOTPATH_ . '/templates/footer.php';

?>