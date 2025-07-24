// Gestion des onglets
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Retirer la classe active de tous les boutons et contenus
        document.querySelectorAll('.tab-btn').forEach(tb => tb.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
        
        // Ajouter la classe active au bouton cliqué
        this.classList.add('active');
        
        // Afficher le contenu correspondant
        const tabId = this.getAttribute('data-tab');
        document.getElementById(`${tabId}-tab`).classList.add('active');
    });
});

// Gestion du modal département
const addDepartmentBtn = document.getElementById('addDepartmentBtn');
const departmentModal = document.getElementById('departmentModal');
const closeModal = document.getElementById('closeModal');
const cancelBtn = document.getElementById('cancelBtn');
const departmentForm = document.getElementById('departmentForm');

addDepartmentBtn.addEventListener('click', () => {
    document.getElementById('modalTitle').textContent = 'Ajouter un Département';
    document.getElementById('departmentId').value = '';
    document.getElementById('departmentName').value = '';
    departmentModal.style.display = 'flex';
});

closeModal.addEventListener('click', () => {
    departmentModal.style.display = 'none';
});

cancelBtn.addEventListener('click', () => {
    departmentModal.style.display = 'none';
});

// Gestion du modal section
const addSectionBtn = document.createElement('button');
addSectionBtn.className = 'add-btn';
addSectionBtn.innerHTML = '<i class="fas fa-plus"></i> Ajouter Section';
addSectionBtn.id = 'addSectionBtn';
document.querySelector('#sections-tab .page-header').appendChild(addSectionBtn);

addSectionBtn.addEventListener('click', () => {
    document.querySelector('#sectionModal .modal-title').textContent = 'Ajouter une Section';
    document.getElementById('sectionId').value = '';
    document.getElementById('sectionName').value = '';
    document.getElementById('departmentSelect').value = '';
    document.getElementById('sectionModal').style.display = 'flex';
});

// Gestion du modal promotion
const addPromotionBtn = document.createElement('button');
addPromotionBtn.className = 'add-btn';
addPromotionBtn.innerHTML = '<i class="fas fa-plus"></i> Ajouter Promotion';
addPromotionBtn.id = 'addPromotionBtn';
document.querySelector('#promotions-tab .page-header').appendChild(addPromotionBtn);

addPromotionBtn.addEventListener('click', () => {
    document.querySelector('#promotionModal .modal-title').textContent = 'Ajouter une Promotion';
    document.getElementById('promotionId').value = '';
    document.getElementById('promotionName').value = '';
    document.getElementById('sectionSelect').value = '';
    document.getElementById('academicYear').value = '';
    document.getElementById('promotionModal').style.display = 'flex';
});

// Fermer les modals
document.querySelectorAll('.modal .close-btn, .modal .cancel-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.modal').style.display = 'none';
    });
});

// Toggle sidebar sur mobile
document.querySelector('.menu-toggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('active');
    document.querySelector('.main-content').classList.toggle('active');
});

// Gestion des formulaires (simulée)
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formId = this.id;
        
        if (formId === 'departmentForm') {
            // Traitement pour département
            alert('Département enregistré avec succès!');
        } else if (formId === 'sectionForm') {
            // Traitement pour section
            alert('Section enregistrée avec succès!');
        } else if (formId === 'promotionForm') {
            // Traitement pour promotion
            alert('Promotion enregistrée avec succès!');
        }
        
        this.closest('.modal').style.display = 'none';
    });
});

// Gestion des boutons d'édition et suppression
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const row = this.closest('tr');
        const cells = row.cells;
        
        if (row.closest('#departments-tab')) {
            // Édition département
            document.getElementById('modalTitle').textContent = 'Modifier Département';
            document.getElementById('departmentId').value = cells[0].textContent;
            document.getElementById('departmentName').value = cells[1].textContent;
            departmentModal.style.display = 'flex';
        } else if (row.closest('#sections-tab')) {
            // Édition section
            document.querySelector('#sectionModal .modal-title').textContent = 'Modifier Section';
            document.getElementById('sectionId').value = cells[0].textContent;
            document.getElementById('sectionName').value = cells[1].textContent;
            
            // Trouver l'ID du département correspondant
            const deptName = cells[2].textContent;
            const options = document.getElementById('departmentSelect').options;
            for (let i = 0; i < options.length; i++) {
                if (options[i].text === deptName) {
                    document.getElementById('departmentSelect').value = options[i].value;
                    break;
                }
            }
            
            document.getElementById('sectionModal').style.display = 'flex';
        } else if (row.closest('#promotions-tab')) {
            // Édition promotion
            document.querySelector('#promotionModal .modal-title').textContent = 'Modifier Promotion';
            document.getElementById('promotionId').value = cells[0].textContent;
            document.getElementById('promotionName').value = cells[1].textContent;
            
            // Trouver l'ID de la section correspondante
            const sectionName = cells[2].textContent;
            const options = document.getElementById('sectionSelect').options;
            for (let i = 0; i < options.length; i++) {
                if (options[i].text === sectionName) {
                    document.getElementById('sectionSelect').value = options[i].value;
                    break;
                }
            }
            
            document.getElementById('academicYear').value = cells[3].textContent;
            document.getElementById('promotionModal').style.display = 'flex';
        }
    });
});

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet élément?')) {
            this.closest('tr').remove();
            alert('Élément supprimé avec succès!');
        }
    });
});

// Script pour gérer les modals
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
        departmentModal.style.display = 'block';
    });
    
    addSectionBtn.addEventListener('click', () => {
        sectionModal.style.display = 'block';
    });
    
    addPromotionBtn.addEventListener('click', () => {
        promotionModal.style.display = 'block';
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
            // Retirer la classe active de tous les onglets
            tabButtons.forEach(btn => btn.classList.remove('active'));
            // Ajouter la classe active à l'onglet cliqué
            button.classList.add('active');
            
            // Masquer tous les contenus d'onglets
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Afficher le contenu correspondant
            const tabId = button.getAttribute('data-tab');
            document.getElementById(`${tabId}-tab`).classList.add('active');
        });
    });
});