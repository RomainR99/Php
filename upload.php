<?php
// fichier upload.php
require_once 'fonctions.php';
obligationConnexion();

$erreurs = [];
$maxFileSize = 20 * 1024 * 1024; // 20 Mo en octets
$extensionsInterdites = ['php'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fichier"])) {
    $fichier = $_FILES["fichier"];
    $nomFichier = $fichier["name"];
    $tailleFichier = $fichier["size"];
    $tmpFichier = $fichier["tmp_name"];
    $extension = strtolower(pathinfo($nomFichier, PATHINFO_EXTENSION));

    // Vérifier l'extension
    if (in_array($extension, $extensionsInterdites)) {
        $erreurs[] = "Ce type de fichier est interdit.";
    }
    
    // Vérifier la taille
    if ($tailleFichier > $maxFileSize) {
        $erreurs[] = "Le fichier est trop volumineux (max 20 Mo).";
    }
    
    if (empty($erreurs)) {
        // Déterminer le dossier utilisateur
        $hashUtilisateur = hash('sha256', $_SESSION["identifiant"]);
        $dossierUtilisateur = "uploads/" . $hashUtilisateur;
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($dossierUtilisateur)) {
            mkdir($dossierUtilisateur, 0777, true);
        }
        
        // Déplacer le fichier dans le dossier utilisateur
        $cheminFichier = $dossierUtilisateur . "/" . basename($nomFichier);
        if (move_uploaded_file($tmpFichier, $cheminFichier)) {
            header('Location: dashboard.php');
            exit();
        } else {
            $erreurs[] = "Erreur lors de l'upload du fichier.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploader un fichier</title>
</head>
<body>
    <h1>Uploader un fichier</h1>
    <?php require_once 'erreurs.php'; ?>
    <form method="POST" enctype="multipart/form-data">
        <label for="fichier">Choisir un fichier :</label>
        <input type="file" id="fichier" name="fichier" required>
        <input type="submit" value="Envoyer">
    </form>
    <a href="dashboard.php">Retour au Dashboard</a>
</body>
</html>
