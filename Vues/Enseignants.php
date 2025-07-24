<?php 
include_once("header.php");
require_once '../Models/MaClasse.php';
require_once '../Models/Config.php';

// Vérifier la connexion à la base de données
try {
    $pdo = getPDO();
    $horaire = new Horaire($pdo);
    $enseignants = $horaire->getEnseignants(); // Assurez-vous que cette méthode existe dans MaClasse.php
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Gérer les messages flash
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}
?>

<style>
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

    .form-control, .form-select {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        font-size: 1rem;
    }

    .form-control:focus, .form-select:focus {
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
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
<main class="main-content">
    <div class="management-content">
        <!-- Message flash -->
        <?php if (isset($flash)): ?>
            <div class="flash-message <?= $flash['type'] === 'error' ? 'error' : 'success' ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <!-- En-tête -->
        <div class="management-header">
            <h1><i class="fas fa-chalkboard-teacher me-2"></i>Gestion des Enseignants</h1>
            <div>
                <button class="btn btn-primary" id="addTeacherBtn">
                    <i class="fas fa-plus me-2"></i>Ajouter Enseignant
                </button>
            </div>
        </div>

        <!-- Onglets -->
        <div class="tabs">
            <button class="tab-btn active" data-tab="teachers">Enseignants</button>
            <!-- <button class="tab-btn" data-tab="availabilities">Disponibilité enseignant</button> -->
        </div>

        <!-- Contenu des onglets -->
        <div class="tab-content active" id="teachers-tab">
            <!-- Tableau des enseignants -->
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Specialité</th>
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
                                <span class="badge 
                                    <?= $enseignant['statut'] === 'Permanent' ? 'bg-success' : 
                                       ($enseignant['statut'] === 'Vacataire' ? 'bg-warning' : 'bg-info') ?>">
                                    <?= htmlspecialchars($enseignant['statut']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <button class="btn btn-outline-primary btn-sm edit-btn" 
                                            data-id="<?= $enseignant['id'] ?>"
                                            title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm delete-btn" 
                                            data-id="<?= $enseignant['id'] ?>"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-sm availability-btn" 
                                            data-id="<?= $enseignant['id'] ?>"
                                            title="Disponibilités">
                                        <i class="fas fa-calendar-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-content" id="availabilities-tab">
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>Enseignant</th>
                            <th>Jour</th>
                            <th>Heure début</th>
                            <th>Heure fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="allAvailabilitiesTableBody">
                        <!-- Les disponibilités seront chargées ici -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Modal Ajout/Modification Enseignant -->
<div class="modal" id="teacherModal">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h3 class="modal-title">Ajouter un Enseignant</h3>
            <button class="close-btn text-white">&times;</button>
        </div>
        <form id="teacherForm" action="process_enseignant.php" method="POST">
            <input type="hidden" name="id" id="teacherId">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="teacherLastName" class="form-label">Nom *</label>
                            <input type="text" class="form-control" id="teacherLastName" name="nom" required
                                   minlength="2" maxlength="50" pattern="[A-Za-zÀ-ÿ\s\-]+"
                                   title="Lettres, espaces et tirets seulement">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="teacherFirstName" class="form-label">Prénom *</label>
                            <input type="text" class="form-control" id="teacherFirstName" name="prenom" required
                                   minlength="2" maxlength="50" pattern="[A-Za-zÀ-ÿ\s\-]+"
                                   title="Lettres, espaces et tirets seulement">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="teacherEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="teacherEmail" name="email"
                                   maxlength="100" placeholder="exemple@domain.com">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="teacherPhone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="teacherPhone" name="telephone"
                                   pattern="[0-9]{10,15}" title="10 à 15 chiffres seulement"
                                   placeholder="0612345678">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="teacherStatus" class="form-label">Statut *</label>
                            <select class="form-select" id="teacherStatus" name="statut" required>
                                <option value="">Sélectionner...</option>
                                <option value="Permanent">Permanent</option>
                                <option value="Vacataire">Vacataire</option>
                                <option value="Invité">Invité</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="teacherSpecialty" class="form-label">Spécialité</label>
                            <input type="text" class="form-control" id="teacherSpecialty" name="specialite"
                                   maxlength="100">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-btn">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Disponibilité -->
<div class="modal" id="availabilityModal">
    <div class="modal-content">
        <div class="modal-header bg-success text-white">
            <h3 class="modal-title">Gestion des Disponibilités</h3>
            <button class="close-btn text-white">&times;</button>
        </div>
        <form id="availabilityForm" action="process_disponibilite.php" method="POST">
            <input type="hidden" name="enseignant_id" id="availTeacherId">
            <input type="hidden" name="id" id="availabilityId">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="availDay" class="form-label">Jour *</label>
                            <select class="form-select" id="availDay" name="jour" required>
                                <option value="">Sélectionner...</option>
                                <option value="Lundi">Lundi</option>
                                <option value="Mardi">Mardi</option>
                                <option value="Mercredi">Mercredi</option>
                                <option value="Jeudi">Jeudi</option>
                                <option value="Vendredi">Vendredi</option>
                                <option value="Samedi">Samedi</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="availStatus" class="form-label">Disponible</label>
                            <div class="form-check form-switch" style="padding-left: 2.5rem; margin-top: 0.5rem;">
                                <input class="form-check-input" type="checkbox" id="availStatus" name="disponible" checked>
                                <label class="form-check-label" for="availStatus">Disponible</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="availStart" class="form-label">Heure de début *</label>
                            <input type="time" class="form-control" id="availStart" name="heure_debut" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="availEnd" class="form-label">Heure de fin *</label>
                            <input type="time" class="form-control" id="availEnd" name="heure_fin" required>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="reasonGroup" style="display: none;">
                    <label for="availReason" class="form-label">Raison d'indisponibilité *</label>
                    <input type="text" class="form-control" id="availReason" name="raison">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-btn">Annuler</button>
                <button type="submit" class="btn btn-success">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Visualisation Disponibilités -->
<div class="modal" id="viewAvailabilityModal">
    <div class="modal-content">
        <div class="modal-header bg-info text-white">
            <h3 class="modal-title">Disponibilités de l'enseignant</h3>
            <button class="close-btn text-white">&times;</button>
        </div>
        <div class="modal-body">
            <div class="text-end mb-3">
                <button class="btn btn-sm btn-primary" id="addNewAvailabilityBtn">
                    <i class="fas fa-plus me-1"></i>Ajouter une disponibilité
                </button>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Heure début</th>
                            <th>Heure fin</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="availabilityTableBody">
                        <!-- Les disponibilités seront chargées ici -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-btn">Fermer</button>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Gestion des messages flash
    function showFlashMessage(message, type) {
        const flashDiv = $('<div class="flash-message"></div>')
            .addClass(type === 'error' ? 'error' : 'success')
            .text(message)
            .appendTo('body');
        
        setTimeout(() => {
            flashDiv.fadeOut(() => flashDiv.remove());
        }, 5000);
    }

    <?php if (isset($flash)): ?>
        showFlashMessage('<?= addslashes($flash['message']) ?>', '<?= $flash['type'] ?>');
    <?php endif; ?>

    // Gestion des modals
    const teacherModal = $('#teacherModal');
    const availabilityModal = $('#availabilityModal');
    const viewAvailabilityModal = $('#viewAvailabilityModal');

    // Ouvrir modals
    $('#addTeacherBtn').click(() => {
        $('#teacherForm')[0].reset();
        $('#teacherId').val('');
        teacherModal.find('.modal-title').text('Ajouter un Enseignant');
        teacherModal.css('display', 'flex');
    });

    $('#addAvailabilityBtn').click(() => {
        $('#availabilityForm')[0].reset();
        $('#availTeacherId').val('');
        $('#reasonGroup').hide();
        availabilityModal.find('.modal-title').text('Ajouter une Disponibilité');
        availabilityModal.css('display', 'flex');
    });

    // Boutons d'édition
    $('.edit-btn').click(function() {
        const teacherId = $(this).data('id');
        
        $.get(`get_enseignant.php?id=${teacherId}`, function(data) {
            $('#teacherId').val(data.id);
            $('#teacherLastName').val(data.nom);
            $('#teacherFirstName').val(data.prenom);
            $('#teacherEmail').val(data.email);
            $('#teacherPhone').val(data.telephone);
            $('#teacherStatus').val(data.statut);
            $('#teacherSpecialty').val(data.specialite);
            
            teacherModal.find('.modal-title').text('Modifier un Enseignant');
            teacherModal.css('display', 'flex');
        }).fail(function() {
            showFlashMessage('Erreur lors du chargement des données de l\'enseignant', 'error');
        });
    });

    // Boutons de disponibilité
    $(document).on('click', '.availability-btn', function() {
        const teacherId = $(this).data('id');
        const teacherName = $(this).closest('tr').find('td:nth-child(2)').text() + ' ' + $(this).closest('tr').find('td:nth-child(3)').text();
        $('#availTeacherId').val(teacherId);
        loadDisponibilites(teacherId, teacherName);
    });

    // Gestion de l'indisponibilité
    $('#availStatus').change(function() {
        $('#reasonGroup').toggle(!this.checked);
        if (!this.checked) {
            $('#availReason').prop('required', true);
        } else {
            $('#availReason').prop('required', false);
        }
    });

    // Fermeture des modals
    $('.close-btn, .cancel-btn').click(function() {
        teacherModal.hide();
        availabilityModal.hide();
        viewAvailabilityModal.hide();
    });

    // Fermer en cliquant en dehors
    $(window).click(function(e) {
        if (e.target === teacherModal[0]) teacherModal.hide();
        if (e.target === availabilityModal[0]) availabilityModal.hide();
        if (e.target === viewAvailabilityModal[0]) viewAvailabilityModal.hide();
    });

    // Validation et soumission des formulaires
    $('#teacherForm').submit(function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.post($(this).attr('action'), formData, function(response) {
            if (response.success) {
                showFlashMessage(response.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showFlashMessage(response.error || 'Erreur lors de l\'enregistrement', 'error');
            }
        }, 'json').fail(function() {
            showFlashMessage('Erreur de communication avec le serveur', 'error');
        });
    });

    $('#availabilityForm').submit(function(e) {
        e.preventDefault();
        
        const start = $('#availStart').val();
        const end = $('#availEnd').val();
        
        if (start >= end) {
            showFlashMessage('L\'heure de fin doit être après l\'heure de début', 'error');
            return;
        }
        
        const formData = $(this).serialize();
        
        $.post($(this).attr('action'), formData, function(response) {
            if (response.success) {
                showFlashMessage(response.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showFlashMessage(response.error || 'Erreur lors de l\'enregistrement', 'error');
            }
        }, 'json').fail(function() {
            showFlashMessage('Erreur de communication avec le serveur', 'error');
        });
    });

    // Suppression d'un enseignant
    $('.delete-btn').click(function() {
        const teacherId = $(this).data('id');
        
        if (confirm('Voulez-vous vraiment supprimer cet enseignant ?')) {
            $.ajax({
                url: `delete_enseignant.php?id=${teacherId}`,
                method: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showFlashMessage(response.message, 'success');
                        $(`button[data-id="${teacherId}"]`).closest('tr').remove();
                    } else {
                        showFlashMessage(response.error || 'Erreur lors de la suppression', 'error');
                    }
                },
                error: function() {
                    showFlashMessage('Erreur de communication avec le serveur', 'error');
                }
            });
        }
    });

    // Fonction pour charger les disponibilités d'un enseignant
    function loadDisponibilites(enseignantId, enseignantNom) {
        $.get(`get_disponibilites.php?enseignant_id=${enseignantId}`, function(data) {
            const tbody = $('#availabilityTableBody');
            tbody.empty();
            
            if (data.length === 0) {
                tbody.append('<tr><td colspan="4" class="text-center">Aucune disponibilité enregistrée</td></tr>');
            } else {
                data.forEach(dispo => {
                    const row = `
                        <tr>
                            <td>${dispo.jour}</td>
                            <td>${dispo.heure_debut}</td>
                            <td>${dispo.heure_fin}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-dispo-btn" data-id="${dispo.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-dispo-btn" data-id="${dispo.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            }
            
            $('#viewAvailabilityModal .modal-title').text(`Disponibilités de ${enseignantNom}`);
            viewAvailabilityModal.css('display', 'flex');
        }).fail(function() {
            showFlashMessage('Erreur lors du chargement des disponibilités', 'error');
        });
    }

    // Gestion des onglets
    function initTabs() {
        $('.tab-btn:first').addClass('active');
        $('.tab-content:first').addClass('active');
        
        if ($('.tab-btn.active').data('tab') === 'availabilities') {
            loadAllAvailabilities();
        }
    }
    
    $('.tab-btn').click(function() {
        $('.tab-btn').removeClass('active');
        $('.tab-content').removeClass('active');
        
        $(this).addClass('active');
        const tabId = $(this).data('tab');
        $(`#${tabId}-tab`).addClass('active');
        
        if (tabId === 'availabilities') {
            loadAllAvailabilities();
        }
    });
    
    // Fonction pour charger toutes les disponibilités
    function loadAllAvailabilities() {
        $.ajax({
            url: 'get_all_disponibilites.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                const tbody = $('#allAvailabilitiesTableBody');
                tbody.empty();
                
                if (data.length === 0) {
                    tbody.append('<tr><td colspan="6" class="text-center">Aucune disponibilité enregistrée</td></tr>');
                    return;
                }
                
                data.forEach(function(dispo) {
                    const row = `
                        <tr>
                            <td>${dispo.enseignant_nom} ${dispo.enseignant_prenom}</td>
                            <td>${dispo.jour}</td>
                            <td>${dispo.heure_debut}</td>
                            <td>${dispo.heure_fin}</td>
                            <td>
                                <span class="badge ${dispo.disponible ? 'bg-success' : 'bg-danger'}">
                                    ${dispo.disponible ? 'Disponible' : 'Indisponible'}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-dispo-btn" data-id="${dispo.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-dispo-btn" data-id="${dispo.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            },
            error: function(xhr, status, error) {
                console.error("Erreur lors du chargement des disponibilités:", error);
                showFlashMessage('Erreur lors du chargement des disponibilités', 'error');
            }
        });
    }
    
    // Initialiser les onglets au chargement
    initTabs();
    
    // Gestion des boutons d'édition/suppression de disponibilité
    $(document).on('click', '.edit-dispo-btn', function() {
        const dispoId = $(this).data('id');
        $.get(`get_disponibilite.php?id=${dispoId}`, function(data) {
            $('#availabilityForm')[0].reset();
            $('#availabilityId').val(data.id);
            $('#availTeacherId').val(data.enseignant_id);
            $('#availDay').val(data.jour);
            $('#availStart').val(data.heure_debut);
            $('#availEnd').val(data.heure_fin);
            $('#availStatus').prop('checked', data.disponible);
            $('#availReason').val(data.raison);
            $('#reasonGroup').toggle(!data.disponible);
            
            availabilityModal.find('.modal-title').text('Modifier une Disponibilité');
            viewAvailabilityModal.hide();
            availabilityModal.css('display', 'flex');
        }).fail(function() {
            showFlashMessage('Erreur lors du chargement de la disponibilité', 'error');
        });
    });
    
    $(document).on('click', '.delete-dispo-btn', function() {
        const dispoId = $(this).data('id');
        if (confirm('Voulez-vous vraiment supprimer cette disponibilité ?')) {
            $.ajax({
                url: `delete_disponibilite.php?id=${dispoId}`,
                method: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showFlashMessage(response.message, 'success');
                        // Recharger les données
                        if (viewAvailabilityModal.css('display') === 'flex') {
                            const teacherId = $('#availTeacherId').val();
                            const teacherName = $('#viewAvailabilityModal .modal-title').text().replace('Disponibilités de ', '');
                            loadDisponibilites(teacherId, teacherName);
                        } else {
                            loadAllAvailabilities();
                        }
                    } else {
                        showFlashMessage(response.error || 'Erreur lors de la suppression', 'error');
                    }
                },
                error: function() {
                    showFlashMessage('Erreur de communication avec le serveur', 'error');
                }
            });
        }
    });
    
    // Ajouter une nouvelle disponibilité depuis la modal de visualisation
    $('#addNewAvailabilityBtn').click(function() {
        const teacherId = $('#availTeacherId').val();
        $('#availabilityForm')[0].reset();
        $('#availTeacherId').val(teacherId);
        viewAvailabilityModal.hide();
        availabilityModal.css('display', 'flex');
    });
});
</script>