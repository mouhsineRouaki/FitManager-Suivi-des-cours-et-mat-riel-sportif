<?php
session_start();
require_once "../php/check_connecter.php";
require_once "../config/database.php";
require_once "./functionsCour.php";
function getUserById($id){
    global $conn;
    $stmt = $conn->prepare("select * from utilisateur where user_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function updateSoldUser($id,$newSold){
    global $conn;
    $stmt = $conn ->prepare("update utilisateur set user_sold = ? where user_id = ?");
    return $stmt->execute([$newSold,$id]);
}



$user_id = $_SESSION['user_id'];


$cour_id = (int) $_GET['id_cour'];
$cour = getCourById($cour_id);
$user = getUserById($user_id);


$stmtDelete = $conn->prepare("delete from cours_utilisateurs where cour_id = ? and user_id = ?");

if ($stmtDelete->execute([$cour_id,$user_id])) {
    $newSold = $user["user_sold"] + $cour["cour_prix"];
    updateSoldUser($user_id, $newSold);

    header("Location: ../pages/pageHome.php");
} else {
    header("Location: pageDetailsCour.php?id=$cour_id");
    exit;
}
