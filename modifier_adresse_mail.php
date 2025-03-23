<?php
require_once 'fonctions.php';
obligationConnexion();

$erreurs = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nouveauEmail = filter_input(INPUT_POST, "nouveau_email", FILTER_VALIDATE_EMAIL);
    
    if (!$nouveauEmail) {
        $erreurs[] = "L'adresse e-mail est invalide.";
    } elseif (estCeQueLadresseExisteDeja($nouveauEmail)) {
        $erreurs[] = "Cette adresse e-mail est déjà utilisée.";
    } else {
        $utilisateurs = recupererLesUtilisateurs();
        foreach ($utilisateurs as &$utilisateur) {
            if ($utilisateur["login"] == $_SESSION["identifiant"]) {
                $utilisateur["login"] = $nouveauEmail;
                $_SESSION["identifiant"] = $nouveauEmail;
                break;
            }
        }
        sauvegarderLesUtilisateurs($utilisateurs);
        header('Location: dashboard.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Email</title>
</head>
<body>
    <h1>Modifier votre adresse e-mail</h1>
    <?php require_once 'erreurs.php'; ?>
    <form method="POST">
        <label for="nouveau_email">Nouvelle adresse e-mail :</label>
        <input type="email" id="nouveau_email" name="nouveau_email" required>
        <input type="submit" value="Modifier">
    </form>
    <a href="dashboard.php">Retour au Dashboard</a>
</body>
</html>