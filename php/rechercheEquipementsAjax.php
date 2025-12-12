<?php
require_once "../php/check_connecter.php";
require_once "../config/database.php";
require_once "../php/functionsEquipements.php";

$query = $_GET['query'] ?? '';

rechercheEquipement($query);
?>
