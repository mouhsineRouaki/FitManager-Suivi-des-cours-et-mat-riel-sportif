<?php 
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, Useremail, userPassword FROM utilisateur WHERE Useremail = :email");
    $stmt->execute([':email' => $email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['userPassword'])) {
            session_start();
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["email"] = $user["Useremail"];

            header("Location: ../pages/home.php");
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
