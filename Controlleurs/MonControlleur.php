<?php
require_once("../Models/MaClasse.php");
session_start();



// Vérifier si les données sont envoyées via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        // =============== GESTION ENSEIGNANTS ===============
        case 'add_teacher':
            try {
                $result = $gestion->createEnseignant(
                    htmlspecialchars($_POST['nom']),
                    htmlspecialchars($_POST['prenom']),
                    filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                    htmlspecialchars($_POST['telephone']),
                    htmlspecialchars($_POST['statut'])
                );
                
                if ($result) {
                    $_SESSION['success'] = "Enseignant ajouté avec succès!";
                } else {
                    $_SESSION['error'] = "Erreur lors de l'ajout de l'enseignant";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Erreur: " . $e->getMessage();
            }
            header("Location: gestion_enseignants.php");
            exit();
            
        case 'update_teacher':
            try {
                $result = $gestion->updateEnseignant(
                    intval($_POST['id']),
                    htmlspecialchars($_POST['nom']),
                    htmlspecialchars($_POST['prenom']),
                    filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                    htmlspecialchars($_POST['telephone']),
                    htmlspecialchars($_POST['statut'])
                );
                
                if ($result) {
                    $_SESSION['success'] = "Enseignant mis à jour avec succès!";
                } else {
                    $_SESSION['error'] = "Erreur lors de la mise à jour";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Erreur: " . $e->getMessage();
            }
            header("Location: gestion_enseignants.php");
            exit();
            
        case 'delete_teacher':
            try {
                $result = $gestion->deleteEnseignant(intval($_POST['id']));
                
                if ($result) {
                    $_SESSION['success'] = "Enseignant supprimé avec succès!";
                } else {
                    $_SESSION['error'] = "Erreur lors de la suppression";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Erreur: " . $e->getMessage();
            }
            header("Location: gestion_enseignants.php");
            exit();
            
        // =============== GESTION DISPONIBILITES ===============
        case 'add_availability':
            try {
                $result = $gestion->createDisponibiliteEnseignant(
                    intval($_POST['id_enseignant']),
                    htmlspecialchars($_POST['jour_semaine']),
                    htmlspecialchars($_POST['heure_debut']),
                    htmlspecialchars($_POST['heure_fin']),
                    isset($_POST['indisponible']) ? 1 : 0,
                    htmlspecialchars($_POST['raison'])
                );
                
                if ($result) {
                    $_SESSION['success'] = "Disponibilité enregistrée avec succès!";
                } else {
                    $_SESSION['error'] = "Erreur lors de l'enregistrement";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Erreur: " . $e->getMessage();
            }
            header("Location: gestion_disponibilites.php");
            exit();
            
        case 'update_availability':
            try {
                $result = $gestion->updateDisponibiliteEnseignant(
                    intval($_POST['id']),
                    intval($_POST['id_enseignant']),
                    htmlspecialchars($_POST['jour_semaine']),
                    htmlspecialchars($_POST['heure_debut']),
                    htmlspecialchars($_POST['heure_fin']),
                    isset($_POST['indisponible']) ? 1 : 0,
                    htmlspecialchars($_POST['raison'])
                );
                
                if ($result) {
                    $_SESSION['success'] = "Disponibilité mise à jour avec succès!";
                } else {
                    $_SESSION['error'] = "Erreur lors de la mise à jour";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Erreur: " . $e->getMessage();
            }
            header("Location: gestion_disponibilites.php");
            exit();
            
        case 'delete_availability':
            try {
                $result = $gestion->deleteDisponibiliteEnseignant(intval($_POST['id']));
                
                if ($result) {
                    $_SESSION['success'] = "Disponibilité supprimée avec succès!";
                } else {
                    $_SESSION['error'] = "Erreur lors de la suppression";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Erreur: " . $e->getMessage();
            }
            header("Location: gestion_disponibilites.php");
            exit();
            
        default:
            $_SESSION['error'] = "Action non reconnue";
            header("Location: index.php");
            exit();
    }
}

// Récupération des données pour affichage
$enseignants = $gestion->getEnseignants();
$disponibilites = $gestion->getDisponibilitesEnseignant();
?>