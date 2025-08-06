<?php
session_start();
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

// Activation des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = getPDO();
    $horaireManager = new Horaire($pdo);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            // Validation des données
            $requiredFields = ['date_debut', 'date_fin', 'heure_debut', 'heure_fin', 'frequence', 'id_salle', 'id_promotion', 'id_cours', 'id_enseignant'];

            // Vérification des champs requis
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Le champ $field est requis");
                }
            }

            // Validation des dates
            $dateDebut = strtotime($_POST['date_debut']);
            $dateFin = strtotime($_POST['date_fin']);

            if ($dateFin < $dateDebut) {
                throw new Exception("La date de fin doit être postérieure ou égale à la date de début");
            }

            // Construction du tableau $data après validation
            $data = [
                'date_debut' => $_POST['date_debut'],
                'date_fin' => $_POST['date_fin'],
                'heure_debut' => $_POST['heure_debut'],
                'heure_fin' => $_POST['heure_fin'],
                'frequence' => htmlspecialchars($_POST['frequence']),
                'id_salle' => (int)$_POST['id_salle'],
                'id_promotion' => (int)$_POST['id_promotion'],
                'id_cours' => (int)$_POST['id_cours'],
                'id_secretaire' => !empty($_POST['id_secretaire']) ? (int)$_POST['id_secretaire'] : null,
                'id_enseignant' => (int)$_POST['id_enseignant']
            ];

            if ($_POST['action'] === 'add') {
                if ($horaireManager->createHoraire(
                    $data['date_debut'],
                    $data['date_fin'],
                    $data['heure_debut'],
                    $data['heure_fin'],
                    $data['frequence'],
                    $data['id_salle'],
                    $data['id_promotion'],
                    $data['id_cours'],
                    $data['id_secretaire'],
                    $data['id_enseignant']
                )) {
                    $_SESSION['message'] = "Horaire ajouté avec succès";
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit;
                }
            } elseif ($_POST['action'] === 'edit' && isset($_POST['id'])) {
                if ($horaireManager->updateHoraire(
                    (int)$_POST['id'],
                    $data['date_debut'],
                    $data['date_fin'],
                    $data['heure_debut'],
                    $data['heure_fin'],
                    $data['frequence'],
                    $data['id_salle'],
                    $data['id_promotion'],
                    $data['id_cours'],
                    $data['id_secretaire'],
                    $data['id_enseignant']
                )) {
                    $_SESSION['message'] = "Horaire mis à jour avec succès";
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit;
                }
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}

