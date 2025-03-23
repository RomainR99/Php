<?php
// Charger la liste des fichiers autorisés

$fichierConfig = 'fichier.json';
if (!file_exists($fichierConfig)) {
    die("Erreur : fichier de configuration introuvable.");
}

$data = json_decode(file_get_contents($fichierConfig), true);

// Vérifier si la clé est fournie dans l'URL

if (!isset($_GET['cle']) || empty($_GET['cle'])) {
    die("Erreur : clé de téléchargement absente.");
}

$cle = $_GET['cle'];

// Vérifier si la clé correspond à un fichier existant

if (!isset($data[$cle])) {
    die("Erreur : clé invalide ou fichier introuvable.");
}

$fichier = $data[$cle];

// Vérifier si le fichier existe physiquement sur le serveur

if (!file_exists($fichier)) {
    die("Erreur : ficheir non trouvé sur le serveur.");
}

// Définir les en-têtes pour le téléchargement

header('Content-Description: File Transfert');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachement; filename="' . basename($fichier) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($fichier));

// Lire et envoyer le fichier

readfile($fichier);
exit;