CREATE DATABASE fitmanagerProject;
USE fitmanagerProject;

CREATE TABLE utilisateur (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(50) NOT NULL UNIQUE,
    user_image VARCHAR(200) NOT NULL,
    user_email VARCHAR(50) NOT NULL UNIQUE,
    user_password VARCHAR(100) NOT NULL,
    user_sold DECIMAL(10,2) DEFAULT 0.00
);

CREATE TABLE cours (
    cour_id INT PRIMARY KEY AUTO_INCREMENT,
    cour_nom VARCHAR(100) NOT NULL,
    cour_category VARCHAR(100) NOT NULL,
    cour_description TEXT,
    cour_date DATE NOT NULL,
    cour_heure TIME NOT NULL,
    cour_dure INT NOT NULL,
    nb_participants INT,
    cour_image VARCHAR(200),
    cour_prix DECIMAL(10,2)
);

CREATE TABLE cours_utilisateurs (
    user_id INT,
    cour_id INT,
    PRIMARY KEY (user_id, cour_id),
    FOREIGN KEY (user_id) REFERENCES utilisateur(user_id),
    FOREIGN KEY (cour_id) REFERENCES cours(cour_id)
);

CREATE TABLE equipements (
    equipement_id INT PRIMARY KEY AUTO_INCREMENT,
    equipement_nom VARCHAR(50) NOT NULL UNIQUE,
    equipement_type VARCHAR(50),
    equipement_qt INT DEFAULT 0,
    equipement_etat ENUM('bon', 'moyenne', 'faible') DEFAULT 'moyenne',
    equipement_image VARCHAR(200)
);

CREATE TABLE cours_equipements (
    cour_id INT,
    equipement_id INT,
    PRIMARY KEY (cour_id, equipement_id),
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
