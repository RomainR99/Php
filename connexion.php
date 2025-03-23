<?php
require_once 'fonctions.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$email || empty($password)) {
        $message = "Veuillez remplir tous les champs.";
    } else {
        $utilisateur = recupererUtilisateurParAdresse($email);

        if ($utilisateur && password_verify($password, $utilisateur['password'])) {
            $_SESSION["identifiant"] = $utilisateur['id']; // Stocke l'ID de l'utilisateur en session
            $_SESSION["email"] = $utilisateur['email']; // Stocke l'email en session
            header('Location: espace_fichiers.php'); // Redirige vers l'espace de stockage
            exit();
        } else {
            $message = "Identifiants incorrects.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Adresse e-mail" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
    <h3><?= htmlspecialchars($message) ?></h3>
    <a href="inscription.php">S'inscrire</a>
</body>
</html>

