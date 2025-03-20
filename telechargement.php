<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Accès refusé.");
}

if (!isset($_GET['token']) || !isset($_SESSION['tokens'][$_GET['token']])) {
    die("Lien invalide ou expiré.");
}

$file_name = $_SESSION['tokens'][$_GET['token']];
$file_path = __DIR__ . "/fichiers/" . $file_name;

if (!file_exists($file_path)) {
    die("Fichier introuvable.");
}

// En-têtes HTTP pour le téléchargement
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
header('Content-Length: ' . filesize($file_path));
readfile($file_path);
// Supprimer le fichier après téléchargement
if (unlink($file_path)) {
    echo "Le fichier a été supprimé avec succès après le téléchargement.";
} else {
    echo "Erreur lors de la suppression du fichier.";
}
// Supprimer le token après utilisation
unset($_SESSION['tokens'][$_GET['token']]);

exit;
?>
