<?php
$host = "127.0.0.1";
$user = "root";
$pass = "mouhsinerouaki";
$db   = "brief1";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} 
catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
;
