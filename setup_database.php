<?php
$host = 'localhost';
$dbname = 'bdd_projet_web';
$user = 'root';
$pass = '';

try {
    // Connexion sans DB pour créer la base si besoin
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✔ Base '$dbname' créée ou existante.<br>";

    // Connexion à la base
    $pdo->exec("USE $dbname");

    // Supprimer la table si elle existe (ATTENTION : efface les données)
    $pdo->exec("DROP TABLE IF EXISTS t_utilisateur_uti");
    echo "✔ Table 't_utilisateur_uti' supprimée si elle existait.<br>";

    // Créer la table avec toutes les colonnes nécessaires
    $sql = "
    CREATE TABLE t_utilisateur_uti (
        uti_id INT AUTO_INCREMENT PRIMARY KEY,
        uti_pseudo VARCHAR(255) NOT NULL UNIQUE,
        uti_email VARCHAR(255) NOT NULL UNIQUE,
        uti_motdepasse VARCHAR(255) NOT NULL,
        uti_compte_active TINYINT(1) NOT NULL DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($sql);
    echo "✔ Table 't_utilisateur_uti' créée avec succès.<br>";

} catch (PDOException $e) {
    die("❌ Erreur : " . $e->getMessage());
}

