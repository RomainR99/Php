<?php
// J'ai 2 phpmyadmin, c'est pour m'assure du bon port cf localhost8888phpmyadmin.png
$db_server = "localhost";
$db_user = "root";
$db_pass = "root"; // Essaie "" si MAMP utilise un mot de passe vide
$db_name = "ProjetPhp";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

echo "Connexion réussie à la base de données !";
?>