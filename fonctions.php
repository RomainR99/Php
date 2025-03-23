<?php
session_start();

// Connexion à la base de données
function getDbConnection() {
    $db_server = "localhost";
    $db_name = "ProjetPhp";
    $db_user = "root";
    $db_pass = "root";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    if (!$conn) {
        die("Erreur de connexion : " . mysqli_connect_error());
    }
    return $conn;
}

// Vérifie si l'utilisateur est connecté, sinon redirige vers connexion.php
function obligationConnexion() {
    if (!isset($_SESSION["identifiant"])) {
        header("Location: connexion.php");
        exit();
    }
}

// Vérifie si une adresse e-mail existe déjà dans la base
function estCeQueLadresseExisteDeja($email) {
    $conn = getDbConnection();
    $sql = "SELECT id FROM utilisateurs WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $existe = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $existe;
}

// Récupère les informations d'un utilisateur par son email
function recupererUtilisateurParAdresse($email) {
    $conn = getDbConnection();
    $sql = "SELECT id, email, password FROM utilisateurs WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $utilisateur = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $utilisateur;
}

// Fonction pour déconnecter un utilisateur
function deconnecterUtilisateur() {
    session_destroy();
    header("Location: connexion.php");
    exit();
}
// Met à jour les informations d'un utilisateur (email et/ou mot de passe)
function sauvegarderLesUtilisateurs($email, $nouveauEmail, $nouveauMotDePasse = null) {
    if ($email === $nouveauEmail || !estCeQueLadresseExisteDeja($nouveauEmail)) {
        $conn = getDbConnection();

        if ($nouveauMotDePasse) {
            $hashedPassword = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
            $sql = "UPDATE utilisateurs SET email = ?, password = ? WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $nouveauEmail, $hashedPassword, $email);
        } else {
            $sql = "UPDATE utilisateurs SET email = ? WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $nouveauEmail, $email);
        }

        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        return $success;
    }
    return false; // L'email est déjà pris
}
?>