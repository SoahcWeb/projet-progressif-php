<?php
// Démarrer la session si elle n'est pas encore active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Charger la configuration du site
require_once __DIR__ . '/../config.php';

// Définir des titres par défaut si non spécifiés
$pageTitre = $pageTitre ?? "";
$metaDescription = $metaDescription ?? "Description par défaut";

// Identifier la page en cours
$currentPage = basename($_SERVER['PHP_SELF']);

// Générer dynamiquement le menu de navigation
function genererMenu($elements) {
    global $currentPage;
    echo '<nav><ul class="nav-list">';
    foreach ($elements as $nom => $lien) {
        $classeActive = (strtolower($currentPage) === strtolower(basename($lien))) ? 'active' : '';
        echo "<li><a class=\"$classeActive\" href=\"$lien\">$nom</a></li>";
    }
    echo '</ul></nav>';
}

// Éléments de navigation selon l'état de session
$menuElements = [
    'Accueil' => '/php_pp/progressif01/index.php',
    'Contact' => '/php_pp/progressif01/contact.php'
];

if (isset($_SESSION['user'])) {
    $menuElements['Profil'] = '/php_pp/progressif01/profil.php';
    $menuElements['Déconnexion'] = '/php_pp/progressif01/deconnexion.php';
} else {
    $menuElements['Connexion'] = '/php_pp/progressif01/connexion.php';
    $menuElements['Inscription'] = '/php_pp/progressif01/inscription.php';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitre) ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="/php_pp/progressif01/assets/style.css">
</head>
<body>
<header>
    <?php if (!empty($pageTitre)): ?>
        <h1><?= htmlspecialchars($pageTitre) ?></h1>
    <?php endif; ?>

    <?php genererMenu($menuElements); ?>

    <?php if (isset($_SESSION['user'])): ?>
        <p class="welcome">Bonjour, <?= htmlspecialchars($_SESSION['user']['pseudo']) ?> 👋</p>
    <?php endif; ?>
</header>
<main>




