<?php
session_start();
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

header('Content-Type: application/json');

try {
    $pdo = getPDO();
    $horaire = new Horaire($pdo);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée');
    }

    // Validation des données
    $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $statut = trim($_POST['statut'] ?? '');
    $specialite = trim($_POST['specialite'] ?? '');

    // Validation des champs obligatoires
    if (empty($nom) || empty($prenom) || empty($statut)) {
        throw new Exception('Le nom, le prénom et le statut sont obligatoires');
    }

    // Validation de l'email
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('L\'email n\'est pas valide');
    }

    // Validation du téléphone
    if (!empty($telephone) && !preg_match('/^[0-9]{10,15}$/', $telephone)) {
        throw new Exception('Le téléphone doit contenir 10 à 15 chiffres');
    }

    // Création ou mise à jour
    if ($id) {
        $result = $horaire->updateEnseignant($id, $nom, $prenom, $email, $telephone, $statut, $specialite);
        $message = 'Enseignant mis à jour avec succès';
    } else {
        $result = $horaire->createEnseignant($nom, $prenom, $email, $telephone, $statut, $specialite);
        $message = 'Enseignant créé avec succès';
    }

    if (!$result) {
        throw new Exception('Aucune modification n\'a été effectuée');
    }

    echo json_encode(['success' => true, 'message' => $message]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}