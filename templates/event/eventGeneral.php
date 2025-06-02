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

    <div class="card mb-3 contauner-fluid col-10">
        <?php foreach ($allEvent as $event) {?>
    <div class="row g-0">
        <div class="col-md-4">
            <img src="<?php echo $cheminCouverture . $event['cover'] ?>" class="img-fluid rounded-start" alt="<?php echo $event['name'] ?>">
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title"><?php echo $event['name'] ?></h5>
                <p class="card-text"><small class="text-muted">Jeu: Nom du jeu</small></p>
                <p class="card-text"><small class="text-muted">Plateforme : <?php echo $event['plateforme_name'] ?></</small></p>
                <p class="card-text"><small class="text-muted">Début: <?php echo $event['start'] ?> - <?php echo $event['end'] ?></small></p>
                <p class="card-text"><small class="text-muted">Joueurs: <?php echo $event['joueurs'] ?></small></p>

                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEventDetail_ID_EVENEMENT" aria-expanded="false" aria-controls="collapseEventDetail_ID_EVENEMENT">
                    Voir les détails
                </button>
            </div>
        </div>
    </div>


    <div class="collapse" id="collapseEventDetail_ID_EVENEMENT">
        <div class="card-footer">
            <p class="card-text"><strong>Diaporama:</strong> [Images du diaporama ici, par exemple avec un carrousel ou des vignettes]</p>
            <p class="card-text"><strong>Description:</strong> La description complète de l'événement...</p>
            <p class="card-text"><strong>Organisateur:</strong> Pseudo de l'organisateur</p>
            <p class="card-text"><strong>Visibilité:</strong> Privé/Public</p>
            <button class="btn btn-primary">S'inscrire</button>
            </div>
    </div>
    <?php }?>
</div>
</section>
</main>

<?php

    require_once _ROOTPATH_ . '/templates/footer.php';

?>