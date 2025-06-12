<?php
require_once __DIR__ . '/includes/authentification.php';

deconnecter_utilisateur();
header("Location: connexion.php");
exit;
