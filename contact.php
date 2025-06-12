<?php
$pageTitre = "";
$metaDescription = "Cette page vous permet de nous contacter en remplissant un formulaire simple.";

require_once __DIR__ . '/includes/header.php';

$errors = [];
$nom = $prenom = $email = $message = "";
$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation du nom
    if (empty($_POST["nom"])) {
        $errors["nom"] = "Le champ nom est requis.";
    } elseif (strlen($_POST["nom"]) < 2 || strlen($_POST["nom"]) > 255) {
        $errors["nom"] = "Le nom doit contenir entre 2 et 255 caractères.";
    } else {
        $nom = htmlspecialchars(trim($_POST["nom"]));
    }

    // Validation du prénom (facultatif)
    if (!empty($_POST["prenom"])) {
        if (strlen($_POST["prenom"]) < 2 || strlen($_POST["prenom"]) > 255) {
            $errors["prenom"] = "Le prénom doit contenir entre 2 et 255 caractères.";
        } else {
            $prenom = htmlspecialchars(trim($_POST["prenom"]));
        }
    }

    // Validation de l'email
    if (empty($_POST["email"])) {
        $errors["email"] = "Le champ email est requis.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "L'email n'est pas valide.";
    } else {
        $email = htmlspecialchars(trim($_POST["email"]));
    }

    // Validation du message
    if (empty($_POST["message"])) {
        $errors["message"] = "Le champ message est requis.";
    } elseif (strlen($_POST["message"]) < 10 || strlen($_POST["message"]) > 3000) {
        $errors["message"] = "Le message doit contenir entre 10 et 3000 caractères.";
    } else {
        $message = htmlspecialchars(trim($_POST["message"]));
    }

    // Si pas d'erreurs, envoi de l'email
    if (empty($errors)) {
        $to = "jonathan.pauwels@ifosup.wavre.be"; // <-- remplace par ta vraie adresse
        $subject = "Projet Framework - Formulaire de contact";

        // Construction du message HTML
        $emailContent = "
            <html>
            <head>
              <title>Message du formulaire de contact</title>
            </head>
            <body>
              <h2>Nouveau message de contact</h2>
              <p><strong>Nom :</strong> {$nom}</p>
              <p><strong>Prénom :</strong> {$prenom}</p>
              <p><strong>Email :</strong> {$email}</p>
              <p><strong>Message :</strong><br>" . nl2br($message) . "</p>
            </body>
            </html>
        ";

        // Headers
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: {$nom} <{$email}>\r\n";
        $headers .= "Reply-To: {$email}\r\n";

        // Envoi du mail
        if (mail($to, $subject, $emailContent, $headers)) {
            $successMessage = "Le formulaire a bien été envoyé ! Merci de votre message.";
            // Réinitialiser les champs après envoi
            $nom = $prenom = $email = $message = "";
        } else {
            $errorMessage = "Le formulaire n'a pas pu être envoyé. Veuillez réessayer plus tard.";
        }
    } else {
        $errorMessage = "Le formulaire n'a pas été envoyé ! Veuillez corriger les erreurs.";
    }
}
?>

<h2>Contact</h2>

<?php if ($successMessage): ?>
    <p style="color:green;"><?= $successMessage ?></p>
<?php elseif ($errorMessage): ?>
    <p style="color:red;"><?= $errorMessage ?></p>
<?php endif; ?>

<form action="" method="post" novalidate>
    <label for="nom">Nom (requis) :</label><br>
    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($nom) ?>" aria-invalid="<?= isset($errors['nom']) ? 'true' : 'false' ?>" aria-describedby="nom-error"><br>
    <span id="nom-error" style="color:red"><?= $errors['nom'] ?? '' ?></span><br>

    <label for="prenom">Prénom (facultatif) :</label><br>
    <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($prenom) ?>" aria-invalid="<?= isset($errors['prenom']) ? 'true' : 'false' ?>" aria-describedby="prenom-error"><br>
    <span id="prenom-error" style="color:red"><?= $errors['prenom'] ?? '' ?></span><br>

    <label for="email">Email (requis) :</label><br>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" aria-invalid="<?= isset($errors['email']) ? 'true' : 'false' ?>" aria-describedby="email-error"><br>
    <span id="email-error" style="color:red"><?= $errors['email'] ?? '' ?></span><br>

    <label for="message">Message (requis) :</label><br>
    <textarea id="message" name="message" rows="5" aria-invalid="<?= isset($errors['message']) ? 'true' : 'false' ?>" aria-describedby="message-error"><?= htmlspecialchars($message) ?></textarea><br>
    <span id="message-error" style="color:red"><?= $errors['message'] ?? '' ?></span><br>

    <button type="submit">Envoyer</button>
</form>

<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '/includes/footer.php'; ?>
