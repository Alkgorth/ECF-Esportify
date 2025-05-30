<?php

    require_once _ROOTPATH_ . '/templates/head.php';

?>

<h2>Page Mot de passe réinitialisé</h2>
    <div class="container-fluid px-5 mt-5 row d-flex justify-content-center">
        <a href="../index.php" class="text-center">
            <img src="" alt="Logo Esportify" class="pb-4" width="400">
        </a>

        <div class="container border text-center m-4">
            <p>Votre mot de passe à bien été réinitialisé</p>
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-warning mb-4" name="retourConnexion">
                <a href="index.php?controller=auth&action=connexion" class="liens">Retour à la page de connexion</a>
            </button>
        </div>
    </div>
</body>

</html>
