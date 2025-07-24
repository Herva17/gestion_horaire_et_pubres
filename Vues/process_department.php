<?php
session_start();
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

// Activer le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = getPDO();
    $horaire = new Horaire($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validation des données
        $nom = trim($_POST['nom'] ?? '');
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;

        if (empty($nom)) {
            throw new Exception("Le nom du département est requis");
        }

        // Journalisation pour débogage
        error_log("Tentative de " . ($id ? "mise à jour" : "création") . " de département: $nom");

        // Opération de création/mise à jour
        if ($id) {
            $result = $horaire->updateDepartement($id, $nom);
            $message = "Département mis à jour avec succès";
        } else {
            $result = $horaire->createDepartement($nom);
            $message = "Département créé avec succès";
        }

        if (!$result) {
            throw new Exception("Aucune modification n'a été effectuée");
        }

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => $message
        ];
        
        // Journalisation du succès
        error_log("Opération réussie: $message");
    }
} catch (PDOException $e) {
    $errorMsg = "Erreur base de données: " . $e->getMessage();
    error_log($errorMsg);
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => $errorMsg
    ];
} catch (Exception $e) {
    $errorMsg = "Erreur: " . $e->getMessage();
    error_log($errorMsg);
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => $errorMsg
    ];
}

header('Location: departement.php');
exit;
?>