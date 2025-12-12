<?php 
require_once "../php/check_connecter.php";
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["user_email"];
    $password = $_POST["user_password"];
    $username = $_POST["user_name"];
    $userimage = $_POST["user_image"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO utilisateur (user_name, user_image,user_email,user_password) VALUES (?,?,?,?)");
    if ($stmt->execute([$username,$userimage,$email,$hashedPassword])) {
        echo "Votre compte a été créé avec succès !";
    } else {
        echo "Erreur SQL : ". $stmt->errorInfo();
    }
}
?>
