<?php
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    die("Accès refusé.");
}

// Nom du fichier (tu peux modifier ici)
$file_name = "exemple.pdf"; 

// Générer un token unique
$token = bin2hex(random_bytes(32));

// Définir une expiration de 1 heure (3600 secondes)
$expiration_time = time() + 3600; // 1 heure après la génération du lien

// Stocker le token et son expiration en session
$_SESSION['tokens'][$token] = [
    'file_name' => $file_name,
    'expiration' => $expiration_time,
];

// Lien de téléchargement sécurisé
$download_link = "http://localhost/mon_projet/telechargement.php?token=" . $token;

echo "Lien de téléchargement : <a href='$download_link'>$download_link</a>";
?>
