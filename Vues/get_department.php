<?php
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

if (isset($_GET['id'])) {
    $pdo = getPDO();
    $horaire = new Horaire($pdo);
    $department = $horaire->getDepartementById($_GET['id']);
    
    header('Content-Type: application/json');
    echo json_encode($department);
}
?>