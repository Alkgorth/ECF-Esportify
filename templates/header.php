<?php

use App\Tools\Navigation;
use App\Tools\Security;
use App\Tools\UserValidator;

?>
<header>
    <nav>
        <section>
            <aside>
                <div class="d-flex flex-column flex-shrink-0 bg-light" style="width: 4.5rem">
                    <a href="/" class="d-block p-3 link-dark text-decoration-none" title="Icon-only" data-bs-toggle="tooltip" data-bs-placement="right">
                        <svg class="bi" width="40" height="32">
                            <use xlink:href="#bootstrap" />
                        </svg>
                        <span class="visually-hidden">Icon-only</span>
                    </a>
                    <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
                        <li class="nav-item">
                            <a href="#" class="nav-link active py-3 border-bottom" aria-current="page" title="Home" data-bs-toggle="tooltip" data-bs-placement="right">
                                <svg class="bi" width="24" height="24" role="img" aria-label="Home">
                                    <use xlink:href="#home" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link py-3 border-bottom" title="Dashboard" data-bs-toggle="tooltip" data-bs-placement="right">
                                <svg class="bi" width="24" height="24" role="img" aria-label="Dashboard">
                                    <use xlink:href="#speedometer2" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link py-3 border-bottom" title="Orders" data-bs-toggle="tooltip" data-bs-placement="right">
                                <svg class="bi" width="24" height="24" role="img" aria-label="Orders">
                                    <use xlink:href="#table" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link py-3 border-bottom" title="Products" data-bs-toggle="tooltip" data-bs-placement="right">
                                <svg class="bi" width="24" height="24" role="img" aria-label="Products">
                                    <use xlink:href="#grid" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link py-3 border-bottom" title="Customers" data-bs-toggle="tooltip" data-bs-placement="right">
                                <svg class="bi" width="24" height="24" role="img" aria-label="Customers">
                                    <use xlink:href="#people-circle" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                    <div class="dropdown border-top">
                        <a href="#" class="d-flex align-items-center justify-content-center p-3 link-dark text-decoration-none dropdown-toggle" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="" alt="" width="24" height="24" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser3">
                            <li><a class="dropdown-item" href="#">New project...</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Sign out</a></li>
                        </ul>
                    </div>
                </div>
            </aside>
        </section>
    </nav>

</header>

<div id="nav-latteral" class="nav-latteral">
    <div id="nav-close" class="nav-button">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
        </svg>
    </div>
    <div class="nav-links">
        <div class="nav-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
            </svg>
            Accueil
        </div>
        <div class="nav-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
            </svg>
            Compte
        </div>
        <div class="nav-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />
            </svg>
            Paramètres
        </div>
    </div>
</div>
<div id="nav-wrapper"></div>
<nav>
    <div id="nav-open" class="nav-button">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
        </svg>
    </div>

</nav>