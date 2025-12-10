<?php
require_once "../config/database.php";
require_once "../php/functionsEquipements.php";

$etat = $_GET['etat'] ?? '';

getEquipementParEtat($etat);
?>
