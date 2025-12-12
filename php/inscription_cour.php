<?php
session_start();
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
    return $stmt->execute([ $newSold,$id]);
}





if (!isset($_GET['id'])) {
    die("Cours introuvable.");
}

$user_id = $_SESSION['user_id'];


$cour_id = (int) $_GET['id'];

$user = getUserById($_SESSION["user_id"]);
$cour = getCourById($cour_id);
if($user["user_sold"] >= $cour["cour_prix"]){
    $newSold = $user["user_sold"] - $cour["cour_prix"];
    updateSoldUser($user["user_id"] ,$newSold );

}else{
    die("sold ne pas suffesion");
}

$sqlInsert = "INSERT INTO cours_utilisateurs (user_id, cour_id) VALUES (:user_id, :cour_id)";
$stmtInsert = $conn->prepare($sqlInsert);

if ($stmtInsert->execute([
    ':user_id' => $user_id,
    ':cour_id' => $cour_id
])) {
    header("Location: ../pages/pageDetailsCour.php?id=$cour_id");
    exit;
} else {
    header("Location: ../pages/pageDetailsCour.php?id=$cour_id");
    exit;
}
