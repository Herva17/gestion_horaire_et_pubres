<?php include_once("header.php"); ?>

       

            <div class="dashboard-content">
                <!-- Statistics Cards -->
                <div class="stats-cards">
                    <div class="card">
                        <div class="card-inner">
                            <i class="fas fa-calendar-day"></i>
                            <div class="card-info">
                                <h3>Cours aujourd'hui</h3>
                                <h2>18</h2>
                                <p>+2 par rapport à hier</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-inner">
                            <i class="fas fa-chalkboard"></i>
                            <div class="card-info">
                                <h3>Salles occupées</h3>
                                <h2>12/20</h2>
                                <p>60% d'occupation</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-inner">
                            <i class="fas fa-users"></i>
                            <div class="card-info">
                                <h3>Professeurs actifs</h3>
                                <h2>24</h2>
                                <p>5 en congé</p>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-inner">
                            <i class="fas fa-exclamation-circle"></i>
                            <div class="card-info">
                                <h3>Conflits d'horaire</h3>
                                <h2>3</h2>
                                <p>À résoudre</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities and Schedule List -->
                <div class="dashboard-grid">
                    <div class="recent-activities">
                        <h2>Activités Récentes</h2>
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="activity-details">
                                    <h4>Nouveau cours programmé</h4>
                                    <p>Marketing Digital - Salle B12</p>
                                    <small>Il y a 15 minutes</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                                <div class="activity-details">
                                    <h4>Changement de salle</h4>
                                    <p>Comptabilité déplacée en A07</p>
                                    <small>Il y a 1 heure</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-user-clock"></i>
                                </div>
                                <div class="activity-details">
                                    <h4>Professeur indisponible</h4>
                                    <p>M. Dupont - Cours reporté</p>
                                    <small>Il y a 3 heures</small>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="activity-details">
                                    <h4>Conflit résolu</h4>
                                    <p>Salle C05 libérée à 14h</p>
                                    <small>Hier à 16:30</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="schedule-list">
                        <h2>Prochains Cours</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Cours</th>
                                    <th>Professeur</th>
                                    <th>Salle</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="course-info">
                                            <strong>Marketing Digital</strong>
                                            <small>09:00 - 11:00</small>
                                        </div>
                                    </td>
                                    <td>Mme. Martin</td>
                                    <td>B12</td>
                                    <td><span class="status active">Confirmé</span></td>
                                    <td>
                                        <button class="action-btn">Détails</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="course-info">
                                            <strong>Comptabilité</strong>
                                            <small>11:30 - 13:30</small>
                                        </div>
                                    </td>
                                    <td>M. Bernard</td>
                                    <td>A07</td>
                                    <td><span class="status pending">À confirmer</span></td>
                                    <td>
                                        <button class="action-btn">Détails</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="course-info">
                                            <strong>Droit des affaires</strong>
                                            <small>14:00 - 16:00</small>
                                        </div>
                                    </td>
                                    <td>Mme. Dubois</td>
                                    <td>C05</td>
                                    <td><span class="status active">Confirmé</span></td>
                                    <td>
                                        <button class="action-btn">Détails</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="course-info">
                                            <strong>Management</strong>
                                            <small>16:30 - 18:30</small>
                                        </div>
                                    </td>
                                    <td>M. Leroy</td>
                                    <td>B08</td>
                                    <td><span class="status canceled">Annulé</span></td>
                                    <td>
                                        <button class="action-btn">Détails</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        // Toggle sidebar on mobile
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        });

        // Active menu item
        const navLinks = document.querySelectorAll('.nav-links li');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(item => item.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>