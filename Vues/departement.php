<?php include_once("header.php");
require_once '../Models/MaClasse.php';
$pdo = getPDO();
$horaire = new Horaire($pdo);
$departements = $horaire->getDepartements();
$sections = $horaire->getSections();
$promotions = $horaire->getPromotions();
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
        margin-left: -200px;
        margin-top: 20px;
    }

    .management-header {
        margin-bottom: 2rem;
    }

    .management-header h1 {
        color: #2d3a4b;
        font-weight: 700;
        font-size: 2rem;
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
        overflow: hidden;
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
    }

    table tbody td {
        padding: 0.75rem;
    }

    table tbody tr:nth-of-type(odd) {
        background-color: #f4f6fb;
    }

    .action-btns button {
        margin-right: 0.5rem;
        padding: 0.25rem 0.75rem;
        border-radius: 0.35rem;
        border: none;
        font-weight: 600;
        cursor: pointer;
    }

    .edit-btn {
        background: #007bff;
        color: #fff;
    }

    .delete-btn {
        background: #dc3545;
        color: #fff;
    }

    .btn {
        font-weight: 600;
        padding: 0.375rem 0.75rem;
        border-radius: 0.35rem;
        border: none;
        cursor: pointer;
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
    }

    .modal-header {
        padding: 1.25rem;
        border-bottom: 1px solid #e3e6f0;
        display: flex;
        justify-content: space-between;
    }

    .modal-title {
        font-weight: 700;
        font-size: 1.35rem;
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

    input, select {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
    }

    @media (max-width: 768px) {
        .management-content {
            padding: 1rem;
            margin-left: 0;
        }
        
        .tab-btn {
            padding: 0.5rem 1rem;
        }
    }
</style>

<main class="main-content">
        <div class="management-content">
            <!-- En-tête -->
            <div class="management-header">
                <h1><i class="fas fa-building me-2"></i>Gestion Académique</h1>
                <div><br>
                    <button class="btn btn-primary" id="addDepartmentBtn">
                        <i class="fas fa-plus me-2"></i>Ajouter Département
                    </button>
                    <button class="btn btn-success" id="addSectionBtn">
                        <i class="fas fa-plus me-2"></i>Ajouter Section
                    </button>
                    <button class="btn btn-info" id="addPromotionBtn">
                        <i class="fas fa-plus me-2"></i>Ajouter Promotion
                    </button>
                </div>
            </div>

            <!-- Onglets -->
            <div class="tabs">
                <button class="tab-btn active" data-tab="departments">Départements</button>
                <button class="tab-btn" data-tab="sections">Sections</button>
                <button class="tab-btn" data-tab="promotions">Promotions</button>
            </div>

            <!-- Contenu des onglets -->
            <div class="tab-content active" id="departments-tab">
                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom du Département</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($departements as $departement): ?>
                                <tr>
                                    <td><?= htmlspecialchars($departement['id']) ?></td>
                                    <td><?= htmlspecialchars($departement['nom']) ?></td>
                                    <td class="action-btns">
                                        <button class="edit-btn" data-id="<?= $departement['id'] ?>" data-type="department">
                                            <i class="fas fa-edit"></i> Modifier
                                        </button>
                                        <button class="delete-btn" data-id="<?= $departement['id'] ?>" data-type="department">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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
                                <th>Nom de la Section</th>
                                <th>Département</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sections as $section): ?>
                                <tr>
                                    <td><?= htmlspecialchars($section['id']) ?></td>
                                    <td><?= htmlspecialchars($section['nom']) ?></td>
                                    <td><?= htmlspecialchars($section['departement_nom']) ?></td>
                                    <td class="action-btns">
                                        <button class="edit-btn" data-id="<?= $section['id'] ?>" data-type="section">
                                            <i class="fas fa-edit"></i> Modifier
                                        </button>
                                        <button class="delete-btn" data-id="<?= $section['id'] ?>" data-type="section">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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
                                <th>Nom Promotion</th>
                                <th>Section</th>
                                <th>Année Académique</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($promotions as $promotion): ?>
                                <tr>
                                    <td><?= htmlspecialchars($promotion['id']) ?></td>
                                    <td><?= htmlspecialchars($promotion['nom']) ?></td>
                                    <td><?= htmlspecialchars($promotion['section_nom']) ?></td>
                                    <td><?= htmlspecialchars($promotion['annee_academique']) ?></td>
                                    <td class="action-btns">
                                        <button class="edit-btn" data-id="<?= $promotion['id'] ?>" data-type="promotion">
                                            <i class="fas fa-edit"></i> Modifier
                                        </button>
                                        <button class="delete-btn" data-id="<?= $promotion['id'] ?>" data-type="promotion">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modals -->
    <!-- Modal Département -->
    <div class="modal" id="departmentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Ajouter un Département</h3>
                <button class="close-btn">&times;</button>
            </div>
            <form id="departmentForm" action="process_department.php" method="POST">
                <input type="hidden" name="id" id="departmentId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="departmentName" class="form-label">Nom du Département</label>
                        <input type="text" id="departmentName" name="nom" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cancel-btn">Annuler</button>
                    <button type="submit" class="btn btn-primary save-btn">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Section -->
    <div class="modal" id="sectionModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Ajouter une Section</h3>
                <button class="close-btn">&times;</button>
            </div>
            <form id="sectionForm" action="process_section.php" method="POST">
                <input type="hidden" name="id" id="sectionId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sectionName" class="form-label">Nom de la Section</label>
                        <input type="text" id="sectionName" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="departmentSelect" class="form-label">Département</label>
                        <select id="departmentSelect" name="id_departement" required>
                            <option value="">Sélectionner un département</option>
                            <?php foreach ($departements as $dept): ?>
                                <option value="<?= htmlspecialchars($dept['id']) ?>">
                                    <?= htmlspecialchars($dept['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cancel-btn">Annuler</button>
                    <button type="submit" class="btn btn-primary save-btn">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Promotion -->
    <div class="modal" id="promotionModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Ajouter une Promotion</h3>
                <button class="close-btn">&times;</button>
            </div>
            <form id="promotionForm" action="process_promotion.php" method="POST">
                <input type="hidden" name="id" id="promotionId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="promotionName" class="form-label">Nom de la Promotion</label>
                        <input type="text" id="promotionName" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="sectionSelect" class="form-label">Section</label>
                        <select id="sectionSelect" name="id_section" required>
                            <option value="">Sélectionner une section</option>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?= htmlspecialchars($section['id']) ?>">
                                    <?= htmlspecialchars($section['nom']) ?> (<?= htmlspecialchars($section['departement_nom']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="academicYear" class="form-label">Année Académique</label>
                        <select id="academicYear" name="annee_academique" required>
                            <option value="">Sélectionner une année</option>
                            <?php
                            $currentYear = date('Y');
                            for ($i = -2; $i <= 2; $i++) {
                                $year = $currentYear + $i;
                                echo "<option value='$year-" . ($year + 1) . "'>$year-" . ($year + 1) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
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
        // Afficher le message flash s'il existe
        <?php if (isset($flash)): ?>
            showFlashMessage('<?= addslashes($flash['message']) ?>', '<?= $flash['type'] ?>');
        <?php endif; ?>

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
        const modals = {
            department: document.getElementById('departmentModal'),
            section: document.getElementById('sectionModal'),
            promotion: document.getElementById('promotionModal')
        };

        // Boutons d'ouverture
        document.getElementById('addDepartmentBtn').addEventListener('click', () => {
            resetForm('departmentForm');
            document.querySelector('#departmentModal .modal-title').textContent = 'Ajouter un Département';
            modals.department.style.display = 'flex';
        });

        document.getElementById('addSectionBtn').addEventListener('click', () => {
            resetForm('sectionForm');
            document.querySelector('#sectionModal .modal-title').textContent = 'Ajouter une Section';
            modals.section.style.display = 'flex';
        });

        document.getElementById('addPromotionBtn').addEventListener('click', () => {
            resetForm('promotionForm');
            document.querySelector('#promotionModal .modal-title').textContent = 'Ajouter une Promotion';
            modals.promotion.style.display = 'flex';
        });

        // Fermeture des modals
        document.querySelectorAll('.close-btn, .cancel-btn').forEach(button => {
            button.addEventListener('click', () => {
                Object.values(modals).forEach(modal => {
                    modal.style.display = 'none';
                });
            });
        });

        // Gestion des clics en dehors des modals
        window.addEventListener('click', (event) => {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        });

        // Gestion des boutons Modifier
        document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const type = this.getAttribute('data-type');
        
        fetch(`get_${type}.php?id=${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                console.log('Données reçues:', data); // Debug
                
                if (type === 'department') {
                    document.getElementById('departmentId').value = data.id;
                    document.getElementById('departmentName').value = data.nom;
                    document.querySelector('#departmentModal .modal-title').textContent = 'Modifier Département';
                    modals.department.style.display = 'flex';
                } 
                else if (type === 'section') {
                    document.getElementById('sectionId').value = data.id;
                    document.getElementById('sectionName').value = data.nom;
                    document.getElementById('departmentSelect').value = data.id_departement;
                    document.querySelector('#sectionModal .modal-title').textContent = 'Modifier Section';
                    modals.section.style.display = 'flex';
                } 
                else if (type === 'promotion') {
                    // Debug: Vérification des données
                    console.log('Année académique reçue:', data.annee_academique);
                    
                    // Remplissage du formulaire
                    document.getElementById('promotionId').value = data.id;
                    document.getElementById('promotionName').value = data.nom;
                    
                    // Section
                    const sectionSelect = document.getElementById('sectionSelect');
                    sectionSelect.value = data.id_section;
                    
                    // Année académique - solution robuste
                    const yearSelect = document.getElementById('academicYear');
                    const anneeValue = data.annee_academique;
                    
                    // Vérifie si la valeur existe dans les options
                    const optionExists = Array.from(yearSelect.options).some(
                        option => option.value === anneeValue
                    );
                    
                    if (optionExists) {
                        yearSelect.value = anneeValue;
                    } else {
                        console.warn(`La valeur ${anneeValue} n'existe pas dans les options`);
                        yearSelect.value = ''; // Réinitialise si valeur non trouvée
                    }
                    
                    document.querySelector('#promotionModal .modal-title').textContent = 'Modifier Promotion';
                    modals.promotion.style.display = 'flex';
                    
                    // Debug supplémentaire
                    console.log('Valeur sélectionnée après mise à jour:', yearSelect.value);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showFlashMessage('Erreur lors du chargement des données: ' + error.message, 'error');
            });
    });
});
        // Gestion des boutons Supprimer
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const type = this.getAttribute('data-type');
                const messages = {
                    department: 'Voulez-vous vraiment supprimer ce département ?',
                    section: 'Voulez-vous vraiment supprimer cette section ?',
                    promotion: 'Voulez-vous vraiment supprimer cette promotion ?'
                };

                if (confirm(messages[type])) {
                    fetch(`delete_${type}.php?id=${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showFlashMessage(data.message, 'success');
                            this.closest('tr').remove();
                        } else {
                            throw new Error(data.error || 'Erreur lors de la suppression');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showFlashMessage(error.message, 'error');
                    });
                }
            });
        });

        // Validation des formulaires
        document.getElementById('departmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const nom = document.getElementById('departmentName').value.trim();
            
            if (nom.length < 2 || nom.length > 50) {
                showFlashMessage('Le nom doit contenir entre 2 et 50 caractères', 'error');
                return;
            }

            submitForm(this);
        });

        document.getElementById('sectionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const nom = document.getElementById('sectionName').value.trim();
            const departement = document.getElementById('departmentSelect').value;
            
            if (!nom || !departement) {
                showFlashMessage('Veuillez remplir tous les champs', 'error');
                return;
            }

            submitForm(this);
        });

        document.getElementById('promotionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const nom = document.getElementById('promotionName').value.trim();
            const section = document.getElementById('sectionSelect').value;
            const annee = document.getElementById('academicYear').value;
            
            if (!nom || !section || !annee) {
                showFlashMessage('Veuillez remplir tous les champs', 'error');
                return;
            }

            submitForm(this);
        });

        // Fonctions utilitaires
        function resetForm(formId) {
            const form = document.getElementById(formId);
            form.reset();
            const hiddenInput = form.querySelector('input[type="hidden"]');
            if (hiddenInput) hiddenInput.value = '';
        }

        function submitForm(form) {
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.json();
                }
            })
            .then(data => {
                if (data && data.success) {
                    showFlashMessage(data.message, 'success');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showFlashMessage('Une erreur est survenue', 'error');
            });
        }

        function showFlashMessage(message, type) {
            const flashDiv = document.createElement('div');
            flashDiv.className = `flash-message ${type}`;
            flashDiv.textContent = message;
            document.body.appendChild(flashDiv);

            setTimeout(() => {
                flashDiv.style.opacity = '0';
                setTimeout(() => flashDiv.remove(), 500);
            }, 5000);
        }
    });
    </script>