<?php
require_once "../config/database.php";
require_once "./functionsEquipements.php";

if(isset($_GET['id'])){
    $id = $_GET['id'];

    if(supprimerEquipement($id)){
        header("Location: ../pages/pageEquipements.php");
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "ID non fourni.";
}
