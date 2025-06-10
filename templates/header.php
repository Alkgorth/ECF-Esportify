<?php

    use App\Tools\UserValidator;

?>
<header>

    <nav class="navbar barreNav z-3" id="navbar">
        <ul class="navbar-nav flex-shrink-0 list-unstyled ps-0 flex-grow-1 d-flex flex-column h-100">
            <li class="nav-item liItem logo">
                <a href="/index.php?controller=pages&action=home" class="nav-link d-flex ps-1 ms-1  border-bottom myLink">
                    <span class="logo-text">Esportify</span>
                    <img src="../Assets/Documentation/Images/Logo-Bannière/Logo-02.png" alt="Logo Esportify" class="pe-2">
                </a>
            </li>
            
            <li class="mb-1 ms-1 dropdown nav-item liItem">
                <a href="#" class="nav-link dropdown-toggle myLink" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-calendar2-week">
                        <use href="../node_modules/bootstrap-icons/bootstrap-icons.svg#calendar2-week" class="color-second"></use>
                    </svg>
                    <span class="nav-text mySpan">Événements</span>
                </a>
                <ul class="list-unstyled fw-normal pb-1 small dropdown-menu">
                    <li><a href="/index.php?controller=event&action=eventGeneral" class="dropdown-item">Tous les événements</a></li>
                    <li><a href="#" class="dropdown-item">À venir</a></li>
                    <li><a href="#" class="dropdown-item">En cours</a></li>
                </ul>
            </li>
            <?php if (UserValidator::isJoueur()) {?>
                <li class="mb-1 ms-1 dropdown nav-item liItem">
                    <a href="#" class="nav-link dropdown-toggle myLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-person">
                            <path class="color-first color-second" d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                        </svg>
                        <span class="nav-text mySpan">Espace personnel</span>
                    </a>
                    <ul class="list-unstyled fw-normal pb-1 small dropdown-menu">
                        <li><a href="/index.php?controller=personal&action=espacePersonnel" class="dropdown-item">Données personnelles</a></li>
                        <li><a href="index.php?controller=event&action=mesEvents" class="dropdown-item">Mes événements</a></li>
                        <li><a href="#" class="dropdown-item">Mon historique</a></li>
                    </ul>
                </li>

                <li class="mb-1 ms-1 nav-item liItem">
                    <a href="/index.php?controller=auth&action=deconnexion" class="nav-link myLink" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-box-arrow-left">
                            <path class="color-first color-second" fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                            <path class="color-first color-second" fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                        </svg>
                        <span class="nav-text mySpan">Déconnexion</span>
                    </a>
                </li>
            <?php } elseif (UserValidator::isOrga()) {?>
                <li class="mb-1 ms-1 dropdown nav-item liItem">
                    <a href="#" class="nav-link dropdown-toggle myLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-person">
                            <path class="color-first color-second" d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                        </svg>
                        <span class="nav-text mySpan">Espace personnel</span>
                    </a>
                    <ul class="list-unstyled fw-normal pb-1 small dropdown-menu">
                        <li><a href="/index.php?controller=personal&action=espacePersonnelOrga" class="dropdown-item">Données personnelles</a></li>
                        <li><a href="index.php?controller=event&action=mesEvents" class="dropdown-item">Mes événements</a></li>
                        <li><a href="#" class="dropdown-item">Mon historique</a></li>
                    </ul>
                </li>

                <li class="mb-1 ms-1 nav-item liItem">
                    <a href="/index.php?controller=auth&action=deconnexion" class="nav-link myLink" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-box-arrow-left">
                            <path class="color-first color-second" fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                            <path class="color-first color-second" fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                        </svg>
                        <span class="nav-text mySpan">Déconnexion</span>
                    </a>
                </li>
                <?php } elseif (UserValidator::isAdmin()) {?>

                    <li class="mb-1 ms-1 dropdown nav-item liItem">
                    <a href="#" class="nav-link  dropdown-toggle myLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-person">
                            <path class="color-first color-second" d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                        </svg>
                        <span class="nav-text mySpan">Espace personnel</span>
                    </a>
                    <ul class="list-unstyled fw-normal pb-1 small dropdown-menu">
                        <li><a href="/index.php?controller=personal&action=espacePersonnelAdmin" class="dropdown-item">Données personnelles</a></li>
                        <li><a href="#" class="dropdown-item">Mes événements</a></li>
                        <li><a href="#" class="dropdown-item">Mon historique</a></li>
                    </ul>
                </li>

                <li class="mb-1 ms-1 nav-item liItem">
                    <a href="/index.php?controller=auth&action=deconnexion" class="nav-link myLink" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-box-arrow-left">
                            <path class="color-first color-second" fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                            <path class="color-first color-second" fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                        </svg>
                        <span class="nav-text mySpan">Déconnexion</span>
                    </a>
                </li>
                <?php } else { ?>

                <li class="border-top my-3"></li>
                <li class="mb-1 ms-1 nav-item liItem">
                    <a href="/index.php?controller=pages&action=creationCompte" class="nav-link myLink" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-person-add">
                            <path class="color-first color-second" d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                            <path class="color-first color-second" d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z" />
                        </svg>
                        <span class="nav-text mySpan">Inscription</span>
                    </a>
                </li>
                <li class="mb-1 ms-1 nav-item liItem">
                    <a href="/index.php?controller=auth&action=connexion" class="nav-link myLink" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                            <path class="color-first color-second" fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z" />
                            <path class="color-first color-second" fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                        </svg>
                        <span class="nav-text mySpan">Connexion</span>
                    </a>
                </li>
                <?php } ?>
                
                <li class="border-top my-3"></li>

                <li class="mb-1 ms-1 nav-item liItem">
                    <a href="#" class="nav-link myLink" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" fill="currentColor" viewBox="0 0 16 16" class="bi bi-question-circle">
                            <path class="color-first color-second" d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                            <path class="color-first color-second" d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94" />
                        </svg>
                        <span class="nav-text mySpan">Aide</span>
                    </a>
                </li>
        </ul>
    </nav>
    <ul>
        <li class="nav-burger myBurger" id="nav-burger">
            <a href="#" class="nav-link d-flex ps-3 myLink" aria-expanded="false">
                <span class="logo-vert">Esportify</span>
                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1.5em" height="1.5em" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <use href="../node_modules/bootstrap-icons/bootstrap-icons.svg#list" class="color-second"></use>
                </svg>
            </a>
        </li>
    </ul>
</header>