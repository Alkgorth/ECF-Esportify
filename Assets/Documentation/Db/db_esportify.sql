CREATE TABLE USERS(
   id_user INT,
   last_name VARCHAR(255) NOT NULL,
   first_name VARCHAR(255) NOT NULL,
   mail VARCHAR(255) NOT NULL,
   pseudo VARCHAR(124) NOT NULL,
   password VARCHAR(255) NOT NULL,
   role VARCHAR(14) NOT NULL,
   PRIMARY KEY(id_user),
   UNIQUE(mail),
   UNIQUE(pseudo)
);

CREATE TABLE TOKENS(
   id_token INT,
   creation_date DATETIME NOT NULL,
   expiration_date DATETIME NOT NULL,
   token VARCHAR(255) NOT NULL,
   fk_id_user INT NOT NULL,
   PRIMARY KEY(id_token),
   UNIQUE(token)
);

CREATE TABLE FAVORITES(
   id_favorite INT,
   name VARCHAR(255) NOT NULL,
   fk_id_user INT NOT NULL,
   id_user INT,
   PRIMARY KEY(id_favorite),
   FOREIGN KEY(id_user) REFERENCES USERS(id_user)
);

CREATE TABLE PLATEFORME(
   id_plateforme INT,
   name VARCHAR(255) NOT NULL,
   fk_id_event INT NOT NULL,
   PRIMARY KEY(id_plateforme)
);

CREATE TABLE VISIBILITY(
   id_visibility INT,
   name VARCHAR(50) NOT NULL,
   fk_id_event INT NOT NULL,
   PRIMARY KEY(id_visibility)
);

CREATE TABLE EVENTS(
   id_event INT,
   name_event VARCHAR(255) NOT NULL,
   name_game VARCHAR(255) NOT NULL,
   date_hour_start DATETIME NOT NULL,
   date_hour_end DATETIME NOT NULL,
   description TEXT NOT NULL,
   fk_id_user INT NOT NULL,
   id_visibility INT NOT NULL,
   id_user INT NOT NULL,
   PRIMARY KEY(id_event),
   UNIQUE(name_event),
   FOREIGN KEY(id_visibility) REFERENCES VISIBILITY(id_visibility),
   FOREIGN KEY(id_user) REFERENCES USERS(id_user)
);

CREATE TABLE PASSWORD(
   id_user INT,
   id_token INT,
   PRIMARY KEY(id_user, id_token),
   FOREIGN KEY(id_user) REFERENCES USERS(id_user),
   FOREIGN KEY(id_token) REFERENCES TOKENS(id_token)
);

CREATE TABLE SPECIFICATION(
   id_event INT,
   id_plateforme INT,
   PRIMARY KEY(id_event, id_plateforme),
   FOREIGN KEY(id_event) REFERENCES EVENTS(id_event),
   FOREIGN KEY(id_plateforme) REFERENCES PLATEFORME(id_plateforme)
);
