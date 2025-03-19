<?php
session_start();
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["email"])) {
    header("Location: connexion.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
</head>
<body>
    <h2>Bienvenue, <?php echo $_SESSION["email"]; ?> !</h2>
    <p><a href="deconnexion.php">Se déconnecter</a></p>
</body>
</html>
