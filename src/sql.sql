CREATE TABLE utilisateurs (
  nom VARCHAR(30) NOT NULL,
  prenom VARCHAR(30) NOT NULL,
  pseudo VARCHAR(30) NOT NULL,
  mdp VARCHAR(255) NOT NULL,
  status VARCHAR(255) NOT NULL DEFAULT 'Etudiant',
  photo INT NOT NULL DEFAULT 0
);


CREATE TABLE POST (
  id_post VARCHAR(255) PRIMARY KEY,
  pseudo_posteur VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  heure_date_publication DATETIME NOT NULL,
  is_story BOOLEAN DEFAULT 0,
  signalement BOOLEAN DEFAULT 0,
  pseudo_signaleur VARCHAR(255),
  nb_like INT DEFAULT 0
);

CREATE TABLE likes (
  id_post VARCHAR(255) PRIMARY KEY,
  pseudo_likeur VARCHAR(255) NOT NULL
);

CREATE TABLE relations (
  abonnes VARCHAR(255) NOT NULL,
  abonnement VARCHAR(255) NOT NULL
);

CREATE TABLE message (
  pseudo_expediteur VARCHAR(255) NOT NULL,
  pseudo_destinataire VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  heure_date_publication DATETIME NOT NULL
);