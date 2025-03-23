<?php
require_once 'fonctions.php';
obligationConnexion();

$conn = getDbConnection();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nouveauEmail = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $ancienMotDePasse = $_POST['ancien_password'];
    $nouveauMotDePasse = $_POST['nouveau_password'];
    $confirmerMotDePasse = $_POST['confirm_password'];

    $idUtilisateur = $_SESSION["identifiant"];

    // Récupérer l'utilisateur actuel
    $utilisateur = recupererUtilisateurParAdresse($_SESSION["email"]);

    if (!$utilisateur) {
        $message = "Utilisateur introuvable.";
    } elseif (!password_verify($ancienMotDePasse, $utilisateur['password'])) {
        $message = "Ancien mot de passe incorrect.";
    } elseif (!empty($nouveauMotDePasse) && strlen($nouveauMotDePasse) < 6) {
        $message = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
    } elseif ($nouveauMotDePasse !== $confirmerMotDePasse) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'email est déjà utilisé
        if ($nouveauEmail !== $_SESSION["email"] && estCeQueLadresseExisteDeja($nouveauEmail)) {
            $message = "Cette adresse e-mail est déjà utilisée.";
        } else {
            // Mise à jour des informations
            sauvegarderLesUtilisateurs($_SESSION["email"], $nouveauEmail, !empty($nouveauMotDePasse) ? $nouveauMotDePasse : null);
            $_SESSION["email"] = $nouveauEmail;
            $message = "Modification réussie !";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil</title>
</head>
<body>
    <h2>Modifier mon profil</h2>
    <form method="post">
        <label>Nouvelle adresse e-mail :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['email']) ?>" required>

        <label>Ancien mot de passe :</label>
        <input type="password" name="ancien_password" required>

        <label>Nouveau mot de passe (laisser vide pour ne pas changer) :</label>
        <input type="password" name="nouveau_password">

        <label>Confirmer le nouveau mot de passe :</label>
        <input type="password" name="confirm_password">

        <button type="submit">Enregistrer les modifications</button>
    </form>

    <h3><?= htmlspecialchars($message) ?></h3>

    <a href="espace_fichiers.php">Retour à l'espace fichiers</a>
</body>
</html>
