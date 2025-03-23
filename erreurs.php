<?php
// Vérifier si le tableau $erreurs contient des messages d'erreur
if (!empty($erreurs)) {
    echo "<ul>";
    foreach ($erreurs as $erreur) {
        echo "<li>" . htmlspecialchars($erreur) . "</li>";
    }
    echo "</ul>";
}
?>