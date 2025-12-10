<?php
require_once "config.php";
require_once "allFunction.php";

if(isset($_GET['id'])){
    $id = $_GET['id'];

    if(supprimerCour($id)){
        header("Location: ../pages/cours.php");
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "ID non fourni.";
}
