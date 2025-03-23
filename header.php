<?php
// Pour rester conneté
session_start();
?>
<nav>
    <a href="index.php">Accueil</a>
    <?php if (isset($_SESSION["identifiant"])): ?>
        <a href="deconnexion.php">Se déconnecter</a>
    <?php endif; ?>
</nav>
