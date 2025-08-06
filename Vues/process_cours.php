<?php
require_once '../Models/Config.php';
require_once '../Models/MaClasse.php';

header('Content-Type: application/json');

try {
    $pdo = getPDO();
    $manager = new Horaire($pdo);

    // Vérification CSRF token
    if (!isset($_SERVER['HTTP_X_CSRF_TOKEN']) || $_SERVER['HTTP_X_CSRF_TOKEN'] !== $_COOKIE['csrf_token']) {
        throw new Exception("Token CSRF invalide");
    }

    $method = $_SERVER['REQUEST_METHOD'];
    $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    if ($method === 'POST' && empty($data['_method'])) {
        // CREATE
        $required = ['titre', 'Description', 'id_enseignant', 'id_promotion', 'id_typeCours', 'volume_horaire'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Le champ $field est requis");
            }
        }

        $success = $manager->createCours(
            $data['titre'],
            $data['Description'],
            $data['id_enseignant'],
            $data['id_promotion'],
            $data['id_typeCours'],
            $data['id_salle'] ?? null,
            $data['volume_horaire'],
            $data['Coefficient'] ?? null,
            $data['Credit'] ?? null
        );

        if (!$success) {
            throw new Exception("Erreur lors de la création du cours");
        }

        echo json_encode(['success' => true, 'message' => 'Cours créé avec succès']);
    } elseif (($method === 'POST' && isset($data['_method']) && $data['_method'] === 'PUT') || $method === 'PUT') {
        // UPDATE
        if (empty($data['id'])) {
            throw new Exception("ID du cours manquant");
        }

        $id = filter_var($data['id'], FILTER_VALIDATE_INT);
        if ($id === false) {
            throw new Exception("ID du cours invalide");
        }

        $success = $manager->updateCours(
            $id,
            $data['titre'],
            $data['Description'],
            $data['id_enseignant'],
            $data['id_promotion'],
            $data['id_typeCours'],
            $data['id_salle'] ?? null,
            $data['volume_horaire'],
            $data['Coefficient'] ?? null,
            $data['Credit'] ?? null
        );

        if (!$success) {
            throw new Exception("Erreur lors de la mise à jour du cours");
        }

        echo json_encode(['success' => true, 'message' => 'Cours mis à jour avec succès']);
    } elseif (($method === 'POST' && isset($data['_method']) && $data['_method'] === 'DELETE') || $method === 'DELETE') {
        // DELETE
        if (empty($data['id'])) {
            throw new Exception("ID du cours manquant");
        }

        $id = filter_var($data['id'], FILTER_VALIDATE_INT);
        if ($id === false) {
            throw new Exception("ID du cours invalide");
        }

        $success = $manager->deleteCours($id);
        if (!$success) {
            throw new Exception("Erreur lors de la suppression du cours");
        }

        echo json_encode(['success' => true, 'message' => 'Cours supprimé avec succès']);
    } else {
        throw new Exception("Méthode non autorisée");
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}