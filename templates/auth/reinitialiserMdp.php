<?php

    require_once _ROOTPATH_ . '/templates/head.php';

?>

<div class="container-fluid px-5 mt-5 row d-flex justify-content-center">
    <h1 class="text-center pb-4">Réinitialiser mot de passe</h1>
    <a href="../index.php" class="text-center">
        <img src="" alt="Logo Esportify" class="pb-4" width="400">
    </a>

    <form method="POST" class="col-sm-6 mb-3 mx-auto justify-content-center">
        <div class="mb-3 text-center">
            <label for="password" class="form-label text-white">Nouveau mot de passe</label>
            <input type="password" class="form-control" <?php echo (isset($errors['password']) ? 'is-invalid' : '')?> id="password" name="password">
            <?php if (isset($errors['password'])) {?>
                <div class="invalid-feedback"><?php echo $errors['password']?></div>
            <?php }?>
        </div>
        <div class="mb-3 text-center">
            <label for="passwordConfirm" class="form-label text-white">Confirmer mot de passe</label>
            <input type="password" class="form-control" <?php echo (isset($errors['passwordConfirm']) ? 'is-invalid' : '')?> id="passwordConfirm" name="passwordConfirm">
            <?php if (isset($errors['passwordConfirm'])) {?>
                <div class="invalid-feedback"><?php echo $errors['passwordConfirm']?></div>
            <?php }?>
        </div>

        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-warning mb-4" name="resetPassword">Valider</button>
        </div>
    </form>
</div>
</body>
</html>