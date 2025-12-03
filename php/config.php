<?php
$host = "db";
$user = "root";
$pass = "root";
$db   = "ma_base";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} 
catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
;
