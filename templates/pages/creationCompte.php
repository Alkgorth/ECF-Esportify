<?php

require_once _ROOTPATH_ . '\templates\head.php';
require_once _ROOTPATH_ . '\templates\header.php';



?>

<main class="container mx-5">
    <h1>Page Création Compte</h1>
    <form class="m-5 p-4 text-white" method="POST">
        <h1 class="text-center pb-4">Créer mon compte</h1>
        <div class="mb-3 text-center">
            <label for="last_name" class="form-label">Nom</label>
            <input type="text" class="form-control <?= (isset($errors['last_name']) ? 'is-invalid' : '') ?>" id="last_name" name="last_name" required>
            <?php if (isset($errors['last_name'])) { ?>
                <div class="invalid-feedback"><?= $errors['last_name'] ?></div>
            <?php } ?>
        </div>
        <div class="mb-3 text-center">
            <label for="first_name" class="form-label">Prénom</label>
            <input type="text" class="form-control <?= (isset($errors['first_name']) ? 'is-invalid' : '') ?>" id="first_name" name="first_name" required>
            <?php if (isset($errors['first_name'])) { ?>
                <div class="invalid-feedback"><?= $errors['first_name'] ?></div>
            <?php } ?>
        </div>
        <div class="mb-3 text-center">
            <label for="mail" class="form-label">Email</label>
            <input type="email" class="form-control <?= (isset($errors['mail']) ? 'is-invalid' : '') ?>" id="mail" name="mail" required>
            <?php if (isset($errors['mail'])) { ?>
                <div class="invalid-feedback"><?= $errors['mail'] ?></div>
            <?php } ?>
        </div>
        <div class="mb-3 text-center">
            <label for="pseudo" class="form-label">Pseudo</label>
            <input type="text" class="form-control <?= (isset($errors['pseudo']) ? 'is-invalid' : '') ?>" id="pseudo" name="pseudo" required>
            <?php if (isset($errors['pseudo'])) { ?>
                <div class="invalid-feedback"><?= $errors['pseudo'] ?></div>
            <?php } ?>
        </div>
        <div class="mb-3 text-center">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control <?= (isset($errors['password']) ? 'is-invalid' : '') ?>" id="password" name="password" required minlength="12">
            <?php if (isset($errors['password'])) { ?>
                <div class="invalid-feedback"><?= $errors['password'] ?></div>
            <?php } ?>
        </div>
        <div class="mb-3 text-center">
            <label for="passwordConfirm" class="form-label">Confirmer mot de passe</label>
            <input type="password" class="form-control <?= (isset($errors['passwordConfirm']) ? 'is-invalid' : '') ?>" id="passwordConfirm" name="passwordConfirm" required minlength="12">
            <?php if (isset($errors['passwordConfirm'])) { ?>
                <div class="invalid-feedback"><?= $errors['passwordConfirm'] ?></div>
            <?php } ?>
        </div>
       

            <div class="text-center">
                <button type="submit" name="saveUser" class="btn btn-warning m-4">Valider</button>
            </div>

    </form>
</main>

<?php

require_once _ROOTPATH_ . '\templates\footer.php';


?>