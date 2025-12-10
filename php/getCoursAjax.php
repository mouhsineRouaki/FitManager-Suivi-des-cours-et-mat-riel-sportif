<?php
require_once "../config/database.php";
require_once "../php/functionsCour.php";

// Récupérer la catégorie depuis l'URL (GET)
$category = $_GET['category'] ?? '';

// Appeler la fonction pour afficher les cours filtrés
getCoursParCategory($category);
?>
