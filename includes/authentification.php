<?php
// includes/authentification.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connexion PDO centralisée
require_once __DIR__ . '/pdo.php';

/**
 * Connecte un utilisateur en session et (optionnellement) avec cookie
 */
function connecter_utilisateur(int $id, bool $rememberMe = false) {
    $_SESSION['utilisateurId'] = $id;

    if ($rememberMe) {
        // Crée un cookie valable 30 jours
        setcookie('remember_me', $id, time() + 30 * 24 * 60 * 60, '/', '', false, true);
    }
}

/**
 * Vérifie si un utilisateur est connecté (session ou cookie)
 */
function est_connecte(): bool {
    if (isset($_SESSION['utilisateurId'])) {
        return true;
    }

    // Tentative de reconnexion via le cookie
    if (isset($_COOKIE['remember_me'])) {
        $_SESSION['utilisateurId'] = (int) $_COOKIE['remember_me'];
        return true;
    }

    return false;
}

/**
 * Retourne l'utilisateur connecté (ou null si non connecté)
 */
function get_utilisateur_connecte(PDO $pdo): ?array {
    if (!est_connecte()) {
        return null;
    }

    $id = $_SESSION['utilisateurId'];
    $stmt = $pdo->prepare("SELECT uti_id, uti_pseudo, uti_email FROM t_utilisateur_uti WHERE uti_id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Déconnecte l'utilisateur de la session et supprime le cookie
 */
function deconnecter_utilisateur() {
    unset($_SESSION['utilisateurId']);
    setcookie('remember_me', '', time() - 3600, '/');
}
?>
