<?php
$host = "db";
$user = "mouhsine";
$pass = "mouhsinerouaki";
$db   = "fitmanagerProject";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} 
catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
};
