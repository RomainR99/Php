<?php
// Inclure le fichier des fonctions
require_once 'fonctions.php';

// Vérifier que l'utilisateur est bien connecté
obligationConnexion();

// Utiliser l'email pour créer le hash du dossier utilisateur
$emailUtilisateur = $_SESSION["email"]; // Récupérer l'email de l'utilisateur
$hashUtilisateur = hash('sha256', $emailUtilisateur);
$dossierUtilisateur = "uploads/" . $hashUtilisateur;

// Vérifier si le dossier de l'utilisateur existe, sinon le créer
if (!is_dir($dossierUtilisateur)) {
    mkdir($dossierUtilisateur, 0777, true);
}

// Liste des fichiers dans le dossier utilisateur
$fichiers = array_diff(scandir($dossierUtilisateur), array('.', '..'));

// Gestion de la suppression d'un fichier
$message = "";
if (isset($_GET['supprimer']) && !empty($_GET['supprimer'])) {
    $fichierASupprimer = basename($_GET['supprimer']); // Sécuriser le fichier à supprimer
    $cheminFichier = $dossierUtilisateur . "/" . $fichierASupprimer;

    if (file_exists($cheminFichier)) {
        unlink($cheminFichier);
        $message = "Fichier supprimé avec succès.";
    } else {
        $message = "Erreur : fichier introuvable.";
    }
}

// Charger la base de données des fichiers publics
define("DB_FILE", "files_db.json");
$FILES_DB = file_exists(DB_FILE) ? json_decode(file_get_contents(DB_FILE), true) : [];

// Gestion de l'upload de fichier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fichier'])) {
    // Récupérer les données du formulaire
    $fichier = $_FILES['fichier'];
    $public = isset($_POST['public']) && $_POST['public'] == 'on'; // Vérifier si la case "public" est cochée

    // Créer un nom de fichier unique
    $fileName = uniqid() . "_" . basename($fichier['name']);
    $filePath = $dossierUtilisateur . "/" . $fileName;

    // Déplacer le fichier téléchargé vers son emplacement
    if (move_uploaded_file($fichier['tmp_name'], $filePath)) {
        // Si le fichier est téléchargé, ajouter son chemin et sa visibilité à la base de données
        $key = uniqid();
        $FILES_DB[$key] = [
            'path' => $filePath,
            'public' => $public,
            'downloads' => 0
        ];

        // Sauvegarder la base de données des fichiers
        file_put_contents(DB_FILE, json_encode($FILES_DB, JSON_PRETTY_PRINT));
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace de fichiers</title>
</head>
<body>
    <h1>Bienvenue dans votre espace de fichiers</h1>

    <!-- Afficher un message si un fichier est supprimé -->
    <?php if (!empty($message)): ?>
        <p style="color: red;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- Formulaire pour télécharger un fichier -->
    <h2>Envoyer un fichier</h2>
<form action="upload.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="fichier" required>
    <label>
        <input type="checkbox" name="public"> Rendre ce fichier public
    </label>
    <button type="submit">Télécharger</button>
</form>
    <h2>Vos fichiers :</h2>
    <ul>
        <?php if (empty($fichiers)): ?>
            <li>Aucun fichier trouvé.</li>
        <?php else: ?>
            <?php foreach ($fichiers as $fichier): ?>
                <li>
                     <a href="<?= htmlspecialchars($dossierUtilisateur . '/' . $fichier) ?>" download><?= htmlspecialchars($fichier) ?></a> 
                    <a href="espace_fichiers.php?supprimer=<?= urlencode($fichier) ?>" 
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?');">
                        Supprimer
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <!-- Afficher les fichiers publics avec leur lien -->
    <h2>Fichiers Publics Disponibles :</h2>
    <ul>
        <?php if (empty($FILES_DB)): ?>
            <li>Aucun fichier public disponible.</li>
        <?php else: ?>
            <?php foreach ($FILES_DB as $key => $file): ?>
                <?php if ($file["public"]): ?>
                    <li>
                        📂 <a href="http://localhost/8888/ProjetPHPbis/telechargement.php?cle=<?= htmlspecialchars($key) ?>">
                            <?= htmlspecialchars(basename($file["path"])) ?>
                        </a> 
                        (Téléchargements : <?= (int) $file["downloads"] ?>)
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
    
    <!-- Lien pour se déconnecter -->
    <a href="deconnexion.php">Se déconnecter</a> | 
    <a href="modifier_profil.php">Modifier mon profil</a>
</body>
</html>

