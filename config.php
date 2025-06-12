<?php
// config.php - connexion PDO
$host = 'localhost';  // serveur MySQL
$db   = 'bdd_projet_web'; // nom BDD
$user = 'root';      // utilisateur BDD (adapter selon ta config)
$pass = '';          // mot de passe BDD
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);
} catch (PDOException $e) {
    die('Erreur connexion BDD : ' . $e->getMessage());
}

// Démarrage session (important pour auth)
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
