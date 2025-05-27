<?php

require_once _ROOTPATH_ . '\templates\head.php';
require_once _ROOTPATH_ . '\templates\header.php';

?>

<main class="container">
  <section>
    <h1>Page Espace Personnel Admin</h1>
  </section>

  <form class="m-5 p-4 text-white" method="POST" action="index.php?controller=personal&action=espacePersonnelAdmin">

    <?php if (empty($error) && isset($_POST['saveUser'])) { ?>
      <div class="alert alert-primary" role="alert">
        <?= $affichage; ?>
      </div>
    <?php } ?>

    <?php foreach ($error as $errors) { ?>
      <div class="alert alert-danger" role="alert">
        <?= $errors; ?>
      </div>
    <?php } ?>

    <h1 class="text-center pb-4">Espace personnel</h1>
    <div class="mb-3 text-center">
      <label for="last_name" class="form-label">Nom</label>
      <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($_SESSION['user']['last_name']) ?>">
    </div>

    <div class="mb-3 text-center">
      <label for="first_name" class="form-label">Prénom</label>
      <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($_SESSION['user']['first_name']) ?>">
    </div>

    <div class="mb-3 text-center">
      <label for="pseudo" class="form-label">Pseudo</label>
      <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?= htmlspecialchars($_SESSION['user']['pseudo']) ?>">
    </div>

    <div class="mb-3 text-center">
      <label for="mail" class="form-label">Email</label>
      <input type="email" class="form-control" id="mail" name="mail" value="<?= htmlspecialchars($_SESSION['user']['mail']) ?>">
    </div>

    <div class="mb-3 text-center">
      <label for="password" class="form-label">Mot de passe</label>
      <input type="password" class="form-control" id="password" name="password" required minlength="12">
    </div>

    <div class="mb-3 text-center">
      <label for="passwordConfirm" class="form-label">Confirmer mot de passe</label>
      <input type="password" class="form-control" id="passwordConfirm" name="passwordConfirm" required minlength="12">
    </div>

    <div class="text-center">
      <button type="submit" name="saveUser" class="btn btn-warning m-4">Valider</button>
    </div>
    
    <div class="text-center">
      <button type="submit" name="delete" class="btn btn-dark m-4">Supprimer mon compte</button>
    </div>

  </form>

</main>

<?php

require_once _ROOTPATH_ . '\templates\footer.php';

?>