<?php 
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO utilisateur (Useremail, userPassword) VALUES (:email, :password)");
    if ($stmt->execute([
        ':email' => $email,
        ':password' => $hashedPassword
    ])) {
        echo "Votre compte a été créé avec succès !";
    } else {
        echo "Erreur SQL : " . implode(" ", $stmt->errorInfo());
    }
}
?>
