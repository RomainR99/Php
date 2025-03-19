<?php
session_start();
require 'config.php'; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $mot_de_passe = $_POST["mot_de_passe"];
    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($mot_de_passe, $user["mot_de_passe"])) {
        // Stocker l'email dans la session et rediriger
        $_SESSION["email"] = $user["email"];
        header("Location: dashboard.php"); // Rediriger vers l'espace utilisateur
        exit;
    } else {
        echo "Identifiants incorrects.";
    }
}
?>
