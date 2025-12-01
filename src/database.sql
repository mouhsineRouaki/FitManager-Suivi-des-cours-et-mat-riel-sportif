CREATE DATABASE if not exists brief1 ;

USE brief1;

CREATE TABLE cours(
  cour_id int primary key auto_increment,
  cour_nom varchar(100) not null,
  cour_category varchar(100) not null,
  cour_date date not null,
  cour_heure time not null,
  cour_dure int not null,
  nb_participants int 
);

CREATE TABLE equipements(
    equipement_id int primary key auto_increment,
    equipement_nom varchar(50) NOT NULL UNIQUE,
    equipement_type varchar(50) default "cardio",
    equipement_qt int default 0,
    equipement_etat varchar(40) default "bon"
);

CREATE TABLE user(
    id int primary key auto_increment,
    Useremail varchar(50) not null  unique,
    userPassword varchar(50) not null  unique,
    cour_id int,
    FOREIGN KEY cours(cour_id) REFERENCES cours(cour_id)
);


CREATE TABLE cours_equipements(
    id_cours_equipement int primary key auto_increment,
    cour_id int,
    equipement_id int,
    FOREIGN KEY cours(cour_id) REFERENCES cours(cour_id),
    FOREIGN KEY equipements(equipement_id) REFERENCES equipements(equipement_id)
);