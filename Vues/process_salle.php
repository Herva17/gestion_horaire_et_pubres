<?php
require_once '../Models/Config.php';
require_once '../Models/MaClasse.php';

header('Content-Type: application/json');

try {
    session_start();
    $pdo = getPDO();
    $manager = new Horaire($pdo);

    // Vérification CSRF
    $tokenHeader = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    $tokenPost = $_POST['csrf_token'] ?? '';
    $tokenSession = $_SESSION['csrf_token'] ?? '';

    if (empty($tokenHeader) && empty($tokenPost)) {
        throw new Exception("Token CSRF manquant");
    }

    if ($tokenHeader !== $tokenSession && $tokenPost !== $tokenSession) {
        throw new Exception("Token CSRF invalide");
    }

    $method = $_SERVER['REQUEST_METHOD'];
    $data = $_POST;

    if ($method === 'POST' && empty($data['_method'])) {
        // CREATE
        $required = ['designation'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Le champ $field est requis");
            }
        }

        $success = $manager->createSalle(
            htmlspecialchars($data['designation']),
            htmlspecialchars($data['description_salle'] ?? '')
        );

        if (!$success) {
            throw new Exception("Erreur lors de la création de la salle");
        }

        echo json_encode(['success' => true, 'message' => 'Salle créée avec succès']);
    } elseif (($method === 'POST' && isset($data['_method']) && $data['_method'] === 'PUT') || $method === 'PUT') {
        // UPDATE
        if (empty($data['id'])) {
            throw new Exception("ID de la salle manquant");
        }

        $id = filter_var($data['id'], FILTER_VALIDATE_INT);
        if ($id === false) {
            throw new Exception("ID de la salle invalide");
        }

        $success = $manager->updateSalle(
            $id,
            htmlspecialchars($data['designation']),
            htmlspecialchars($data['description_salle'] ?? '')
        );

        if (!$success) {
            throw new Exception("Erreur lors de la mise à jour de la salle");
        }

        echo json_encode(['success' => true, 'message' => 'Salle mise à jour avec succès']);
    } elseif (($method === 'POST' && isset($data['_method']) && $data['_method'] === 'DELETE') || $method === 'DELETE') {
        // DELETE
        if (empty($data['id'])) {
            throw new Exception("ID de la salle manquant");
        }

        $id = filter_var($data['id'], FILTER_VALIDATE_INT);
        if ($id === false) {
            throw new Exception("ID de la salle invalide");
        }

        $success = $manager->deleteSalle($id);
        if (!$success) {
            throw new Exception("Erreur lors de la suppression de la salle");
        }

        echo json_encode(['success' => true, 'message' => 'Salle supprimée avec succès']);
    } else {
        throw new Exception("Méthode non autorisée");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}