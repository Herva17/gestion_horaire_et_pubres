<?php
session_start();
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

try {
    $pdo = getPDO();
    $horaireManager = new Horaire($pdo);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupération des données
try {
    $horaires = $horaireManager->getHoraires();
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur lors du chargement des données : " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du temps par Section</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .print-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .print-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .print-btn:hover {
            background-color: #2980b9;
        }
        
        .section-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 40px;
            overflow: hidden;
        }
        
        .section-header {
            background-color: #3498db;
            color: white;
            padding: 15px 20px;
            font-size: 1.4em;
        }
        
        .timetable {
            width: 100%;
            border-collapse: collapse;
        }
        
        .timetable th {
            background-color: #2980b9;
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: 500;
        }
        
        .timetable td {
            padding: 12px;
            border: 1px solid #e0e0e0;
            text-align: center;
        }
        
        .timetable tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .date-cell {
            font-weight: bold;
            min-width: 120px;
        }
        
        .time-cell {
            font-weight: bold;
            color: #2c3e50;
            min-width: 100px;
        }
        
        .course-cell {
            text-align: left;
            min-width: 200px;
        }
        
        .course-name {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .promotion-name {
            font-size: 0.9em;
            color: #7f8c8d;
        }
        
        .teacher-name, .room-name {
            font-size: 0.9em;
        }
        
        @media print {
            body {
                background-color: white;
                padding: 0;
                font-size: 12pt;
            }
            
            .no-print {
                display: none;
            }
            
            .section-container {
                box-shadow: none;
                page-break-inside: avoid;
                margin-bottom: 20pt;
            }
            
            .timetable {
                font-size: 10pt;
            }
            
            .timetable th, .timetable td {
                padding: 8pt;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="header no-print">
            <h1><i class="fas fa-calendar-alt"></i> Emploi du temps par Section</h1>
            <button class="print-btn" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimer
            </button>
        </div>
        
        <?php if (!empty($horaires)): 
            // Organiser les horaires par section
            $sections = [];
            foreach ($horaires as $horaire) {
                $section = $horaireManager->getPromotionById($horaire['id_promotion'])['section_nom'];
                $sections[$section][] = $horaire;
            }
            
            foreach ($sections as $sectionName => $sectionHoraires): ?>
                <div class="section-container">
                    <div class="section-header">
                        Section : <?= htmlspecialchars($sectionName) ?>
                    </div>
                    
                    <table class="timetable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Cours</th>
                                <th>Enseignant</th>
                                <th>Salle</th>
                                <th>Fréquence</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sectionHoraires as $horaire): ?>
                                <tr>
                                    <td class="date-cell">
                                        <?= date('d/m/Y', strtotime($horaire['date_debut'])) ?>
                                        <?php if ($horaire['date_debut'] != $horaire['date_fin']): ?>
                                            <br>au <?= date('d/m/Y', strtotime($horaire['date_fin'])) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="time-cell">
                                        <?= substr($horaire['heure_debut'], 0, 5) ?>
                                        <br>-<br>
                                        <?= substr($horaire['heure_fin'], 0, 5) ?>
                                    </td>
                                    <td class="course-cell">
                                        <span class="course-name"><?= htmlspecialchars($horaire['cours_nom']) ?></span>
                                        <br>
                                        <span class="promotion-name"><?= htmlspecialchars($horaire['promotion_nom']) ?></span>
                                    </td>
                                    <td>
                                        <span class="teacher-name"><?= htmlspecialchars($horaire['enseignant_nom']) ?></span>
                                    </td>
                                    <td>
                                        <span class="room-name"><?= htmlspecialchars($horaire['salle_nom']) ?></span>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($horaire['frequence']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; 
        else: ?>
            <div class="alert alert-info">Aucun horaire enregistré</div>
        <?php endif; ?>
    </div>
</body>
</html>