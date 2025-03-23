<?php
// Fichier de stockage des clés (simulation d'une base de données avec un fichier JSON)
define("DB_FILE", "files_db.json");

// Charger la base de données des fichiers
$FILES_DB = file_exists(DB_FILE) ? json_decode(file_get_contents(DB_FILE), true) : [];

// Générer une clé unique
function generateUniqueKey($length = 7) {
    return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, $length);
}

// Ajouter un fichier avec une clé unique
$file_name = "file1.pdf";  // Supposons que ce fichier soit déjà uploadé dans "uploads/"
$unique_key = generateUniqueKey();

// Enregistrer la clé et le fichier comme public avec un compteur à 0
$FILES_DB[$unique_key] = [
    "path" => "uploads/" . $file_name,
    "public" => true,
    "downloads" => 0  // Initialisation du compteur
];

// Sauvegarde dans le fichier JSON
file_put_contents(DB_FILE, json_encode($FILES_DB, JSON_PRETTY_PRINT));

// Afficher le lien de téléchargement
$download_link = "http://localhost/telechargement.php?cle=" . $unique_key;
echo "Lien de téléchargement : <a href='$download_link'>$download_link</a> (Téléchargements : 0)";
?>

