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
    $enseignant_id = (int)$_POST['enseignant_id'];
    $jour = trim($_POST['jour'] ?? '');
    $heure_debut = trim($_POST['heure_debut'] ?? '');
    $heure_fin = trim($_POST['heure_fin'] ?? '');
    $disponible = isset($_POST['disponible']) ? (bool)$_POST['disponible'] : true;
    $raison = trim($_POST['raison'] ?? '');

    // Validation des champs obligatoires
    if (empty($jour) || empty($heure_debut) || empty($heure_fin)) {
        throw new Exception('Tous les champs sont obligatoires');
    }

    // Validation des heures
    if (strtotime($heure_debut) >= strtotime($heure_fin)) {
        throw new Exception('L\'heure de fin doit être après l\'heure de début');
    }

    // Si indisponible, la raison est obligatoire
    if (!$disponible && empty($raison)) {
        throw new Exception('La raison est obligatoire pour une indisponibilité');
    }

    // Création ou mise à jour
    if ($id) {
        $result = $horaire->updateDisponibilite($id, $jour, $heure_debut, $heure_fin);
        $message = 'Disponibilité mise à jour avec succès';
    } else {
        $result = $horaire->createDisponibiliteEnseignant($jour, $heure_debut, $heure_fin, $enseignant_id);
        $message = 'Disponibilité créée avec succès';
    }

    if (!$result) {
        throw new Exception('Aucune modification n\'a été effectuée');
    }

    echo json_encode(['success' => true, 'message' => $message]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}