<?php
require_once "../config/database.php";
require_once "../php/functionsCour.php";

$category = $_GET['category'] ?? '';

getCoursParCategoryHome($category);
?>
