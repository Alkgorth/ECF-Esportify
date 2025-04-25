<?php

    require_once _ROOTPATH_ . '\templates\head.php';
    require_once _ROOTPATH_ . '\templates\header.php';

?>

<main class="container mx-5">
    <form class="m-5 p-4 text-white" method="POST" enctype="multipart/form-data">

    <?php if (empty($error) && isset($_POST['valider'])) {?>
      <div class="alert alert-primary" role="alert">
        <?php echo $affichage; ?>
      </div>
    <?php }?>

    <?php if (!empty($error)) {
        foreach ($error as $errors) {?>
      <div class="alert alert-danger" role="alert">
        <?php echo $errors; ?>
      </div>
    <?php }
    }?>

        <h1 class="text-center pb-4">Créer un évènement</h1>

        <div class="mb-3 text-center">
        <label for="cover_image_path">Choisissez une image de couverture:</label>
        <input type="file" id="cover_image_path" name="cover_image_path" accept="image/png, image/jpeg" />
        </div>

        <div class="mb-3 text-center">
        <label for="image_path">Choisissez les images de diaporama</label>
        <input type="file" id="image_path" name="image_path" accept="image/png, image/jpeg" multiple/>
        </div>

        <div class="mb-3 text-center">
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
</main>

<?php

    require_once _ROOTPATH_ . '\templates\footer.php';

?>