<?php
require_once "../php/check_connecter.php";
require_once "../config/database.php";
require_once "../php/functionsCour.php";

$query = $_GET['query'] ?? '';

rechercheCour($query);
?>
