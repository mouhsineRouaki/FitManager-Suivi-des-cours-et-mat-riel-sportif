<?php
require_once "../config/database.php";
require_once "./functionsCour.php";

if(isset($_GET['id'])){
    $id = $_GET['id'];

    if(supprimerCour($id)){
        header("Location: ../pages/pageCours.php");
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "ID non fourni.";
}
