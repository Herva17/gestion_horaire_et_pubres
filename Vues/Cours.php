<?php include_once("header.php"); ?>

<style>
   
  
    .management-content {
        width: 950px;
        max-width: 1200px;
        margin: 0 auto;
        background: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 2px 16px rgba(58,59,69,0.10);
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
    input, select {
        border-radius: 0.35rem;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d3e2;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        width: 100%;
        font-size: 1rem;
    }
    input:focus, select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
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
        .modal-body, .modal-header, .modal-footer {
            padding: 1rem;
        }
    }
</style>

<main class="main-content">
    <div class="management-content">
        <!-- Header -->
        <div class="management-header">
            <h1 class="mb-0">
                <i class="fas fa-book me-2"></i>Gestion des Cours
            </h1>
            <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto" style="justify-content: flex-end;">
                <button class="btn btn-primary" id="addDepartmentBtn">
                    <i class="fas fa-plus me-2"></i>Ajouter Type Cours
                </button>
                <button class="btn btn-success" id="addSectionBtn">
                    <i class="fas fa-plus me-2"></i>Ajouter Cours
                </button>
                <button class="btn btn-info" id="addPromotionBtn">
                    <i class="fas fa-plus me-2"></i>Ajouter Cours Promotion
                </button>
            </div>
        </div>
        <!-- Onglets -->
        <div class="tabs">
            <button class="tab-btn active" data-tab="departments">Type Cours</button>
            <button class="tab-btn" data-tab="sections">Cours</button>
            <button class="tab-btn" data-tab="promotions">Cours Promotion</button>
        </div>
        <!-- Contenu des onglets -->
        <div class="tab-content active" id="departments-tab">
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type Cours</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Informatique</td>
                            <td class="action-btns">
                                <button class="edit-btn"><i class="fas fa-edit"></i> Modifier</button>
                                <button class="delete-btn"><i class="fas fa-trash"></i> Supprimer</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-content" id="sections-tab">
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cours</th>
                            <th>Type cours</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Informatique de Gestion</td>
                            <td>Informatique</td>
                            <td class="action-btns">
                                <button class="edit-btn"><i class="fas fa-edit"></i> Modifier</button>
                                <button class="delete-btn"><i class="fas fa-trash"></i> Supprimer</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-content" id="promotions-tab">
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cours</th>
                            <th>Promotion</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>IG1</td>
                            <td>Informatique de Gestion</td>
                            <td class="action-btns">
                                <button class="edit-btn"><i class="fas fa-edit"></i> Modifier</button>
                                <button class="delete-btn"><i class="fas fa-trash"></i> Supprimer</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Modal pour Type Cours -->
<div class="modal" id="departmentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Ajouter un Type Cours</h3>
            <button class="close-btn">&times;</button>
        </div>
        <form id="departmentForm">
            <input type="hidden" id="departmentId">
            <div class="form-group">
                <label for="Typename" class="form-label">Type cours</label>
                <input type="text" id="Typename" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-btn">Annuler</button>
                <button type="submit" class="btn btn-primary save-btn">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal pour Cours -->
<div class="modal" id="sectionModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Ajouter un Cours</h3>
            <button class="close-btn">&times;</button>
        </div>
        <form id="sectionForm">
            <input type="hidden" id="sectionId">
            <div class="form-group">
                <label for="sectionName" class="form-label">Nom du Cours</label>
                <input type="text" id="sectionName" required>
            </div>
            <div class="form-group">
                <label for="departmentSelect" class="form-label">Type Cours</label>
                <select id="departmentSelect" required>
                    <option value="">Sélectionner un type cours</option>
                    <option value="1">Informatique</option>
                    <option value="2">Gestion</option>
                    <option value="3">Marketing</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-btn">Annuler</button>
                <button type="submit" class="btn btn-primary save-btn">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal pour Cours Promotion -->
<div class="modal" id="promotionModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Ajouter Cours Promotion</h3>
            <button class="close-btn">&times;</button>
        </div>
        <form id="promotionForm">
            <input type="hidden" id="promotionId">
            <div class="form-group">
                <label for="sectionSelect" class="form-label">Cours</label>
                <select id="sectionSelect" required>
                    <option value="">Sélectionner un cours</option>
                    <option value="1">Informatique de Gestion</option>
                    <option value="2">Réseaux et Télécoms</option>
                </select>
            </div>
            <div class="form-group">
                <label for="academicYear" class="form-label">Promotion</label>
                <select id="academicYear" required>
                    <option value="">Sélectionner une Promotion</option>
                    <option value="2022-2023">2022-2023</option>
                    <option value="2023-2024">2023-2024</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cancel-btn">Annuler</button>
                <button type="submit" class="btn btn-primary save-btn">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Boutons d'ouverture
    const addDepartmentBtn = document.getElementById('addDepartmentBtn');
    const addSectionBtn = document.getElementById('addSectionBtn');
    const addPromotionBtn = document.getElementById('addPromotionBtn');

    // Modals
    const departmentModal = document.getElementById('departmentModal');
    const sectionModal = document.getElementById('sectionModal');
    const promotionModal = document.getElementById('promotionModal');

    // Boutons de fermeture
    const closeButtons = document.querySelectorAll('.close-btn, .cancel-btn');

    // Ouvrir les modals
    addDepartmentBtn.addEventListener('click', () => {
        departmentModal.style.display = 'flex';
    });
    addSectionBtn.addEventListener('click', () => {
        sectionModal.style.display = 'flex';
    });
    addPromotionBtn.addEventListener('click', () => {
        promotionModal.style.display = 'flex';
    });

    // Fermer les modals
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            departmentModal.style.display = 'none';
            sectionModal.style.display = 'none';
            promotionModal.style.display = 'none';
        });
    });

    // Fermer quand on clique en dehors
    window.addEventListener('click', (event) => {
        if (event.target === departmentModal) {
            departmentModal.style.display = 'none';
        }
        if (event.target === sectionModal) {
            sectionModal.style.display = 'none';
        }
        if (event.target === promotionModal) {
            promotionModal.style.display = 'none';
        }
    });

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

    // Soumission des formulaires (à compléter avec AJAX)
    document.getElementById('departmentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        this.reset();
        departmentModal.style.display = 'none';
    });
    document.getElementById('sectionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        this.reset();
        sectionModal.style.display = 'none';
    });
    document.getElementById('promotionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        this.reset();
        promotionModal.style.display = 'none';
    });
});
</script>