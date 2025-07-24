<?php include_once("header.php"); ?>

<style>
   
    .management-header {
        background-color: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 2px 16px rgba(58,59,69,0.10);
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        margin-left: -250px;
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
    .tab-btn:hover, .tab-btn:focus {
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
        box-shadow: 0 2px 16px rgba(58,59,69,0.08);
        overflow: hidden;
        margin-bottom: 2rem;
        margin-left: -200px;
        width: 900px;

    }
    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: collapse;
      
    }
    .table thead th {
        background-color: #f8f9fc;
        color: #2d3a4b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e3e6f0;
        padding: 0.75rem;
    }
    .table tbody td {
        padding: 0.75rem;
        vertical-align: middle;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f4f6fb;
    }
    .table-hover tbody tr:hover {
        background-color: #e9ecef;
        transition: background 0.2s;
    }
    .badge {
        font-weight: 600;
        padding: 0.35em 0.85em;
        font-size: 0.85em;
        border-radius: 0.35em;
    }
    .bg-success {
        background-color: #28a745 !important;
        color: #fff !important;
    }
    .bg-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }
    .bg-danger {
        background-color: #dc3545 !important;
        color: #fff !important;
    }
    .bg-info {
        background-color: #17a2b8 !important;
        color: #fff !important;
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
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.85rem;
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
        background: none;
        color: #007bff;
        border: 1px solid #007bff;
    }
    .btn-outline-danger {
        background: none;
        color: #dc3545;
        border: 1px solid #dc3545;
    }
    .btn-outline-info {
        background: none;
        color: #17a2b8;
        border: 1px solid #17a2b8;
    }
    .btn:focus {
        outline: 2px solid #007bff;
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
        overflow-y: auto;
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
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
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
    .form-control, .form-select {
        border-radius: 0.35rem;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d3e2;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        width: 100%;
        font-size: 1rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
    }
    .form-check-input {
        width: 1.25em;
        height: 1.25em;
        margin-top: 0.15em;
    }
    @media (max-width: 768px) {
        .main-content {
            padding: 16px 4px;
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
        .modal-body, .modal-header, .modal-footer {
            padding: 1rem;
        }
    }
</style>

<main class="main-content">
    <div class="management-content" style="width: 100%; margin: 0 auto; max-width: 1200px;">
        <!-- Header -->
        <div class="management-header">
            <h1 class="mb-0">
                <i class="fas fa-chalkboard-teacher me-2"></i>Gestion des Enseignants
            </h1>
            <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto" style="justify-content: flex-end;">
                <button class="btn btn-primary" id="addTeacherBtn">
                    <i class="fas fa-plus me-2"></i>Ajouter Enseignant
                </button>
                <button class="btn btn-success" id="addAvailabilityBtn">
                    <i class="fas fa-calendar-plus me-2"></i>Ajouter Disponibilité
                </button>
            </div>
        </div>
        <!-- Onglets -->
        <div class="tabs">
            <button class="tab-btn active" data-tab="teachers">Enseignants</button>
            <button class="tab-btn" data-tab="availabilities">D</button>
        </div>
        <!-- Contenu des onglets -->
        <div class="tab-content active" id="teachers-tab">
            <div class="data-table">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Dupont</td>
                            <td>Jean</td>
                            <td>j.dupont@example.com</td>
                            <td>06 12 34 56 78</td>
                            <td><span class="badge bg-success">Permanent</span></td>
                            <td class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary edit-btn" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-btn" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info availability-btn" title="Disponibilité">
                                    <i class="fas fa-calendar-alt"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Martin</td>
                            <td>Sophie</td>
                            <td>s.martin@example.com</td>
                            <td>06 98 76 54 32</td>
                            <td><span class="badge bg-warning text-dark">Vacataire</span></td>
                            <td class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary edit-btn" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-btn" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info availability-btn" title="Disponibilité">
                                    <i class="fas fa-calendar-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-content" id="availabilities-tab">
            <div class="data-table">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Enseignant</th>
                            <th>Jour</th>
                            <th>Heure Début</th>
                            <th>Heure Fin</th>
                            <th>Disponible</th>
                            <th>Raison</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Jean Dupont</td>
                            <td>Lundi</td>
                            <td>08:00</td>
                            <td>10:00</td>
                            <td><span class="badge bg-danger">Non</span></td>
                            <td>Réunion</td>
                            <td class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary edit-btn" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-btn" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jean Dupont</td>
                            <td>Mardi</td>
                            <td>14:00</td>
                            <td>18:00</td>
                            <td><span class="badge bg-success">Oui</span></td>
                            <td>-</td>
                            <td class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary edit-btn" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-btn" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Modal Ajout Enseignant -->
<div class="modal" id="teacherModal">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h3 class="modal-title">Ajouter un Enseignant</h3>
            <button class="close-btn text-white" title="Fermer">&times;</button>
        </div>
        <form id="teacherForm">
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="teacherLastName" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="teacherLastName" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="teacherFirstName" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="teacherFirstName" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="teacherEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="teacherEmail" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="teacherPhone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="teacherPhone">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="teacherStatus" class="form-label">Statut</label>
                            <select class="form-select" id="teacherStatus" required>
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
                            <input type="text" class="form-control" id="teacherSpecialty">
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

<!-- Modal Ajout Disponibilité -->
<div class="modal" id="availabilityModal">
    <div class="modal-content">
        <div class="modal-header bg-success text-white">
            <h3 class="modal-title">Gestion des Disponibilités</h3>
            <button class="close-btn text-white" title="Fermer">&times;</button>
        </div>
        <form id="availabilityForm">
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="availTeacher" class="form-label">Enseignant</label>
                            <select class="form-select" id="availTeacher" required>
                                <option value="">Sélectionner un enseignant</option>
                                <option value="1">Jean Dupont</option>
                                <option value="2">Sophie Martin</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="availDay" class="form-label">Jour</label>
                            <select class="form-select" id="availDay" required>
                                <option value="">Sélectionner un jour</option>
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
                            <label for="availStatus" class="form-label">Statut</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="availStatus">
                                <label class="form-check-label" for="availStatus">Indisponible</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="availStart" class="form-label">Heure de début</label>
                            <input type="time" class="form-control" id="availStart" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="availEnd" class="form-label">Heure de fin</label>
                            <input type="time" class="form-control" id="availEnd" required>
                        </div>
                    </div>
                    <div class="col-md-12" id="reasonGroup" style="display:none;">
                        <div class="form-group">
                            <label for="availReason" class="form-label">Raison d'indisponibilité</label>
                            <input type="text" class="form-control" id="availReason">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-btn">Annuler</button>
                <button type="submit" class="btn btn-success">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des onglets
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            const tabId = button.getAttribute('data-tab');
            document.getElementById(`${tabId}-tab`).classList.add('active');
        });
    });
    // Gestion des modals
    const teacherModal = document.getElementById('teacherModal');
    const availabilityModal = document.getElementById('availabilityModal');
    const closeButtons = document.querySelectorAll('.close-btn, .cancel-btn');
    // Ouvrir modals
    document.getElementById('addTeacherBtn').addEventListener('click', () => {
        teacherModal.style.display = 'flex';
    });
    document.getElementById('addAvailabilityBtn').addEventListener('click', () => {
        availabilityModal.style.display = 'flex';
    });
    // Fermer modals
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            teacherModal.style.display = 'none';
            availabilityModal.style.display = 'none';
        });
    });
    // Gestion de l'affichage du champ raison
    document.getElementById('availStatus').addEventListener('change', function() {
        document.getElementById('reasonGroup').style.display = this.checked ? 'block' : 'none';
    });
    // Soumission des formulaires
    document.getElementById('teacherForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // AJAX pour sauvegarder
        alert('Enseignant enregistré avec succès!');
        this.reset();
        teacherModal.style.display = 'none';
    });
    document.getElementById('availabilityForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // AJAX pour sauvegarder
        alert('Disponibilité enregistrée avec succès!');
        this.reset();
        document.getElementById('reasonGroup').style.display = 'none';
        availabilityModal.style.display = 'none';
    });
    // Fermer modal en cliquant en dehors
    window.addEventListener('click', (e) => {
        if (e.target === teacherModal) {
            teacherModal.style.display = 'none';
        }
        if (e.target === availabilityModal) {
            availabilityModal.style.display = 'none';
        }
    });
});
</script>