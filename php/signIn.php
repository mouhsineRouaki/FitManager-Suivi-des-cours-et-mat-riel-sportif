<?php 
require_once "../php/check_connecter.php";
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT user_id, user_email, user_password FROM utilisateur WHERE user_email = :email");
    $stmt->execute([':email' => $email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['user_password'])) {
            session_start();
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["email"] = $user["user_email"];

            header("Location: ../pages/pageHome.php");
            exit;
        } 
        else {
            echo "Mot de passe incorrect";
        }
    } 
    else {
        echo "Email introuvable";
    }
}
?>
