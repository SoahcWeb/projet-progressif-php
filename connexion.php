<?php

$pageTitre = "";
$metaDescription = "Connectez-vous à votre compte.";

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/authentification.php';
require_once __DIR__ . '/includes/pdo.php';

$errors = [];
$pseudo = "";

// Redirection si déjà connecté
if (est_connecte()) {
    header("Location: profil.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['connexion_pseudo'] ?? '');
    $mdp = $_POST['connexion_motDePasse'] ?? '';
    $remember = isset($_POST['remember_me']);

    // Validation
    if (strlen($pseudo) < 2 || strlen($pseudo) > 255) {
        $errors['pseudo'] = "Le pseudo doit contenir entre 2 et 255 caractères.";
    }
    if (strlen($mdp) < 8 || strlen($mdp) > 72) {
        $errors['mdp'] = "Le mot de passe doit contenir entre 8 et 72 caractères.";
    }

    if (!$errors) {
        $stmt = $pdo->prepare("SELECT uti_id, uti_pseudo, uti_motdepasse FROM t_utilisateur_uti WHERE uti_pseudo = :pseudo");
        $stmt->execute(['pseudo' => $pseudo]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur && password_verify($mdp, $utilisateur['uti_motdepasse'])) {
            connecter_utilisateur($utilisateur['uti_id'], $remember);
            header("Location: profil.php");
            exit;
        } else {
            $errors['connexion'] = "Pseudo ou mot de passe incorrect.";
        }
    }
}
?>

<h2>Connexion</h2>

<?php if ($errors): ?>
    <ul class="errors">
        <?php foreach ($errors as $err): ?>
            <li style="color:red"><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post">
    <label>Pseudo :
        <input type="text" name="connexion_pseudo" value="<?= htmlspecialchars($pseudo) ?>" required>
    </label><br>

    <label>Mot de passe :
        <input type="password" name="connexion_motDePasse" required>
    </label><br>

    <label>
        <input type="checkbox" name="remember_me"> Se souvenir de moi
    </label><br>

    <button type="submit">Connexion</button>
</form>

<p><a href="inscription.php">Pas encore inscrit ?</a></p>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
