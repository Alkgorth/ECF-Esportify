<?php

use App\Tools\Navigation;
use App\Tools\Security;
use App\Tools\UserValidator;

?>
<header>

<div class="d-flex flex-column flex-shrink-0 bg-light" style="width: 4.5rem;">
    <a href="/" class="d-block p-3 link-dark text-decoration-none" title="Icon-only" data-bs-toggle="tooltip" data-bs-placement="right">
      <svg class="bi" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
      <span class="visually-hidden">Icon-only</span>
    </a>
    <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
      <li class="nav-item">
        <a href="#" class="nav-link active py-3 border-bottom" aria-current="page" title="Home" data-bs-toggle="tooltip" data-bs-placement="right">
          <svg class="bi" width="24" height="24" role="img" aria-label="Home"><use xlink:href="#home"/></svg>
        </a>
      </li>
      <li>
        <a href="#" class="nav-link py-3 border-bottom" title="Dashboard" data-bs-toggle="tooltip" data-bs-placement="right">
          <svg class="bi" width="24" height="24" role="img" aria-label="Dashboard"><use xlink:href="#speedometer2"/></svg>
        </a>
      </li>
      <li>
        <a href="#" class="nav-link py-3 border-bottom" title="Orders" data-bs-toggle="tooltip" data-bs-placement="right">
          <svg class="bi" width="24" height="24" role="img" aria-label="Orders"><use xlink:href="#table"/></svg>
        </a>
      </li>
      <li>
        <a href="#" class="nav-link py-3 border-bottom" title="Products" data-bs-toggle="tooltip" data-bs-placement="right">
          <svg class="bi" width="24" height="24" role="img" aria-label="Products"><use xlink:href="#grid"/></svg>
        </a>
      </li>
      <li>
        <a href="#" class="nav-link py-3 border-bottom" title="Customers" data-bs-toggle="tooltip" data-bs-placement="right">
          <svg class="bi" width="24" height="24" role="img" aria-label="Customers"><use xlink:href="#people-circle"/></svg>
        </a>
      </li>
    </ul>
    <div class="dropdown border-top">
      <a href="#" class="d-flex align-items-center justify-content-center p-3 link-dark text-decoration-none dropdown-toggle" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://github.com/mdo.png" alt="mdo" width="24" height="24" class="rounded-circle">
      </a>
      <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser3">
        <li><a class="dropdown-item" href="#">New project...</a></li>
        <li><a class="dropdown-item" href="#">Settings</a></li>
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#">Sign out</a></li>
      </ul>
    </div>
  </div>

  <nav class="navbar navbar-expand-lg bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="../index.php">
        <img src="..\assets\images\Logo GameStore-2.png" alt="Logo" class="align-text-top ms-2" width=70>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse ms-auto justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php?controller=pages&action=panier"><i class="bi bi-cart-plus fs-2 px-4"></i></a>
          </li>
          <li class="nav-item dropdown">
            <?php if (UserValidator::isUser()) { ?>
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-2"></i></a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="index.php?controller=pages&action=espacePersonnel">Profil</a></li>
                <li><a class="dropdown-item" href="index.php?controller=pages&action=panier">Panier</a></li>
                <li><a class="dropdown-item" href="index.php?controller=connexions&action=deconnexion">Déconnexion</a></li>
              </ul>
              <?php } elseif (UserValidator::isAdmin()) { ?>
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-2"></i></a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="index.php?controller=admin&action=adminAccueil">Accueil administrateur</a></li>
                <li><a class="dropdown-item" href="index.php?controller=admin&action=adminCreationArticle">Création d'un article</a></li>
                <li><a class="dropdown-item" href="index.php?controller=admin&action=gestionStocks">Gestion des stocks</a></li>
                <li><a class="dropdown-item" href="index.php?controller=admin&action=creationEmploye">Création d'un employé</a></li>
                <li><a class="dropdown-item" href="index.php?controller=connexions&action=deconnexion">Déconnexion</a></li>
              </ul>
            <?php } else { ?>
            <a href="/index.php?controller=connexions&action=connexion" class="btn btn-outline-secondary me-2 mx-2 
                <?= Navigation::addActiveClass('connexions', 'connexion') ?>">Connexion</a>
            <a href="/index.php?controller=pages&action=creationCompte" class="btn btn-outline-secondary me-2 mx-2 
                <?= Navigation::addActiveClass('pages', 'creationCompte') ?>">Inscription</a>
            <?php } ?>
          </li>

          <li class="nav-item dropdown">
           
          </li>

          <li class="nav-item">
            <form class="d-flex nav-link" role="search">
              <input class="form-control me-2" type="search" placeholder="Rechercher" aria-label="Search">
              <button class="btn btn-outline-dark" type="submit">Rechercher</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>

</header>