<?php
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

header('Content-Type: application/json');

try {
    $pdo = getPDO();
    $horaire = new Horaire($pdo);
    
    $sql = "SELECT de.*, e.nom AS enseignant_nom, e.prenom AS enseignant_prenom 
            FROM disponibiliteenseignant de
            JOIN enseignants e ON de.id_enseignant = e.id
            ORDER BY e.nom, e.prenom, de.jour, de.heure_debut";
    
    $stmt = $pdo->query($sql);
    $disponibilites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($disponibilites);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}