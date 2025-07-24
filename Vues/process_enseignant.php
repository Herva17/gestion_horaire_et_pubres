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
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $statut = trim($_POST['statut'] ?? '');
        $specialite = trim($_POST['specialite'] ?? '');

        // Validation des champs obligatoires
        if (empty($nom) || empty($prenom)) {
            throw new Exception("Le nom et le prénom sont obligatoires");
        }

        // Validation de l'email si fourni
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'adresse email n'est pas valide");
        }

        // Journalisation pour débogage
        error_log("Tentative de " . ($id ? "mise à jour" : "création") . " d'enseignant: $nom $prenom");

        // Opération de création/mise à jour
        if ($id) {
            $result = $horaire->updateEnseignant($id, $nom, $prenom, $email, $telephone, $statut, $specialite);
            $message = "Enseignant mis à jour avec succès";
        } else {
            $result = $horaire->createEnseignant($nom, $prenom, $email, $telephone, $statut, $specialite);
            $message = "Enseignant créé avec succès";
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

header('Location: enseignant.php');
exit;
?>