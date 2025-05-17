<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Fonction pour vérifier si l'utilisateur est connecté
 * @return bool True si l'utilisateur est connecté, false sinon
 */
function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

/**
 * Fonction pour vérifier si l'utilisateur est un administrateur
 * @return bool True si l'utilisateur est un administrateur, false sinon
 */
function isAdmin() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin';
}

/**
 * Fonction pour rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

/**
 * Fonction pour rediriger l'utilisateur vers la page d'accueil s'il n'est pas un administrateur
 */
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: index.php');
        exit();
    }
} 