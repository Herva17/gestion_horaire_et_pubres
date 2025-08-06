<?php
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

// Vérifier la connexion à la base de données
try {
    $pdo = getPDO();
    $manager = new Horaire($pdo);
    
    // Gérer les actions CRUD
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            try {
                switch ($_POST['action']) {
                    // Gestion des cours
                    case 'add_cours':
                        $manager->createCours(
                            $_POST['titre'],
                            $_POST['Description'],
                            $_POST['id_enseignant'],
                            $_POST['id_promotion'],
                            $_POST['id_typeCours'],
                            $_POST['id_salle'] ?: null,
                            $_POST['volume_horaire'],
                            $_POST['Coefficient'] ?: null,
                            $_POST['Credit'] ?: null
                        );
                        $_SESSION['flash'] = ['message' => 'Cours ajouté avec succès', 'type' => 'success'];
                        break;
                        
                    case 'edit_cours':
                        $manager->updateCours(
                            $_POST['id'],
                            $_POST['titre'],
                            $_POST['Description'],
                            $_POST['id_enseignant'],
                            $_POST['id_promotion'],
                            $_POST['id_typeCours'],
                            $_POST['id_salle'] ?: null,
                            $_POST['volume_horaire'],
                            $_POST['Coefficient'] ?: null,
                            $_POST['Credit'] ?: null
                        );
                        $_SESSION['flash'] = ['message' => 'Cours modifié avec succès', 'type' => 'success'];
                        break;
                        
                    case 'delete_cours':
                        $manager->deleteCours($_POST['id']);
                        $_SESSION['flash'] = ['message' => 'Cours supprimé avec succès', 'type' => 'success'];
                        break;
                        
                    // Gestion des types de cours
                    case 'add_type':
                        $manager->createTypeCours(
                            $_POST['nom'],
                            $_POST['Description'],
                            $_POST['duree_par_seance']
                        );
                        $_SESSION['flash'] = ['message' => 'Type de cours ajouté avec succès', 'type' => 'success'];
                        break;
                        
                    case 'edit_type':
                        $manager->updateTypeCours(
                            $_POST['id'],
                            $_POST['nom'],
                            $_POST['Description'],
                            $_POST['duree_par_seance']
                        );
                        $_SESSION['flash'] = ['message' => 'Type de cours modifié avec succès', 'type' => 'success'];
                        break;
                        
                    case 'delete_type':
                        $manager->deleteTypeCours($_POST['id']);
                        $_SESSION['flash'] = ['message' => 'Type de cours supprimé avec succès', 'type' => 'success'];
                        break;
                        
                    // Gestion des salles
                    case 'add_salle':
                        $manager->createSalle(
                            $_POST['designation'],
                            $_POST['description_salle']
                        );
                        $_SESSION['flash'] = ['message' => 'Salle ajoutée avec succès', 'type' => 'success'];
                        break;
                        
                    case 'edit_salle':
                        $manager->updateSalle(
                            $_POST['id'],
                            $_POST['designation'],
                            $_POST['description_salle']
                        );
                        $_SESSION['flash'] = ['message' => 'Salle modifiée avec succès', 'type' => 'success'];
                        break;
                        
                    case 'delete_salle':
                        $manager->deleteSalle($_POST['id']);
                        $_SESSION['flash'] = ['message' => 'Salle supprimée avec succès', 'type' => 'success'];
                        break;
                }
            } catch (PDOException $e) {
                $_SESSION['flash'] = ['message' => 'Erreur: ' . $e->getMessage(), 'type' => 'error'];
            }
            header('Location: '.$_SERVER['PHP_SELF']);
            exit;
        }
    }

    // Récupérer les données
    $typesCours = $manager->getTypesCours();
    $salles = $manager->getSalles();
    $cours = $manager->getCoursWithDetails();
    $enseignants = $manager->getEnseignants();
    $promotions = $manager->getPromotions();
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Gérer les messages flash
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
include_once("header.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Cours</title>
    <style>
        .management-content {
            width: 950px;
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 2px 16px rgba(58, 59, 69, 0.10);
            padding: 2rem;
            margin-left: -200px;
            margin-top: 30px;
        }

        .management-header {
            background-color: transparent;
            border-radius: 0.5rem;
            box-shadow: none;
            padding: 0;
            margin-bottom: 2rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        .management-header h1 {
            color: #2d3a4b;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tabs {
            border-bottom: 2px solid #e3e6f0;
            margin-bottom: 2rem;
            display: flex;
            gap: 0.5rem;
        }

        .tab-btn {
            padding: 0.75rem 2rem;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            color: #6c757d;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            cursor: pointer;
            outline: none;
        }

        .tab-btn:hover,
        .tab-btn:focus {
            color: #007bff;
            background-color: #f8f9fc;
        }

        .tab-btn.active {
            color: #007bff;
            border-bottom-color: #007bff;
            background-color: #f8f9fc;
        }

        .data-table {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 2px 16px rgba(58, 59, 69, 0.08);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        table {
            margin-bottom: 0;
            width: 100%;
            border-collapse: collapse;
        }

        table thead th {
            background-color: #f8f9fc;
            color: #2d3a4b;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e3e6f0;
            padding: 0.75rem;
        }

        table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
        }

        table tbody tr:nth-of-type(odd) {
            background-color: #f4f6fb;
        }

        table tbody tr:hover {
            background-color: #e9ecef;
            transition: background 0.2s;
        }

        .action-btns button {
            margin-right: 0.5rem;
            font-size: 0.95rem;
            padding: 0.25rem 0.75rem;
            border-radius: 0.35rem;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .edit-btn {
            background: #007bff;
            color: #fff;
        }

        .delete-btn {
            background: #dc3545;
            color: #fff;
        }

        .edit-btn:hover {
            background: #0056b3;
        }

        .delete-btn:hover {
            background: #a71d2a;
        }

        .btn {
            font-weight: 600;
            padding: 0.375rem 0.75rem;
            border-radius: 0.35rem;
            border: none;
            transition: all 0.2s;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .btn-success {
            background-color: #28a745;
            color: #fff;
        }

        .btn-info {
            background-color: #17a2b8;
            color: #fff;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fff;
            margin: 2rem auto;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 500px;
            max-height: 600px;
            overflow-y: auto;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 1.25rem 2rem;
            border-bottom: 1px solid #e3e6f0;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-weight: 700;
            font-size: 1.35rem;
            margin: 0;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            font-weight: 700;
            opacity: 0.5;
            transition: opacity 0.2s;
            cursor: pointer;
        }

        .close-btn:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            padding: 1.25rem 2rem;
            border-top: 1px solid #e3e6f0;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #2d3a4b;
        }

        input,
        select,
        textarea {
            border-radius: 0.35rem;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d3e2;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            width: 100%;
            font-size: 1rem;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 16px 4px;
            }

            .management-content {
                padding: 1rem;
            }

            .management-header {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }

            .tab-btn {
                padding: 0.5rem 1rem;
                font-size: 0.95rem;
            }

            .modal-content {
                margin: 1rem auto;
                width: 98%;
                padding: 0.5rem;
            }

            .modal-body,
            .modal-header,
            .modal-footer {
                padding: 1rem;
            }
        }

        .flash-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 5px;
            color: white;
            z-index: 10000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.5s forwards;
        }

        .flash-message.success {
            background-color: #28a745;
        }

        .flash-message.error {
            background-color: #dc3545;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
    </style>
<body>
    <main class="main-content">
        <div class="management-content">
            <!-- Message flash -->
            <?php if (isset($flash)): ?>
                <div class="flash-message <?= $flash['type'] === 'error' ? 'error' : 'success' ?>">
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
                <script>
                    // Faire disparaître le message après 5 secondes
                    setTimeout(() => {
                        document.querySelector('.flash-message').style.animation = 'fadeOut 0.5s forwards';
                    }, 5000);
                </script>
            <?php endif; ?>

            <div class="management-header">
                <h1 class="mb-0">
                    <i class="fas fa-book me-2"></i>Gestion des Cours
                </h1>
                <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto" style="justify-content: flex-end;">
                    <a href="?show=type_cours_form" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Type Cours
                    </a>
                    <a href="?show=salle_form" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Salle
                    </a>
                    <a href="?show=cours_form" class="btn btn-info">
                        <i class="fas fa-plus me-2"></i>Cours
                    </a>
                </div>
            </div>

            <!-- Onglets -->
            <div class="tabs">
                <a href="?tab=typesCours" class="tab-btn <?= (!isset($_GET['tab']) || $_GET['tab'] === 'typesCours') ? 'active' : '' ?>">Types de Cours</a>
                <a href="?tab=salles" class="tab-btn <?= (isset($_GET['tab']) && $_GET['tab'] === 'salles') ? 'active' : '' ?>">Salles</a>
                <a href="?tab=cours" class="tab-btn <?= (isset($_GET['tab']) && $_GET['tab'] === 'cours') ? 'active' : '' ?>">Cours</a>
            </div>

            <!-- Contenu des onglets -->
            <?php if (!isset($_GET['show'])): ?>
                <!-- Onglet Types de Cours -->
                <div class="tab-content <?= (!isset($_GET['tab']) || $_GET['tab'] === 'typesCours') ? 'active' : '' ?>" id="typesCours-tab">
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Durée/séance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($typesCours as $type): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($type['id']) ?></td>
                                        <td><?= htmlspecialchars($type['nom']) ?></td>
                                        <td><?= htmlspecialchars($type['Description'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($type['duree_par_seance'] ?? '') ?> min</td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="?edit_type=<?= $type['id'] ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="" style="display:inline">
                                                    <input type="hidden" name="action" value="delete_type">
                                                    <input type="hidden" name="id" value="<?= $type['id'] ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Confirmer la suppression de ce type de cours?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Onglet Salles -->
                <div class="tab-content <?= (isset($_GET['tab']) && $_GET['tab'] === 'salles') ? 'active' : '' ?>" id="salles-tab">
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Désignation</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($salles as $salle): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($salle['id']) ?></td>
                                        <td><?= htmlspecialchars($salle['designation']) ?></td>
                                        <td><?= htmlspecialchars($salle['description_salle'] ?? '') ?></td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="?edit_salle=<?= $salle['id'] ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="" style="display:inline">
                                                    <input type="hidden" name="action" value="delete_salle">
                                                    <input type="hidden" name="id" value="<?= $salle['id'] ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Confirmer la suppression de cette salle?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Onglet Cours -->
                <div class="tab-content <?= (isset($_GET['tab']) && $_GET['tab'] === 'cours') ? 'active' : '' ?>" id="cours-tab">
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Volume Horaire</th>
                                    <th>Coefficient</th>
                                    <th>Crédits</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cours as $c): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($c['id']) ?></td>
                                        <td><?= htmlspecialchars($c['titre']) ?></td>
                                        <td><?= htmlspecialchars($c['Description'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($c['volume_horaire']) ?>h</td>
                                        <td><?= htmlspecialchars($c['Coefficient']) ?></td>
                                        <td><?= htmlspecialchars($c['Credit']) ?></td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="?edit_cours=<?= $c['id'] ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="" style="display:inline">
                                                    <input type="hidden" name="action" value="delete_cours">
                                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Confirmer la suppression de ce cours?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulaire Type de Cours -->
            <?php if (isset($_GET['show']) && $_GET['show'] === 'type_cours_form' || isset($_GET['edit_type'])): ?>
                <?php
                $type = null;
                if (isset($_GET['edit_type'])) {
                    $type = $manager->getTypeCoursById($_GET['edit_type']);
                }
                ?>
                <div class="modal" style="display:flex">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h3 class="modal-title"><?= $type ? 'Modifier' : 'Ajouter' ?> un Type de Cours</h3>
                            <a href="?" class="close-btn text-white">&times;</a>
                        </div>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="<?= $type ? 'edit_type' : 'add_type' ?>">
                            <?php if ($type): ?>
                                <input type="hidden" name="id" value="<?= $type['id'] ?>">
                            <?php endif; ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="typeNom" class="form-label">Nom *</label>
                                    <input type="text" class="form-control" id="typeNom" name="nom" 
                                        value="<?= $type ? htmlspecialchars($type['nom']) : '' ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="typeDescription" class="form-label">Description</label>
                                    <textarea class="form-control" id="typeDescription" name="Description" rows="3"><?= 
                                        $type ? htmlspecialchars($type['Description'] ?? '') : '' ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="typeDuree" class="form-label">Durée par séance (minutes) *</label>
                                    <input type="number" class="form-control" id="typeDuree" name="duree_par_seance" 
                                        value="<?= $type ? htmlspecialchars($type['duree_par_seance']) : '' ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="?" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulaire Salle -->
            <?php if (isset($_GET['show']) && $_GET['show'] === 'salle_form' || isset($_GET['edit_salle'])): ?>
                <?php
                $salle = null;
                if (isset($_GET['edit_salle'])) {
                    $salle = $manager->getSalleById($_GET['edit_salle']);
                }
                ?>
                <div class="modal" style="display:flex">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h3 class="modal-title"><?= $salle ? 'Modifier' : 'Ajouter' ?> une Salle</h3>
                            <a href="?" class="close-btn text-white">&times;</a>
                        </div>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="<?= $salle ? 'edit_salle' : 'add_salle' ?>">
                            <?php if ($salle): ?>
                                <input type="hidden" name="id" value="<?= $salle['id'] ?>">
                            <?php endif; ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="salleDesignation" class="form-label">Désignation *</label>
                                    <input type="text" class="form-control" id="salleDesignation" name="designation" 
                                        value="<?= $salle ? htmlspecialchars($salle['designation']) : '' ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="salleDescription" class="form-label">Description</label>
                                    <textarea class="form-control" id="salleDescription" name="description_salle" rows="3"><?= 
                                        $salle ? htmlspecialchars($salle['description_salle'] ?? '') : '' ?></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="?" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-success">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulaire Cours -->
            <?php if (isset($_GET['show']) && $_GET['show'] === 'cours_form' || isset($_GET['edit_cours'])): ?>
                <?php
                $cours = null;
                if (isset($_GET['edit_cours'])) {
                    $cours = $manager->getCoursById($_GET['edit_cours']);
                }
                ?>
                <div class="modal" style="display:flex">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h3 class="modal-title"><?= $cours ? 'Modifier' : 'Ajouter' ?> un Cours</h3>
                            <a href="?" class="close-btn text-white">&times;</a>
                        </div>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="<?= $cours ? 'edit_cours' : 'add_cours' ?>">
                            <?php if ($cours): ?>
                                <input type="hidden" name="id" value="<?= $cours['id'] ?>">
                            <?php endif; ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="coursTitre" class="form-label">Titre *</label>
                                    <input type="text" class="form-control" id="coursTitre" name="titre" 
                                        value="<?= $cours ? htmlspecialchars($cours['titre']) : '' ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="coursDescription" class="form-label">Description</label>
                                    <textarea class="form-control" id="coursDescription" name="Description" rows="3"><?= 
                                        $cours ? htmlspecialchars($cours['Description'] ?? '') : '' ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="coursEnseignant" class="form-label">Enseignant *</label>
                                    <select class="form-control" id="coursEnseignant" name="id_enseignant" required>
                                        <option value="">Sélectionner un enseignant</option>
                                        <?php foreach ($enseignants as $enseignant): ?>
                                            <option value="<?= $enseignant['id'] ?>" <?= 
                                                ($cours && $cours['id_enseignant'] == $enseignant['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($enseignant['nom'] . ' ' . ($enseignant['prenom'] ?? '')) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="coursPromotion" class="form-label">Promotion *</label>
                                    <select class="form-control" id="coursPromotion" name="id_promotion" required>
                                        <option value="">Sélectionner une promotion</option>
                                        <?php foreach ($promotions as $promotion): ?>
                                            <option value="<?= $promotion['id'] ?>" <?= 
                                                ($cours && $cours['id_promotion'] == $promotion['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($promotion['nom']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="coursType" class="form-label">Type de Cours *</label>
                                    <select class="form-control" id="coursType" name="id_typeCours" required>
                                        <option value="">Sélectionner un type</option>
                                        <?php foreach ($typesCours as $type): ?>
                                            <option value="<?= $type['id'] ?>" <?= 
                                                ($cours && $cours['id_typeCours'] == $type['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($type['nom']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="coursSalle" class="form-label">Salle</label>
                                    <select class="form-control" id="coursSalle" name="id_salle">
                                        <option value="">Sélectionner une salle</option>
                                        <?php foreach ($salles as $salle): ?>
                                            <option value="<?= $salle['id'] ?>" <?= 
                                                ($cours && $cours['id_salle'] == $salle['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($salle['designation']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="coursVolume" class="form-label">Volume Horaire *</label>
                                    <input type="text" class="form-control" id="coursVolume" name="volume_horaire" 
                                        value="<?= $cours ? htmlspecialchars($cours['volume_horaire']) : '' ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="coursCoefficient" class="form-label">Coefficient</label>
                                    <input type="number" step="0.01" class="form-control" id="coursCoefficient" name="Coefficient" 
                                        value="<?= $cours ? htmlspecialchars($cours['Coefficient']) : '' ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="coursCredit" class="form-label">Crédits</label>
                                    <input type="number" class="form-control" id="coursCredit" name="Credit" 
                                        value="<?= $cours ? htmlspecialchars($cours['Credit']) : '' ?>">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="?" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-info">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>