<?php
// fichier modifier_motdepasse.php
require_once 'fonctions.php';
obligationConnexion();

$erreurs = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ancienMotDePasse = filter_input(INPUT_POST, "ancien_motdepasse");
    $nouveauMotDePasse = filter_input(INPUT_POST, "nouveau_motdepasse");
    $confirmationMotDePasse = filter_input(INPUT_POST, "confirmation_motdepasse");
    
    $utilisateur = recupererUtilisateurParAdresse($_SESSION["identifiant"]);
    
    if (!$ancienMotDePasse || !$nouveauMotDePasse || !$confirmationMotDePasse) {
        $erreurs[] = "Tous les champs sont obligatoires.";
    } elseif (!password_verify($ancienMotDePasse, $utilisateur["pwd"])) {
        $erreurs[] = "L'ancien mot de passe est incorrect.";
    } elseif ($nouveauMotDePasse !== $confirmationMotDePasse) {
        $erreurs[] = "Les nouveaux mots de passe ne correspondent pas.";
    } else {
        $utilisateurs = recupererLesUtilisateurs();
        foreach ($utilisateurs as &$user) {
            if ($user["login"] == $_SESSION["identifiant"]) {
                $user["pwd"] = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
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
    <title>Modifier Mot de Passe</title>
</head>
<body>
    <h1>Modifier votre mot de passe</h1>
    <?php require_once 'erreurs.php'; ?>
    <form method="POST">
        <label for="ancien_motdepasse">Ancien mot de passe :</label>
        <input type="password" id="ancien_motdepasse" name="ancien_motdepasse" required>

        <label for="nouveau_motdepasse">Nouveau mot de passe :</label>
        <input type="password" id="nouveau_motdepasse" name="nouveau_motdepasse" required>

        <label for="confirmation_motdepasse">Confirmer nouveau mot de passe :</label>
        <input type="password" id="confirmation_motdepasse" name="confirmation_motdepasse" required>

        <input type="submit" value="Modifier">
    </form>
    <a href="dashboard.php">Retour au Dashboard</a>
</body>
</html>