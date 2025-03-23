<?php
// Fichier JSON qui stocke les fichiers publics
define("DB_FILE", "files_db.json");

// Charger la base de données des fichiers
$FILES_DB = file_exists(DB_FILE) ? json_decode(file_get_contents(DB_FILE), true) : [];

// Vérifier si une clé est passée en paramètre
if (isset($_GET['cle'])) {
    $key = $_GET['cle'];

    // Vérifier si la clé existe et si le fichier est public
    if (isset($FILES_DB[$key]) && $FILES_DB[$key]["public"]) {
        $file_path = $FILES_DB[$key]["path"];

        // Remplacer les barres obliques inverses par des barres obliques normales
        $file_path = str_replace("\\/", "/", $file_path);  // Correction pour les barres obliques inverses

        // Afficher le chemin du fichier pour déboguer
        echo "Chemin du fichier : $file_path<br>";

        // Vérifier si le fichier existe sur le serveur
        if (file_exists($file_path)) {
            // Incrémenter le compteur de téléchargements
            $FILES_DB[$key]["downloads"] += 1;
            file_put_contents(DB_FILE, json_encode($FILES_DB, JSON_PRETTY_PRINT));

            // Forcer le téléchargement
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        } else {
            die("Erreur : Le fichier n'existe pas.");
        }
    } else {
        die("Erreur : Clé invalide ou fichier non public.");
    }
} else {
    die("Erreur : Aucune clé fournie.");
}
?>




