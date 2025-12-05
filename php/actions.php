<?php
require_once "cours_functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ajout d'un cours
    if (isset($_POST["ajoutCour"])) {
        $nom          = $_POST["nomCour"];
        $categorieCour= $_POST["categorieCour"];
        $dateCour     = $_POST["dateCour"];
        $heureCour    = $_POST["heureCour"];
        $dureeCour    = $_POST["dureeCour"];
        $maxCour      = $_POST["maxCour"];

        if (ajouterCour($nom, $categorieCour, $dateCour, $heureCour, $dureeCour, $maxCour)) {
            header("Location: ../pages/cours.php");
            exit;
        } else {
            echo "Erreur lors de l'ajout du cours.";
        }
    }

    // Modification d'un cours
    if (isset($_POST["modifierCour"])) {
        $id           = $_POST["idCour"];
        $nom          = $_POST["nomCour"];
        $categorieCour= $_POST["categorieCour"];
        $dateCour     = $_POST["dateCour"];
        $heureCour    = $_POST["heureCour"];
        $dureeCour    = $_POST["dureeCour"];
        $maxCour      = $_POST["maxCour"];

        if (modifierCour($id, $nom, $categorieCour, $dateCour, $heureCour, $dureeCour, $maxCour)) {
            header("Location: ../pages/cours.php");
            exit;
        } else {
            echo "Erreur lors de la modification du cours.";
        }
    }

    // Ajouter un équipement à un cours (depuis cours_detail.php)
    if (isset($_POST["ajouterEquipementCours"])) {
        $courId       = (int)$_POST["cour_id"];
        $equipementId = (int)$_POST["equipement_id"];

        if ($courId > 0 && $equipementId > 0 && ajouterEquipementAuCour($courId, $equipementId)) {
            header("Location: ../pages/cours_detail.php?id=".$courId);
            exit;
        } else {
            echo "Erreur lors de l'ajout de l'équipement au cours.";
        }
    }
}

// Suppression d'un cours via GET (optionnel, si tu veux l'utiliser)
if (isset($_GET['action']) && $_GET['action'] === 'supprimer' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    supprimerCour($id);
    header("Location: ../pages/cours.php");
    exit;
}