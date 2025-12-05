CREATE DATABASE IF NOT EXISTS brief1;
USE brief1;

CREATE TABLE utilisateur (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username varchar(50) NOT NULL UNIQUE,
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
    nb_participants INT,
    id_user,
    FOREIGN KEY id_user REFERENCES  utilisateur(id)
);

CREATE TABLE cours_utilisateurs (
    id INT PRIMARY KEY,
    cour_id INT PRIMARY KEY,
    FOREIGN KEY (id) REFERENCES utilisateur(id),
    FOREIGN KEY (cour_id) REFERENCES cours(cour_id)
);

CREATE TABLE equipements (
    equipement_id INT PRIMARY KEY AUTO_INCREMENT,
    equipement_nom VARCHAR(50) NOT NULL UNIQUE,
    equipement_type VARCHAR(50) ENUM("bon" , "moyenne", "faible" ) DEFAULT "bon",
    equipement_qt INT DEFAULT 0,
    equipement_etat VARCHAR(40) DEFAULT 'bon'
);

CREATE TABLE cours_equipements (
    cour_id INT PRIMARY KEY,
    equipement_id INT PRIMARY KEY,
    FOREIGN KEY (cour_id) REFERENCES cours(cour_id),
    FOREIGN KEY (equipement_id) REFERENCES equipements(equipement_id)
);



/*  exemple selection */
SELECT * FROM cours where cour_category=? limit 3; 
/*  exemple insertion */
INSERT INTO cours (cour_nom, cour_category, cour_date, cour_heure, cour_dure, nb_participants)VALUES (?, ?, ?, ?, ?, ?)
/*  exemple update */
UPDATE cours SET cour_nom = ?, cour_category = ?, cour_date = ?,  cour_heure = ?, cour_dure = ?, nb_participants = ? WHERE cour_id = ?;
/*  exemple jointure */
SELECT e.* FROM equipements e INNER JOIN cours_equipements ce ON ce.equipement_id = e.equipement_id WHERE ce.cour_id = ? ORDER BY e.equipement_nom
