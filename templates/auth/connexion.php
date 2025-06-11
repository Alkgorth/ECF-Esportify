<?php

    require_once _ROOTPATH_ . '/templates/head.php';

?>

<div class="container-fluid px-5 mt-4 row d-flex justify-content-center">
    <h1 class="text-center pb-2">Connexion</h1>
    <a href="../index.php" class="text-center ">
        <img src="../../Assets/Documentation/Images/Logo-Bannière/Logo-02.png" alt="Logo Esportify" class="mb-4" width="400">
    </a>

    <?php foreach ($error as $errors) {?>
        <div class="alert alert-danger" role="alert">
            <?php echo $errors;?>
        </div>
    <?php }?>

    <form method="POST" class="col-sm-6 mb-3 mx-auto justify-content-center">

        <!-- Input pour CSRF -->
        <input type="hidden" name="csrf_token" value="<?php echo $_COOKIE['csrf_token']; ?>">

        <div class="mb-2 text-center">
            <label for="mail" class="form-label texte-input">Adresse mail</label>
            <input type="email" class="form-control input-form" id="mail" aria-describedby="emailHelp" name="mail">
            <div id="emailHelp" class="form-text">Nous ne partagerons jamais votre e-mail avec quelqu'un d'autre.</div>
        </div>
        <div class="mb-2 text-center">
            <label for="password" class="form-label texte-input">Mot de passe</label>
            <input type="password" class="form-control input-form" id="password" name="password">
        </div>

        <div class="d-flex justify-content-center p-2">
            <button type="submit" class="btn bouton-valider mb-2 me-4" name="connexion">Connexion</button>
            <button class="btn bouton-annuler mb-3 ms-4">
                    <a href="index.php?controller=pages&action=creationCompte" class="texte-bouton">Créer un compte</a>
            </button>
        </div>

        <div>
            <div class="text-center">
                <button class="mb-3 btn btn-dark text-center bouton-supprimer">
                    <a href="index.php?controller=auth&action=mdpOublie" class="texte-bouton">Mot de passe oublié</a>
                </button>
            </div>
        </div>
    </form>
</div>