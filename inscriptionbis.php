<?php
// Connexion à la base de données
$db_server = "localhost";
$db_name = "ProjetPhp";
$db_user = "root";
$db_pass = "root";

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($prenom) || empty($nom)) {
        $message = "Veuillez entrer votre prénom et nom.";
    } elseif (!$email) {
        $message = "Veuillez entrer une adresse e-mail valide.";
    } elseif (empty($password) || strlen($password) < 6) {
        $message = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($password !== $confirm_password) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'utilisateur existe déjà
        $sql_check = "SELECT id FROM utilisateurs WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $message = "Cette adresse e-mail est déjà utilisée.";
        } else {
            // Hacher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insérer l'utilisateur en base de données
            $sql = "INSERT INTO utilisateurs (prenom, nom, email, password) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $prenom, $nom, $email, $hashed_password);
            if (mysqli_stmt_execute($stmt)) {
                $message = "Inscription réussie ! <a href='connexion.php'>Se connecter</a>";
            } else {
                $message = "Erreur lors de l'inscription.";
            }
        }

        mysqli_stmt_close($stmt);
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h2>Inscription</h2>
    <form method="post">
        <input type="text" name="prenom" placeholder="Prénom" required>
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="email" name="email" placeholder="Adresse e-mail" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="password" name="confirm_password" placeholder="Confirmez le mot de passe" required>
        <button type="submit">S'inscrire</button>
    </form>
    <h3><?= htmlspecialchars($message) ?></h3>
</body>
</html>