// Traitement de la suppression
if (isset($_GET['delete'])) {
    try {
        if ($horaireManager->deleteHoraire((int)$_GET['delete'])) {
            $_SESSION['message'] = "Horaire supprimé avec succès";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Récupération des données
try {
    $horaires = $horaireManager->getHoraires();
    $promotions = $horaireManager->getPromotions();
    $salles = $horaireManager->getSalles();
    $cours = $horaireManager->getCoursWithDetails();
    $enseignants = $horaireManager->getEnseignants();
    $secretaires = $horaireManager->getSecretaires();
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur lors du chargement des données : " . $e->getMessage();
}

include_once("header.php");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Horaires</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .management-content {
            width: 1100px;
            max-width: 98vw;
            margin: 0 auto;
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 2px 16px rgba(58, 59, 69, 0.10);
            padding: 2rem;
            margin-top: 20px;
            margin-left: -220px;
        }

        .management-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
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

        .data-table {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 2px 16px rgba(58, 59, 69, 0.08);
            overflow: auto;
            margin-bottom: 2rem;
            max-height: 700px;
        }

        table {
            margin-bottom: 0;
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
        }

        table thead th {
            background-color: #f8f9fc;
            color: #2d3a4b;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e3e6f0;
            padding: 0.75rem;
            text-align: center;
        }

        table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
            text-align: center;
        }

        table tbody tr:nth-of-type(odd) {
            background-color: #f4f6fb;
        }

        table tbody tr:hover {
            background-color: #e9ecef;
            transition: background 0.2s;
        }

        .section-row {
            background: #e3e6f0;
            font-weight: bold;
            color: #007bff;
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
            overflow: auto;
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 600px;
            min-width: 320px;
            max-height: 170vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            animation: fadeIn 0.3s;
            position: relative;
            top: 0;
            left: 0;
        }

        .modal-header {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            margin: 0;
            font-size: 1.25rem;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 1rem;
            padding: 0 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
        }

        .modal-footer {
            padding: 1rem;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .btn-danger {
            background-color: #dc3545;
            color: #fff;
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

        .alert {
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }

        @media (max-width: 900px) {
            .management-content {
                width: 99vw;
                padding: 1rem;
                margin-left: 0;
            }

            table {
                font-size: 0.95rem;
            }
        }
    </style>
</head>

<body>
    <main class="main-content">
        <div class="management-content">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="management-header">
                <h1><i class="fas fa-calendar-alt me-2"></i>Horaire Institutionnel</h1>
                <button class="btn btn-primary" id="addHoraireBtn">
                    <i class="fas fa-plus me-2"></i>Ajouter Horaire
                </button>
            </div>

            <div class="data-table">
                <?php if (empty($horaires)): ?>
                    <div class="alert alert-info">Aucun horaire enregistré</div>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Section</th>
                                <th>Promotion</th>
                                <th>Cours</th>
                                <th>Enseignant</th>
                                <th>Salle</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Heure début</th>
                                <th>Heure fin</th>
                                <th>Fréquence</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $currentSection = '';
                            foreach ($horaires as $horaire):
                                $section = $horaireManager->getPromotionById($horaire['id_promotion'])['section_nom'];

                                if ($section != $currentSection):
                                    $currentSection = $section;
                            ?>
                                    <tr class="section-row">
                                        <td colspan="11">Section : <?= htmlspecialchars($currentSection) ?></td>
                                    </tr>
                                <?php endif; ?>

                                <tr>
                                    <td><?= htmlspecialchars($currentSection) ?></td>
                                    <td><?= htmlspecialchars($horaire['promotion_nom']) ?></td>
                                    <td><?= htmlspecialchars($horaire['cours_nom']) ?></td>
                                    <td><?= htmlspecialchars($horaire['enseignant_nom']) ?></td>
                                    <td><?= htmlspecialchars($horaire['salle_nom']) ?></td>
                                    <td><?= htmlspecialchars($horaire['date_debut']) ?></td>
                                    <td><?= htmlspecialchars($horaire['date_fin']) ?></td>
                                    <td><?= substr($horaire['heure_debut'], 0, 5) ?></td>
                                    <td><?= substr($horaire['heure_fin'], 0, 5) ?></td>
                                    <td><?= htmlspecialchars($horaire['frequence']) ?></td>
                                    <td class="action-btns">
                                        <button class="edit-btn"
                                            data-id="<?= $horaire['id'] ?>"
                                            data-date_debut="<?= $horaire['date_debut'] ?>"
                                            data-date_fin="<?= $horaire['date_fin'] ?>"
                                            data-heure_debut="<?= $horaire['heure_debut'] ?>"
                                            data-heure_fin="<?= $horaire['heure_fin'] ?>"
                                            data-frequence="<?= $horaire['frequence'] ?>"
                                            data-id_salle="<?= $horaire['id_salle'] ?>"
                                            data-id_promotion="<?= $horaire['id_promotion'] ?>"
                                            data-id_cours="<?= $horaire['id_cours'] ?>"
                                            data-id_secretaire="<?= $horaire['id_secretaire'] ?>"
                                            data-id_enseignant="<?= $horaire['id_enseignant'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-btn" data-id="<?= $horaire['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Modal pour Ajout/Modification Horaire -->
    <div class="modal" id="horaireModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Ajouter un Horaire</h3>
                <button type="button" class="close-btn">&times;</button>
            </div>
            <form id="horaireForm" method="post" action="">
                <input type="hidden" id="horaireId" name="id">
                <input type="hidden" name="action" id="formAction" value="add">

                <!-- Dates -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="dateDebut" class="form-label">Date de début *</label>
                        <input type="date" id="dateDebut" name="date_debut" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="dateFin" class="form-label">Date de fin *</label>
                        <input type="date" id="dateFin" name="date_fin" class="form-control" required>
                    </div>
                </div>

                <!-- Plage horaire -->
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="heureDebut" class="form-label">Heure de début *</label>
                        <input type="time" id="heureDebut" name="heure_debut" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="heureFin" class="form-label">Heure de fin *</label>
                        <input type="time" id="heureFin" name="heure_fin" class="form-control" required>
                    </div>
                </div>

                <!-- Fréquence -->
                <div class="form-group">
                    <label for="frequence" class="form-label">Fréquence *</label>
                    <select id="frequence" name="frequence" class="form-control" required>
                        <option value="">Sélectionner la fréquence</option>
                        <option value="Hebdomadaire">Hebdomadaire</option>
                        <option value="Quotidienne">Quotidienne</option>
                        <option value="Mensuelle">Mensuelle</option>
                        <option value="Ponctuelle">Ponctuelle</option>
                    </select>
                </div>

                <!-- Salle -->
                <div class="form-group">
                    <label for="salleSelect" class="form-label">Salle *</label>
                    <select id="salleSelect" name="id_salle" class="form-control" required>
                        <option value="">Sélectionner une salle</option>
                        <?php foreach ($salles as $salle): ?>
                            <option value="<?= $salle['id'] ?>">
                                <?= htmlspecialchars($salle['designation']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Promotion -->
                <div class="form-group">
                    <label for="promotionSelect" class="form-label">Promotion *</label>
                    <select id="promotionSelect" name="id_promotion" class="form-control" required>
                        <option value="">Sélectionner une promotion</option>
                        <?php foreach ($promotions as $promotion): ?>
                            <option value="<?= $promotion['id'] ?>" data-section="<?= htmlspecialchars($promotion['section_nom'] ?? '') ?>">
                                <?= htmlspecialchars($promotion['nom']) ?> (<?= htmlspecialchars($promotion['section_nom'] ?? 'Non attribuée') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Cours -->
                <div class="form-group">
                    <label for="coursSelect" class="form-label">Cours *</label>
                    <select id="coursSelect" name="id_cours" class="form-control" required>
                        <option value="">Sélectionner un cours</option>
                        <?php foreach ($cours as $coursItem): ?>
                            <option value="<?= $coursItem['id'] ?>">
                                <?= htmlspecialchars($coursItem['titre']) ?> - Enseignant: <?= htmlspecialchars($coursItem['enseignant_nom'] ?? 'Non attribué') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Enseignant -->
                <div class="form-group">
                    <label for="enseignantSelect" class="form-label">Enseignant *</label>
                    <select id="enseignantSelect" name="id_enseignant" class="form-control" required>
                        <option value="">Sélectionner un enseignant</option>
                        <?php foreach ($enseignants as $enseignant): ?>
                            <option value="<?= $enseignant['id'] ?>">
                                <?= htmlspecialchars($enseignant['nom']) ?>
                                <?php if (!empty($enseignant['matiere'])): ?>
                                    - <?= htmlspecialchars($enseignant['matiere']) ?>
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Secrétaire (optionnel) -->
                <div class="form-group">
                    <label for="secretaireSelect" class="form-label">Secrétaire</label>
                  <select id="secretaireSelect" name="id_secretaire" class="form-control">
    <option value="">Sélectionner un secrétaire (optionnel)</option>
    <?php foreach ($secretaires as $secretaire): ?>
        <option value="<?= $secretaire['id'] ?>">
            <?= htmlspecialchars($secretaire['nom']) ?> 
            <?php if (!empty($secretaire['prenom'])): ?>
                <?= htmlspecialchars($secretaire['prenom']) ?>
            <?php endif; ?>
            - Section: <?= htmlspecialchars($secretaire['section_nom'] ?? $secretaire['nom_section'] ?? 'Non attribuée') ?>
        </option>
    <?php endforeach; ?>
</select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cancel-btn">Annuler</button>
                    <button type="submit" class="btn btn-primary save-btn">
                        <span class="btn-text">Enregistrer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal" id="confirmDeleteModal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3 class="modal-title">Confirmation de suppression</h3>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cet horaire ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-delete-btn">Annuler</button>
                <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Supprimer</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion de la modal
            const addHoraireBtn = document.getElementById('addHoraireBtn');
            const horaireModal = document.getElementById('horaireModal');
            const confirmDeleteModal = document.getElementById('confirmDeleteModal');
            const closeButtons = document.querySelectorAll('.close-btn, .cancel-btn, .cancel-delete-btn');

            // Bouton d'ajout
            addHoraireBtn.addEventListener('click', () => {
                document.getElementById('modalTitle').textContent = "Ajouter un Horaire";
                document.getElementById('formAction').value = "add";
                document.getElementById('horaireForm').reset();
                horaireModal.style.display = 'flex';
            });

            // Boutons d'édition
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', () => {
                    document.getElementById('modalTitle').textContent = "Modifier un Horaire";
                    document.getElementById('formAction').value = "edit";

                    // Remplir le formulaire avec les données
                    document.getElementById('horaireId').value = button.dataset.id;
                    document.getElementById('dateDebut').value = button.dataset.date_debut;
                    document.getElementById('dateFin').value = button.dataset.date_fin;
                    document.getElementById('heureDebut').value = button.dataset.heure_debut;
                    document.getElementById('heureFin').value = button.dataset.heure_fin;
                    document.getElementById('frequence').value = button.dataset.frequence;
                    document.getElementById('promotionSelect').value = button.dataset.id_promotion;
                    document.getElementById('coursSelect').value = button.dataset.id_cours;
                    document.getElementById('enseignantSelect').value = button.dataset.id_enseignant;
                    document.getElementById('salleSelect').value = button.dataset.id_salle;
                    document.getElementById('secretaireSelect').value = button.dataset.id_secretaire || '';

                    horaireModal.style.display = 'flex';
                });
            });

            // Boutons de suppression
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    document.getElementById('confirmDeleteBtn').href = `?delete=${id}`;
                    confirmDeleteModal.style.display = 'flex';
                });
            });

            // Fermeture des modals
            closeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    horaireModal.style.display = 'none';
                    confirmDeleteModal.style.display = 'none';
                });
            });

            // Fermeture en cliquant à l'extérieur
            window.addEventListener('click', (event) => {
                if (event.target === horaireModal) horaireModal.style.display = 'none';
                if (event.target === confirmDeleteModal) confirmDeleteModal.style.display = 'none';
            });

            // Validation avant soumission
            document.getElementById('horaireForm').addEventListener('submit', function(e) {
                const requiredFields = ['date_debut', 'date_fin', 'heure_debut', 'heure_fin', 'frequence', 'id_salle', 'id_promotion', 'id_cours', 'id_enseignant'];
                let isValid = true;

                requiredFields.forEach(field => {
                    const element = this.querySelector(`[name="${field}"]`);
                    if (!element.value) {
                        element.style.borderColor = 'red';
                        isValid = false;
                    } else {
                        element.style.borderColor = '';
                    }
                });

                // Validation des dates
                const dateDebut = new Date(document.getElementById('dateDebut').value);
                const dateFin = new Date(document.getElementById('dateFin').value);

                if (dateFin < dateDebut) {
                    alert('La date de fin doit être postérieure ou égale à la date de début');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>