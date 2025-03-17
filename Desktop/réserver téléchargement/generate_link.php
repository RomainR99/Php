<?php
// Simulons une base de données (à remplacer par MySQL)
$FILES_DB = [
    "A9B7G5H" => ["file" => "uploads/file1.pdf", "email" => "alice@alice.com"]
];

// Générer une clé unique
function generateUniqueKey($length = 7) {
    return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, $length);
}

// Ajouter un fichier avec une clé unique et une adresse e-mail
$file_name = "file1.pdf";  // Fichier à protéger
$user_email = "alice@alice.com";  // E-mail autorisé
$unique_key = generateUniqueKey();

$FILES_DB[$unique_key] = ["file" => "uploads/" . $file_name, "email" => $user_email];

// Afficher le lien de téléchargement
$download_link = "http://localhost/telechargement.php?cle=$unique_key&email=$user_email";
echo "Lien de téléchargement : <a href='$download_link'>$download_link</a>";
?>
