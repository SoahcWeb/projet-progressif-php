<?php
// includes/pdo.php

// Connexion à la base de données avec PDO (centralisé pour réutilisation)
$host = 'localhost';
$dbname = 'bdd_projet_web';
$userDB = 'root';
$passDB = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $userDB, $passDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
