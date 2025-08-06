<?php
session_start();
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = getPDO();
    $horaire = new Horaire($pdo);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée', 405);
    }

    // Récupération des données
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data === null) {
        $data = $_POST;
    }

    // Validation des données
    $id = !empty($data['id']) ? (int)$data['id'] : null;
    $nom = trim($data['nom'] ?? '');
    $description = trim($data['description'] ?? null); // Modification ici
    $duree = trim($data['duree_par_seance'] ?? null);

    if (empty($nom)) {
        throw new Exception('Le nom du type de cours est obligatoire', 400);
    }

    // Création ou mise à jour
    if ($id) {
        $result = $horaire->updateTypeCours($id, $nom, $description, $duree);
        $message = 'Type de cours mis à jour avec succès';
    } else {
        $result = $horaire->createTypeCours($nom, $description, $duree);
        $message = 'Type de cours créé avec succès';
    }

    if (!$result) {
        throw new Exception('Aucune modification n\'a été effectuée', 500);
    }

    $_SESSION['flash'] = [
        'type' => 'success',
        'message' => $message
    ];

    echo json_encode([
        'success' => true, 
        'message' => $message,
        'redirect' => 'cours.php' // Ajout d'une redirection
    ]);

} catch (Exception $e) {
    http_response_code($e->getCode() >= 400 && $e->getCode() < 500 ? $e->getCode() : 500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}