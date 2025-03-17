<?php
// Simulons une base de données (à remplacer par MySQL)
$FILES_DB = [
    "A9B7G5H" => ["file" => "uploads/file1.pdf", "email" => "alice@alice.com"]
];

// Récupérer les paramètres depuis l’URL
$cle = $_GET["cle"] ?? null;
$user_email = $_GET["email"] ?? null;

if (!$cle || !$user_email || !isset($FILES_DB[$cle])) {
    die("Clé invalide ou fichier introuvable.");
}

// Vérifier si l’e-mail correspond
$file_info = $FILES_DB[$cle];
if ($file_info["email"] !== $user_email) {
    die("Accès refusé : votre e-mail n’est pas autorisé.");
}

$file_path = $file_info["file"];

if (!file_exists($file_path)) {
    die("Fichier non trouvé.");
}

// Forcer le téléchargement du fichier
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . basename($file_path) . "\"");
header("Content-Length: " . filesize($file_path));
readfile($file_path);
exit;
?>
