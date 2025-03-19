<?php
require 'config.php'; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $mot_de_passe = $_POST["mot_de_passe"];
    $confirmation_mot_de_passe = $_POST["confirmation_mot_de_passe"];
    // Vérifier si les mots de passe correspondent
    if ($mot_de_passe !== $confirmation_mot_de_passe) {
        die("Les mots de passe ne correspondent pas.");
    }
    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        die("Cet email est déjà utilisé.");
    }
    // Hasher le mot de passe avant de le stocker
    $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);
    // Insérer l'utilisateur dans la base de données
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe) VALUES (?, ?)");
    $stmt->execute([$email, $mot_de_passe_hash]);
    echo "Inscription réussie ! <a href='connexion.html'>Se connecter</a>";
}
?>
