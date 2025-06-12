<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/authentification.php';
require_once __DIR__ . '/includes/pdo.php';

$pageTitre = "";
$metaDescription = "Page de profil utilisateur";

if (!est_connecte()) {
    header("Location: connexion.php");
    exit;
}

$user = get_utilisateur_connecte($pdo);

if (!$user) {
    echo "<p>Utilisateur introuvable.</p>";
    exit;
}
?>

<h2>Profil de <?= htmlspecialchars($user['uti_pseudo']) ?></h2>
<p>Email : <?= htmlspecialchars($user['uti_email']) ?></p>

<p><a href="deconnexion.php">Se déconnecter</a></p>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
