<?php
session_start();

// Simuler un utilisateur connecté (remplace par un vrai système de login)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Exemple d'ID utilisateur
}

echo "<h1>Bienvenue</h1>";
echo "<a href='generer_lien.php'>Générer un lien de téléchargement</a>";
echo "<br><a href='logout.php'>Déconnexion</a>";
?>
