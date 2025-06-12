<?php
session_start();

$pageTitre = "";
$metaDescription = "Créer un compte utilisateur.";

require_once __DIR__ . '/includes/authentification.php';
require_once __DIR__ . '/includes/header.php';

// Si utilisateur déjà connecté → redirection vers le profil
if (est_connecte()) {
    header('Location: profil.php');
    exit;
}

$errors = [];
$pseudo = $email = "";
$successMessage = "";

// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=bdd_projet_web;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et nettoyage
    $pseudo = trim($_POST['inscription_pseudo'] ?? '');
    $email = trim($_POST['inscription_email'] ?? '');
    $mdp = $_POST['inscription_motDePasse'] ?? '';
    $mdpConfirm = $_POST['inscription_motDePasse_confirmation'] ?? '';

    // Validation
    if (empty($pseudo) || strlen($pseudo) < 2 || strlen($pseudo) > 255) {
        $errors['pseudo'] = "Le pseudo doit contenir entre 2 et 255 caractères.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email invalide.";
    }
    if (empty($mdp) || strlen($mdp) < 8 || strlen($mdp) > 72) {
        $errors['mdp'] = "Le mot de passe doit contenir entre 8 et 72 caractères.";
    }
    if ($mdp !== $mdpConfirm) {
        $errors['mdp_confirm'] = "Les mots de passe ne correspondent pas.";
    }

    // Vérification unicité pseudo/email
    if (!$errors) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM t_utilisateur_uti WHERE uti_pseudo = ? OR uti_email = ?");
        $stmt->execute([$pseudo, $email]);
        if ($stmt->fetchColumn() > 0) {
            $errors['exist'] = "Pseudo ou email déjà utilisé.";
        }
    }

    // Insertion en BDD si pas d’erreurs
    if (!$errors) {
        $hash = password_hash($mdp, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO t_utilisateur_uti (uti_pseudo, uti_email, uti_motdepasse)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$pseudo, $email, $hash]);

        $successMessage = "Inscription réussie. Vous pouvez maintenant vous connecter.";
        $pseudo = $email = ""; // Nettoyage du formulaire
    }
}
?>

<h2>Inscription</h2>

<?php if ($successMessage): ?>
    <p style="color:green"><?= htmlspecialchars($successMessage) ?></p>
<?php endif; ?>

<?php if (isset($errors['exist'])): ?>
    <p style="color:red"><?= htmlspecialchars($errors['exist']) ?></p>
<?php endif; ?>

<form action="" method="post" novalidate>
    <label for="inscription_pseudo">Pseudo :</label><br>
    <input type="text" id="inscription_pseudo" name="inscription_pseudo" required minlength="2" maxlength="255"
           value="<?= htmlspecialchars($pseudo) ?>">
    <span style="color:red"><?= $errors['pseudo'] ?? '' ?></span><br>

    <label for="inscription_email">Email :</label><br>
    <input type="email" id="inscription_email" name="inscription_email" required
           value="<?= htmlspecialchars($email) ?>">
    <span style="color:red"><?= $errors['email'] ?? '' ?></span><br>

    <label for="inscription_motDePasse">Mot de passe :</label><br>
    <input type="password" id="inscription_motDePasse" name="inscription_motDePasse" required minlength="8"
           maxlength="72">
    <span style="color:red"><?= $errors['mdp'] ?? '' ?></span><br>

    <label for="inscription_motDePasse_confirmation">Confirmez mot de passe :</label><br>
    <input type="password" id="inscription_motDePasse_confirmation" name="inscription_motDePasse_confirmation"
           required minlength="8" maxlength="72">
    <span style="color:red"><?= $errors['mdp_confirm'] ?? '' ?></span><br>

    <button type="submit">S'inscrire</button>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
