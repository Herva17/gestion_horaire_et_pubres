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
                    // Gestion des secrétaires
                    case 'add_secretaire':
                        $manager->createSecretaire(
                            $_POST['nom'],
                            $_POST['prenom'],
                            $_POST['sexe'],
                            $_POST['adressemail'],
                            $_POST['telephone'],
                            password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT),
                            $_POST['Adresse'],
                            $_POST['grade'],
                            $_POST['id_section']
                        );
                        $_SESSION['flash'] = ['message' => 'Secrétaire ajouté avec succès', 'type' => 'success'];
                        break;

                    case 'edit_secretaire':
                        $manager->updateSecretaire(
                            $_POST['id'],
                            $_POST['nom'],
                            $_POST['prenom'],
                            $_POST['sexe'],
                            $_POST['adressemail'],
                            $_POST['telephone'],
                            !empty($_POST['mot_de_passe']) ? password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT) : null,
                            $_POST['Adresse'],
                            $_POST['grade'],
                            $_POST['id_section']
                        );
                        $_SESSION['flash'] = ['message' => 'Secrétaire modifié avec succès', 'type' => 'success'];
                        break;

                    case 'delete_secretaire':
                        $manager->deleteSecretaire($_POST['id']);
                        $_SESSION['flash'] = ['message' => 'Secrétaire supprimé avec succès', 'type' => 'success'];
                        break;

                    // Gestion des étudiants
                    case 'add_etudiant':
                        $manager->createEtudiant(
                            $_POST['nom'],
                            $_POST['prenom'],
                            $_POST['Adressemail'],
                            $_POST['matricule'],
                            $_POST['telephone'],
                            $_POST['sexe'],
                            $_POST['id_promotion']
                        );
                        $_SESSION['flash'] = ['message' => 'Étudiant ajouté avec succès', 'type' => 'success'];
                        break;

                    case 'edit_etudiant':
                        $manager->updateEtudiant(
                            $_POST['matricule'],
                            $_POST['nom'],
                            $_POST['prenom'],
                            $_POST['Adressemail'],
                            $_POST['telephone'],
                            $_POST['sexe'],
                            $_POST['id_promotion']
                        );
                        $_SESSION['flash'] = ['message' => 'Étudiant modifié avec succès', 'type' => 'success'];
                        break;

                    case 'delete_etudiant':
                        $manager->deleteEtudiant($_POST['matricule']);
                        $_SESSION['flash'] = ['message' => 'Étudiant supprimé avec succès', 'type' => 'success'];
                        break;

                    // Gestion des enseignants
                    case 'add_enseignant':
                        $manager->createEnseignant(
                            $_POST['nom'],
                            $_POST['prenom'],
                            $_POST['email'],
                            password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT),
                            $_POST['telephone'],
                            $_POST['statut'],
                            $_POST['specialite']
                        );
                        $_SESSION['flash'] = ['message' => 'Enseignant ajouté avec succès', 'type' => 'success'];
                        break;

                    case 'edit_enseignant':
                        $manager->updateEnseignant(
                            $_POST['id'],
                            $_POST['nom'],
                            $_POST['prenom'],
                            $_POST['email'],
                            !empty($_POST['mot_de_passe']) ? password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT) : null,
                            $_POST['telephone'],
                            $_POST['statut'],
                            $_POST['specialite']
                        );
                        $_SESSION['flash'] = ['message' => 'Enseignant modifié avec succès', 'type' => 'success'];
                        break;

                    case 'delete_enseignant':
                        $manager->deleteEnseignant($_POST['id']);
                        $_SESSION['flash'] = ['message' => 'Enseignant supprimé avec succès', 'type' => 'success'];
                        break;
                }
            } catch (PDOException $e) {
                $_SESSION['flash'] = ['message' => 'Erreur: ' . $e->getMessage(), 'type' => 'error'];
            }
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }

    // Récupérer les données nécessaires
    $enseignants = $manager->getEnseignants();
    $secretaires = $manager->getSecretaires();
    $etudiants = $manager->getEtudiants();
    $promotions = $manager->getPromotions();
    $sections = $manager->getSections();
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
    <title>Gestion des Utilisateurs</title>
    <style>
        /* [Votre CSS reste inchangé] */
        .flash-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 5px;
            color: white;
            z-index: 10000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        .flash-message.success {
            background: #4CAF50;
        }

        .flash-message.error {
            background: #F44336;
        }

        .management-content {
            width: 950px;
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 2px 16px rgba(58, 59, 69, 0.10);
            padding: 2rem;
            margin-top: 20px;
            margin-left: -200px;
        }

        .management-header {
            margin-bottom: 2rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }

        .management-header h1 {
            color: #2d3a4b;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0;
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
        }

        .tab-btn.active {
            color: #007bff;
            border-bottom-color: #007bff;
        }

        .data-table {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 2px 16px rgba(58, 59, 69, 0.08);
            overflow: auto;
            margin-bottom: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead th {
            background-color: #f8f9fc;
            color: #2d3a4b;
            font-weight: 700;
            padding: 0.75rem;
            text-align: left;
        }

        table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
        }

        table tbody tr:nth-of-type(odd) {
            background-color: #f4f6fb;
        }

        .action-btns {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            font-weight: 600;
            padding: 0.375rem 0.75rem;
            border-radius: 0.35rem;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .btn-success {
            background-color: #28a745;
            color: #fff;
        }

        .btn-outline-primary {
            background: transparent;
            color: #007bff;
            border: 1px solid #007bff;
        }

        .btn-outline-danger {
            background: transparent;
            color: #dc3545;
            border: 1px solid #dc3545;
        }

        .btn-outline-info {
            background: transparent;
            color: #17a2b8;
            border: 1px solid #17a2b8;
        }

        .badge {
            font-weight: 600;
            padding: 0.35em 0.65em;
            font-size: 0.85em;
            border-radius: 0.25em;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        .bg-info {
            background-color: #17a2b8 !important;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 1.25rem;
            border-bottom: 1px solid #e3e6f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .close-btn:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 1.25rem;
        }

        .modal-footer {
            padding: 1.25rem;
            border-top: 1px solid #e3e6f0;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control,
        .form-select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            font-size: 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -0.75rem;
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 0.75rem;
        }

        @media (max-width: 768px) {
            .management-content {
                padding: 1rem;
            }

            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .tab-btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .action-btns {
                flex-direction: column;
            }
        }

        .tab-content {
            display: none;
            padding: 15px 0;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .password-field {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <main class="main-content">
        <div class="management-content">
            <?php if (isset($flash)): ?>
                <div class="flash-message <?= $flash['type'] === 'error' ? 'error' : 'success' ?>">
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
                <script>
                    setTimeout(() => {
                        document.querySelector('.flash-message').style.opacity = '0';
                        setTimeout(() => document.querySelector('.flash-message').remove(), 500);
                    }, 5000);
                </script>
            <?php endif; ?>

            <div class="management-header">
                <h1 class="mb-0">
                    <i class="fas fa-users-cog me-2"></i>Gestion des Utilisateurs
                </h1>
                <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto" style="justify-content: flex-end;">
                    <a href="?show=secretaire_form" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Secrétaire
                    </a>
                    <a href="?show=etudiant_form" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Étudiant
                    </a>
                    <a href="?show=enseignant_form" class="btn btn-info">
                        <i class="fas fa-plus me-2"></i>Enseignant
                    </a>
                </div>
            </div>

            <div class="tabs">
                <a href="?tab=secretaires" class="tab-btn <?= (!isset($_GET['tab'])) || $_GET['tab'] === 'secretaires' ? 'active' : '' ?>">Secrétaires</a>
                <a href="?tab=etudiants" class="tab-btn <?= isset($_GET['tab']) && $_GET['tab'] === 'etudiants' ? 'active' : '' ?>">Étudiants</a>
                <a href="?tab=enseignants" class="tab-btn <?= isset($_GET['tab']) && $_GET['tab'] === 'enseignants' ? 'active' : '' ?>">Enseignants</a>
            </div>

            <?php if (!isset($_GET['show'])): ?>
                <!-- Onglet Secrétaires -->
                <div class="tab-content <?= (!isset($_GET['tab']) )|| $_GET['tab'] === 'secretaires' ? 'active' : '' ?>" id="secretaires-tab">
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Grade</th>
                                    <th>Section</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($secretaires as $secretaire): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($secretaire['id']) ?></td>
                                        <td><?= htmlspecialchars($secretaire['nom']) ?></td>
                                        <td><?= htmlspecialchars($secretaire['prenom']) ?></td>
                                        <td><?= htmlspecialchars($secretaire['adressmail']) ?></td>
                                        <td><?= htmlspecialchars($secretaire['telephone']) ?></td>
                                        <td><?= htmlspecialchars($secretaire['grade']) ?></td>
                                        <td><?= htmlspecialchars($secretaire['section_nom'] ?? 'Non attribué') ?></td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="?edit_secretaire=<?= $secretaire['id'] ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="" style="display:inline">
                                                    <input type="hidden" name="action" value="delete_secretaire">
                                                    <input type="hidden" name="id" value="<?= $secretaire['id'] ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Confirmer la suppression de ce secrétaire?')">
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

                <!-- Onglet Étudiants -->
                <div class="tab-content <?= isset($_GET['tab']) && $_GET['tab'] === 'etudiants' ? 'active' : '' ?>" id="etudiants-tab">
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Matricule</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Promotion</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($etudiants as $etudiant): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($etudiant['id']) ?></td>
                                        <td><?= htmlspecialchars($etudiant['matricule']) ?></td>
                                        <td><?= htmlspecialchars($etudiant['nom']) ?></td>
                                        <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
                                        <td><?= htmlspecialchars($etudiant['Adressemail']) ?></td>
                                        <td><?= htmlspecialchars($etudiant['telephone']) ?></td>
                                        <td><?= htmlspecialchars($etudiant['promotion_nom'] ?? 'Non attribué') ?></td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="?edit_etudiant=<?= $etudiant['id'] ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="" style="display:inline">
                                                    <input type="hidden" name="action" value="delete_etudiant">
                                                    <input type="hidden" name="id" value="<?= $etudiant['id'] ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Confirmer la suppression de cet étudiant?')">
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

                <!-- Onglet Enseignants -->
                <div class="tab-content <?= isset($_GET['tab']) && $_GET['tab'] === 'enseignants' ? 'active' : '' ?>" id="enseignants-tab">
                    <div class="data-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Spécialité</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($enseignants as $enseignant): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($enseignant['id']) ?></td>
                                        <td><?= htmlspecialchars($enseignant['nom']) ?></td>
                                        <td><?= htmlspecialchars($enseignant['prenom']) ?></td>
                                        <td><?= htmlspecialchars($enseignant['email']) ?></td>
                                        <td><?= htmlspecialchars($enseignant['telephone']) ?></td>
                                        <td><?= htmlspecialchars($enseignant['specialite']) ?></td>
                                        <td>
                                            <span class="badge <?= $enseignant['statut'] === 'Permanent' ? 'bg-success' : ($enseignant['statut'] === 'Vacataire' ? 'bg-warning' : 'bg-info') ?>">
                                                <?= htmlspecialchars($enseignant['statut']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="?edit_enseignant=<?= $enseignant['id'] ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="" style="display:inline">
                                                    <input type="hidden" name="action" value="delete_enseignant">
                                                    <input type="hidden" name="id" value="<?= $enseignant['id'] ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Confirmer la suppression de cet enseignant?')">
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

            <!-- Formulaire Secrétaire -->
            <?php if (isset($_GET['show']) && $_GET['show'] === 'secretaire_form' || isset($_GET['edit_secretaire'])): ?>
                <?php
                $secretaire = null;
                if (isset($_GET['edit_secretaire'])) {
                    $secretaire = $manager->getSecretaireById($_GET['edit_secretaire']);
                }
                ?>
                <div class="modal" style="display:flex">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h3 class="modal-title"><?= $secretaire ? 'Modifier' : 'Ajouter' ?> un Secrétaire</h3>
                            <a href="?" class="close-btn text-white">&times;</a>
                        </div>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="<?= $secretaire ? 'edit_secretaire' : 'add_secretaire' ?>">
                            <?php if ($secretaire): ?>
                                <input type="hidden" name="id" value="<?= $secretaire['id'] ?>">
                            <?php endif; ?>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="secretaireNom" class="form-label">Nom *</label>
                                            <input type="text" class="form-control" id="secretaireNom" name="nom"
                                                value="<?= $secretaire ? htmlspecialchars($secretaire['nom']) : '' ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="secretairePrenom" class="form-label">Prénom *</label>
                                            <input type="text" class="form-control" id="secretairePrenom" name="prenom"
                                                value="<?= $secretaire ? htmlspecialchars($secretaire['prenom']) : '' ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="secretaireSexe" class="form-label">Sexe *</label>
                                            <select class="form-control" id="secretaireSexe" name="sexe" required>
                                                <option value="">Sélectionner...</option>
                                                <option value="M" <?= ($secretaire && $secretaire['sexe'] === 'M') ? 'selected' : '' ?>>Masculin</option>
                                                <option value="F" <?= ($secretaire && $secretaire['sexe'] === 'F') ? 'selected' : '' ?>>Féminin</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="secretaireEmail" class="form-label">Email *</label>
                                            <input type="email" class="form-control" id="secretaireEmail" name="adressemail"
                                                value="<?= $secretaire ? htmlspecialchars($secretaire['adressmail']) : '' ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="secretaireTelephone" class="form-label">Téléphone</label>
                                            <input type="tel" class="form-control" id="secretaireTelephone" name="telephone"
                                                value="<?= $secretaire ? htmlspecialchars($secretaire['telephone']) : '' ?>">
                                        </div>
                                    </div>
                                    <?php if (!$secretaire): ?>
                                        <div class="col-md-6">
                                            <div class="form-group password-field">
                                                <label for="secretairePassword" class="form-label">Mot de passe *</label>
                                                <input type="password" class="form-control" id="secretairePassword" name="mot_de_passe" required>
                                                <i class="fas fa-eye password-toggle" onclick="togglePassword('secretairePassword')"></i>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="secretaireAdresse" class="form-label">Adresse</label>
                                            <textarea class="form-control" id="secretaireAdresse" name="Adresse" rows="2"><?= $secretaire ? htmlspecialchars($secretaire['Adresse'] ?? '') : '' ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="secretaireGrade" class="form-label">Grade</label>
                                            <input type="text" class="form-control" id="secretaireGrade" name="grade"
                                                value="<?= $secretaire ? htmlspecialchars($secretaire['grade']) : '' ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="secretaireSection" class="form-label">Section</label>
                                    <select class="form-control" id="secretaireSection" name="id_section">
                                        <option value="">Sélectionner une section</option>
                                        <?php foreach ($sections as $section): ?>
                                            <option value="<?= $section['id'] ?>" <?= ($secretaire && $secretaire['id_section'] == $section['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($section['nom']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
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

            <!-- Formulaire Étudiant -->
            <?php if (isset($_GET['show']) && $_GET['show'] === 'etudiant_form' || isset($_GET['edit_etudiant'])): ?>
                <?php
                $etudiant = null;
                if (isset($_GET['edit_etudiant'])) {
                    $etudiant = $manager->getEtudiantById($_GET['edit_etudiant']);
                }
                ?>
                <div class="modal" style="display:flex">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h3 class="modal-title"><?= $etudiant ? 'Modifier' : 'Ajouter' ?> un Étudiant</h3>
                            <a href="?" class="close-btn text-white">&times;</a>
                        </div>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="<?= $etudiant ? 'edit_etudiant' : 'add_etudiant' ?>">
                            <?php if ($etudiant): ?>
                                <input type="hidden" name="id" value="<?= $etudiant['id'] ?>">
                            <?php endif; ?>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="etudiantNom" class="form-label">Nom *</label>
                                            <input type="text" class="form-control" id="etudiantNom" name="nom"
                                                value="<?= $etudiant ? htmlspecialchars($etudiant['nom']) : '' ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="etudiantPrenom" class="form-label">Prénom *</label>
                                            <input type="text" class="form-control" id="etudiantPrenom" name="prenom"
                                                value="<?= $etudiant ? htmlspecialchars($etudiant['prenom']) : '' ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="etudiantEmail" class="form-label">Email *</label>
                                            <input type="email" class="form-control" id="etudiantEmail" name="Adressemail"
                                                value="<?= $etudiant ? htmlspecialchars($etudiant['Adressmail']) : '' ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="etudiantMatricule" class="form-label">Matricule *</label>
                                            <input type="text" class="form-control" id="etudiantMatricule" name="matricule"
                                                value="<?= $etudiant ? htmlspecialchars($etudiant['matricule']) : '' ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="etudiantTelephone" class="form-label">Téléphone</label>
                                            <input type="tel" class="form-control" id="etudiantTelephone" name="telephone"
                                                value="<?= $etudiant ? htmlspecialchars($etudiant['telephone']) : '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="etudiantSexe" class="form-label">Sexe *</label>
                                            <select class="form-control" id="etudiantSexe" name="sexe" required>
                                                <option value="">Sélectionner...</option>
                                                <option value="M" <?= ($etudiant && $etudiant['sexe'] === 'M') ? 'selected' : '' ?>>Masculin</option>
                                                <option value="F" <?= ($etudiant && $etudiant['sexe'] === 'F') ? 'selected' : '' ?>>Féminin</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="etudiantPromotion" class="form-label">Promotion *</label>
                                    <select class="form-control" id="etudiantPromotion" name="id_promotion" required>
                                        <option value="">Sélectionner une promotion</option>
                                        <?php foreach ($promotions as $promotion): ?>
                                            <option value="<?= $promotion['id'] ?>" <?= ($etudiant && $etudiant['id_promotion'] == $promotion['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($promotion['nom']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
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

            <!-- Formulaire Enseignant -->
            <?php if (isset($_GET['show']) && $_GET['show'] === 'enseignant_form' || isset($_GET['edit_enseignant'])): ?>
                <?php
                $enseignant = null;
                if (isset($_GET['edit_enseignant'])) {
                    $enseignant = $manager->getEnseignantById($_GET['edit_enseignant']);
                }
                ?>
                <div class="modal" style="display:flex">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h3 class="modal-title"><?= $enseignant ? 'Modifier' : 'Ajouter' ?> un Enseignant</h3>
                            <a href="?" class="close-btn text-white">&times;</a>
                        </div>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="<?= $enseignant ? 'edit_enseignant' : 'add_enseignant' ?>">
                            <?php if ($enseignant): ?>
                                <input type="hidden" name="id" value="<?= $enseignant['id'] ?>">
                            <?php endif; ?>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="enseignantNom" class="form-label">Nom *</label>
                                            <input type="text" class="form-control" id="enseignantNom" name="nom"
                                                value="<?= $enseignant ? htmlspecialchars($enseignant['nom']) : '' ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="enseignantPrenom" class="form-label">Prénom *</label>
                                            <input type="text" class="form-control" id="enseignantPrenom" name="prenom"
                                                value="<?= $enseignant ? htmlspecialchars($enseignant['prenom']) : '' ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="enseignantEmail" class="form-label">Email *</label>
                                            <input type="email" class="form-control" id="enseignantEmail" name="email"
                                                value="<?= $enseignant ? htmlspecialchars($enseignant['email']) : '' ?>" required>
                                        </div>
                                    </div>
                                    <?php if (!$enseignant): ?>
                                        <div class="col-md-6">
                                            <div class="form-group password-field">
                                                <label for="enseignantPassword" class="form-label">Mot de passe *</label>
                                                <input type="password" class="form-control" id="enseignantPassword" name="mot_de_passe" required>
                                                <i class="fas fa-eye password-toggle" onclick="togglePassword('enseignantPassword')"></i>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="col-md-6">
                                            <div class="form-group password-field">
                                                <label for="enseignantPasswordEdit" class="form-label">Nouveau mot de passe</label>
                                                <small class="text-muted">(Laisser vide pour ne pas modifier)</small>
                                                <input type="password" class="form-control" id="enseignantPasswordEdit" name="mot_de_passe">
                                                <i class="fas fa-eye password-toggle" onclick="togglePassword('enseignantPasswordEdit')"></i>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="enseignantTelephone" class="form-label">Téléphone</label>
                                            <input type="tel" class="form-control" id="enseignantTelephone" name="telephone"
                                                value="<?= $enseignant ? htmlspecialchars($enseignant['telephone']) : '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="enseignantStatut" class="form-label">Statut *</label>
                                            <select class="form-control" id="enseignantStatut" name="statut" required>
                                                <option value="">Sélectionner...</option>
                                                <option value="Permanent" <?= ($enseignant && $enseignant['statut'] === 'Permanent') ? 'selected' : '' ?>>Permanent</option>
                                                <option value="Vacataire" <?= ($enseignant && $enseignant['statut'] === 'Vacataire') ? 'selected' : '' ?>>Vacataire</option>
                                                <option value="Invité" <?= ($enseignant && $enseignant['statut'] === 'Invité') ? 'selected' : '' ?>>Invité</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="enseignantSpecialite" class="form-label">Spécialité *</label>
                                    <input type="text" class="form-control" id="enseignantSpecialite" name="specialite"
                                        value="<?= $enseignant ? htmlspecialchars($enseignant['specialite']) : '' ?>" required>
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

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const flashMessage = document.querySelector('.flash-message');
            if (flashMessage) {
                setTimeout(() => {
                    flashMessage.style.opacity = '0';
                    setTimeout(() => flashMessage.remove(), 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>