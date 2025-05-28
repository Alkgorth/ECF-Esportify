USE esportify_bdd;

CREATE TABLE `user`(
   `id_user` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
   `last_name` varchar(255) NOT NULL,
   `first_name` varchar(255) NOT NULL,
   `mail` varchar(255) NOT NULL UNIQUE,
   `pseudo` varchar(124) NOT NULL UNIQUE,
   `password` varchar(255) NOT NULL,
   `role` ENUM('joueur','organisateur', 'administrateur') NOT NULL DEFAULT 'joueur'
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tokens`(
   `id_token` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
   `creation_date` datetime DEFAULT CURRENT_TIMESTAMP,
   `expiration_date` datetime NOT NULL,
   `token` VARCHAR(255) NOT NULL UNIQUE,
   `fk_id_user` int(11) UNSIGNED NOT NULL,
   FOREIGN KEY(fk_id_user) REFERENCES user(id_user) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `plateforme`(
   `id_plateforme` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
   `name` varchar(255) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `event`(
   `id_event` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
   `name_event` varchar(255) NOT NULL,
   `name_game` varchar(255) NOT NULL,
   `date_hour_start` datetime NOT NULL,
   `date_hour_end` datetime NOT NULL,
   `nombre_de_joueurs` int(11) UNSIGNED NOT NULL,
   `description` text NOT NULL,
   `visibility` ENUM('public', 'privé') NOT NULL DEFAULT 'public',
   `status` ENUM ('en attente', 'validé', 'refusé', 'annulé') NOT NULL DEFAULT 'en attente',
   `fk_id_user` int(11) UNSIGNED NOT NULL,
   `fk_id_plateforme` int(11) UNSIGNED NOT NULL,
    `cover_image_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
   FOREIGN KEY(fk_id_user) REFERENCES user(id_user) ON DELETE CASCADE ON UPDATE CASCADE,
   FOREIGN KEY(fk_id_plateforme) REFERENCES plateforme(id_plateforme) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `event_image` (
  `id_event_image` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `fk_id_event` int(11) UNSIGNED NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `image_order` int UNSIGNED NOT NULL,
  FOREIGN KEY(fk_id_event) REFERENCES event(id_event)ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `favorite`(
   `id_favorite` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
   `fk_id_user` INT(11) UNSIGNED NOT NULL,
   `fk_id_event` INT(11) UNSIGNED NOT NULL,
   UNIQUE KEY (fk_id_user, fk_id_event),
   FOREIGN KEY (fk_id_user) REFERENCES user(id_user) ON DELETE CASCADE ON UPDATE CASCADE,
   FOREIGN KEY (fk_id_event) REFERENCES event(id_event) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `scores`(
   `fk_id_user` int(11) UNSIGNED NOT NULL,
   `fk_id_event` int(11) UNSIGNED NOT NULL,
   PRIMARY KEY(fk_id_user, fk_id_event),
   `score` int(11) UNSIGNED NOT NULL,
   `score_total` decimal(10,2) UNSIGNED NOT NULL CHECK(`score_total` >= 0),
   FOREIGN KEY(fk_id_user) REFERENCES user(id_user),
   FOREIGN KEY(fk_id_event) REFERENCES event(id_event)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE INDEX idx_user ON user (mail, pseudo);
CREATE INDEX idx_event_user ON event (fk_id_user);
CREATE INDEX idx_event_visibility ON event (visibility);
CREATE INDEX idx_event_date_start ON event (date_hour_start);
CREATE INDEX idx_score_event ON scores (fk_id_event);
CREATE UNIQUE INDEX idx_token_token ON tokens (token);