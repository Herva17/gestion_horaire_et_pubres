<?php include_once("header.php"); ?>

<style>
 
    .management-content {
        width: 1100px;
        max-width: 98vw;
        margin: 0 auto;
        background: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 2px 16px rgba(58,59,69,0.10);
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
        box-shadow: 0 2px 16px rgba(58,59,69,0.08);
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
    @media (max-width: 900px) {
        .management-content { width: 99vw; padding: 1rem; }
        table { font-size: 0.95rem; }
    }
</style>

<main class="main-content">
    <div class="management-content">
        <div class="management-header">
            <h1><i class="fas fa-calendar-alt me-2"></i>Horaire Institutionnel</h1>
            <button class="btn btn-primary" id="addHoraireBtn">
                <i class="fas fa-plus me-2"></i>Ajouter Horaire
            </button>
        </div>
        <div class="data-table">
            <table>
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Promotion</th>
                        <th>Cours</th>
                        <th>Enseignant</th>
                        <th>Salle</th>
                        <th>Jour</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Date Début</th>
                        <th>Date Fin</th>
                        <th>Fréquence</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Section Informatique de Gestion -->
                    <tr class="section-row">
                        <td colspan="12">Section : Informatique de Gestion</td>
                    </tr>
                    <tr>
                        <td>Informatique de Gestion</td>
                        <td>IG1</td>
                        <td>Mathématiques</td>
                        <td>M. Dupont</td>
                        <td>Salle A101</td>
                        <td>Lundi</td>
                        <td>08:00</td>
                        <td>10:00</td>
                        <td>2023-09-01</td>
                        <td>2023-12-15</td>
                        <td>Hebdomadaire</td>
                        <td class="action-btns">
                            <button class="edit-btn"><i class="fas fa-edit"></i></button>
                            <button class="delete-btn"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Informatique de Gestion</td>
                        <td>IG2</td>
                        <td>Programmation</td>
                        <td>Mme Martin</td>
                        <td>Salle B202</td>
                        <td>Mardi</td>
                        <td>10:00</td>
                        <td>12:00</td>
                        <td>2023-09-01</td>
                        <td>2023-12-15</td>
                        <td>Hebdomadaire</td>
                        <td class="action-btns">
                            <button class="edit-btn"><i class="fas fa-edit"></i></button>
                            <button class="delete-btn"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <!-- Section Réseaux et Télécoms -->
                    <tr class="section-row">
                        <td colspan="12">Section : Réseaux et Télécoms</td>
                    </tr>
                    <tr>
                        <td>Réseaux et Télécoms</td>
                        <td>RT1</td>
                        <td>Réseaux</td>
                        <td>M. Koffi</td>
                        <td>Salle C303</td>
                        <td>Mercredi</td>
                        <td>14:00</td>
                        <td>16:00</td>
                        <td>2023-09-01</td>
                        <td>2023-12-15</td>
                        <td>Hebdomadaire</td>
                        <td class="action-btns">
                            <button class="edit-btn"><i class="fas fa-edit"></i></button>
                            <button class="delete-btn"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Réseaux et Télécoms</td>
                        <td>RT2</td>
                        <td>Télécoms</td>
                        <td>Mme Diallo</td>
                        <td>Salle D404</td>
                        <td>Jeudi</td>
                        <td>10:00</td>
                        <td>12:00</td>
                        <td>2023-09-01</td>
                        <td>2023-12-15</td>
                        <td>Hebdomadaire</td>
                        <td class="action-btns">
                            <button class="edit-btn"><i class="fas fa-edit"></i></button>
                            <button class="delete-btn"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal pour Ajout Horaire -->
<div class="modal" id="horaireModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Ajouter un Horaire</h3>
            <button class="close-btn">&times;</button>
        </div>
        <form id="horaireForm">
            <div class="form-group">
                <label for="coursSelect" class="form-label">Cours</label>
                <select id="coursSelect" required>
                    <option value="">Sélectionner un cours</option>
                    <option value="1">Mathématiques</option>
                    <option value="2">Programmation</option>
                </select>
            </div>
            <div class="form-group">
                <label for="enseignantSelect" class="form-label">Enseignant</label>
                <select id="enseignantSelect" required>
                    <option value="">Sélectionner un enseignant</option>
                    <option value="1">M. Dupont</option>
                    <option value="2">Mme Martin</option>
                    <option value="3">M. Koffi</option>
                    <option value="4">Mme Diallo</option>
                </select>
            </div>
            <div class="form-group">
                <label for="promotionSelect" class="form-label">Promotion</label>
                <select id="promotionSelect" required>
                    <option value="">Sélectionner une promotion</option>
                    <option value="IG1">IG1</option>
                    <option value="IG2">IG2</option>
                    <option value="RT1">RT1</option>
                    <option value="RT2">RT2</option>
                </select>
            </div>
            <div class="form-group">
                <label for="salleSelect" class="form-label">Salle</label>
                <select id="salleSelect" required>
                    <option value="">Sélectionner une salle</option>
                    <option value="A101">Salle A101</option>
                    <option value="B202">Salle B202</option>
                    <option value="C303">Salle C303</option>
                    <option value="D404">Salle D404</option>
                </select>
            </div>
            <div class="form-group">
                <label for="jourSelect" class="form-label">Jour de la semaine</label>
                <select id="jourSelect" required>
                    <option value="">Sélectionner un jour</option>
                    <option value="Lundi">Lundi</option>
                    <option value="Mardi">Mardi</option>
                    <option value="Mercredi">Mercredi</option>
                    <option value="Jeudi">Jeudi</option>
                    <option value="Vendredi">Vendredi</option>
                    <option value="Samedi">Samedi</option>
                </select>
            </div>
            <div class="form-group">
                <label for="heureDebut" class="form-label">Heure de début</label>
                <input type="time" id="heureDebut" required>
            </div>
            <div class="form-group">
                <label for="heureFin" class="form-label">Heure de fin</label>
                <input type="time" id="heureFin" required>
            </div>
            <div class="form-group">
                <label for="dateDebut" class="form-label">Date de début</label>
                <input type="date" id="dateDebut" required>
            </div>
            <div class="form-group">
                <label for="dateFin" class="form-label">Date de fin</label>
                <input type="date" id="dateFin" required>
            </div>
            <div class="form-group">
                <label for="frequence" class="form-label">Fréquence</label>
                <select id="frequence" required>
                    <option value="">Sélectionner la fréquence</option>
                    <option value="Hebdomadaire">Hebdomadaire</option>
                    <option value="Quotidienne">Quotidienne</option>
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
        // Bouton ouverture modal
        const addHoraireBtn = document.getElementById('addHoraireBtn');
        const horaireModal = document.getElementById('horaireModal');
        const closeButtons = document.querySelectorAll('.close-btn, .cancel-btn');

        addHoraireBtn.addEventListener('click', () => {
            horaireModal.style.display = 'flex';
        });
        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                horaireModal.style.display = 'none';
            });
        });
        window.addEventListener('click', (event) => {
            if (event.target === horaireModal) {
                horaireModal.style.display = 'none';
            }
        });

        // Soumission formulaire (à compléter avec AJAX)
        document.getElementById('horaireForm').addEventListener('submit', function(e) {
            e.preventDefault();
            this.reset();
            horaireModal.style.display = 'none';
        });
    });
</script>