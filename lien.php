<?php
// Simulons une base de données avec un tableau
$FILES_DB = [
    "A9B7G5H" => "uploads/file1.pdf",
    "X3Y2Z1W" => "uploads/image.jpg"
];

// Générer une clé unique pour un fichier
function generateUniqueKey($length = 7) {
    return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, $length);
}

// Ajouter un fichier avec une clé unique
$file_name = "file1.pdf";  // Supposons que ce fichier soit déjà uploadé dans "uploads/"
$unique_key = generateUniqueKey();
$FILES_DB[$unique_key] = "uploads/" . $file_name;

// Afficher le lien de téléchargement
$download_link = "http://localhost/telechargement.php?cle=" . $unique_key;
echo "Lien de téléchargement : <a href='$download_link'>$download_link</a>";
?>

?>
