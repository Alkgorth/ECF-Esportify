<?php

    require_once _ROOTPATH_ . '/templates/head.php';
    require_once _ROOTPATH_ . '/templates/header.php';

?>

<main class="container">
  <section class="container justify-content-center align-items-center d-flex">
    <h1 class="m-4">Page Espace Personnel Joueur</h1>
  </section>

  <section class="container row-col text-center justify-content-center align-items-center d-flex">
    <div class="d-grid gap-2 d-md-block mt-2 me-2">
      <a class="btn btn-primary" href="index.php?controller=event&action=mesEvents" role="button">Mes évènements</a>
    </div>

    <div class="d-grid gap-2 d-md-block mt-2 mx-2">
      <a class="btn btn-primary" href="index.php?controller=event&action=createEvent" role="button">Soumettre un évènement</a>
    </div>

    <div class="d-grid gap-2 d-md-block mt-2 ms-2">
      <a class="btn btn-primary" href="#" role="button">Mon historique</a>
    </div>
  </section>

  <section class="container">

    <form class="m-5 p-4 text-white" method="POST" action="index.php?controller=personal&action=espacePersonnel">

      <?php if (empty($error) && isset($_POST['saveUser'])) {?>
        <div class="alert alert-primary" role="alert">
          <?php echo $affichage;?>
        </div>
      <?php }?>

      <?php foreach ($error as $errors) {?>
        <div class="alert alert-danger" role="alert">
          <?php echo $errors;?>
        </div>
      <?php }?>

      <h1 class="text-center pb-4">Espace personnel</h1>

      <!-- Input pour CSRF -->
      <input type="hidden" id="csrfTokenInput" name="csrfToken" value="<?php echo htmlspecialchars($_COOKIE['csrfToken'] ?? ''); ?>">

      <div class="mb-3 text-center">
        <label for="last_name" class="form-label">Nom</label>
        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($_SESSION['user']['last_name'])?>">

      </div>
      <div class="mb-3 text-center">
        <label for="first_name" class="form-label">Prénom</label>
        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($_SESSION['user']['first_name'])?>">
      </div>

      <div class="mb-3 text-center">
        <label for="pseudo" class="form-label">Pseudo</label>
        <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo htmlspecialchars($_SESSION['user']['pseudo'])?>">
      </div>

      <div class="mb-3 text-center">
        <label for="mail" class="form-label">Email</label>
        <input type="email" class="form-control" id="mail" name="mail" value="<?php echo htmlspecialchars($_SESSION['user']['mail'])?>">

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

  </section>

</main>

<?php

    require_once _ROOTPATH_ . '/templates/footer.php';

?>