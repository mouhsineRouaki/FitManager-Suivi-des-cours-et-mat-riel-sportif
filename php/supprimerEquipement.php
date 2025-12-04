<?php
require_once "config.php";
require_once "allFunction.php";

if(isset($_GET['id'])){
    $id = $_GET['id'];

    if(supprimerEquipement($id)){
        header("Location: ../pages/equipements.php");
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "ID non fourni.";
}
