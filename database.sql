CREATE DATABASE IF NOT EXISTS brief1;
USE brief1;

CREATE TABLE utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    Useremail VARCHAR(50) NOT NULL UNIQUE,
    userPassword VARCHAR(100) NOT NULL
);

CREATE TABLE cours (
    cour_id INT PRIMARY KEY AUTO_INCREMENT,
    cour_nom VARCHAR(100) NOT NULL,
    cour_category VARCHAR(100) NOT NULL,
    cour_date DATE NOT NULL,
    cour_heure TIME NOT NULL,
    cour_dure INT NOT NULL,
    nb_participants INT
);

CREATE TABLE cours_utilisateurs (
    cours_utilisateurs_id INT PRIMARY KEY AUTO_INCREMENT,
    id INT,
    cour_id INT,
    FOREIGN KEY (id) REFERENCES utilisateur(id),
    FOREIGN KEY (cour_id) REFERENCES cours(cour_id)
);

CREATE TABLE equipements (
    equipement_id INT PRIMARY KEY AUTO_INCREMENT,
    equipement_nom VARCHAR(50) NOT NULL UNIQUE,
    equipement_type VARCHAR(50) DEFAULT 'cardio',
    equipement_qt INT DEFAULT 0,
    equipement_etat VARCHAR(40) DEFAULT 'bon'
);

CREATE TABLE cours_equipements (
    id_cours_equipement INT PRIMARY KEY AUTO_INCREMENT,
    cour_id INT,
    equipement_id INT,
    FOREIGN KEY (cour_id) REFERENCES cours(cour_id),
    FOREIGN KEY (equipement_id) REFERENCES equipements(equipement_id)
);
