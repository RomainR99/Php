<?php
session_start();
require_once 'fonctions.php';

obligationConnexion();

$emailUtilisateur = $_SESSION["email"];
$hashUtilisateur = hash('sha256', $emailUtilisateur);
$dossierUtilisateur = "uploads/" . $hashUtilisateur;

if (!is_dir($dossierUtilisateur)) {
    mkdir($dossierUtilisateur, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["fichier"])) {
    $nomFichier = basename($_FILES["fichier"]["name"]);
    $cheminFichier = $dossierUtilisateur . "/" . $nomFichier;

    if (move_uploaded_file($_FILES["fichier"]["tmp_name"], $cheminFichier)) {
        // Charger la base de données des fichiers
        define("DB_FILE", "files_db.json");
        $FILES_DB = file_exists(DB_FILE) ? json_decode(file_get_contents(DB_FILE), true) : [];

        // Générer une clé unique pour le fichier
        function generateUniqueKey($length = 7) {
            return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, $length);
        }

        $uniqueKey = generateUniqueKey();

        // Déterminer si le fichier doit être public
        $isPublic = isset($_POST["public"]) ? true : false;

        // Ajouter le fichier à la base de données
        $FILES_DB[$uniqueKey] = [
            "path" => $cheminFichier,
            "public" => $isPublic,
            "downloads" => 0
        ];

        // Sauvegarder la base de données mise à jour
        file_put_contents(DB_FILE, json_encode($FILES_DB, JSON_PRETTY_PRINT));

        // Rediriger vers l'espace fichier avec un message de succès
        header("Location: espace_fichiers.php?upload=success");
        exit();
    } else {
        echo "Erreur lors du téléversement du fichier.";
    }
}
?>
