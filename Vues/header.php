<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#4e73df" />
    <title>ISC - Gestion des Horaires</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f6fa;
            color: #333;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            transition: all 0.3s;
            position: fixed;
            height: 100%;
            z-index: 1000;
        }

        .logo {
            padding: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo i {
            font-size: 24px;
            margin-right: 10px;
            color: var(--secondary-color);
        }

        .logo span {
            font-size: 18px;
            font-weight: 600;
        }

        .nav-links {
            list-style: none;
            padding: 20px 0;
        }

        .nav-links li {
            margin-bottom: 5px;
        }

        .nav-links li a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--light-color);
            text-decoration: none;
            transition: all 0.3s;
        }

        .nav-links li a i {
            margin-right: 10px;
            font-size: 18px;
        }

        .nav-links li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .nav-links li.active a {
            background-color: var(--secondary-color);
            color: white;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 250px;
            transition: all 0.3s;
        }

        header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .menu-toggle {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: var(--dark-color);
            display: none;
        }

        .search-bar {
            position: relative;
            width: 400px;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-bar i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info i {
            margin-right: 20px;
            font-size: 18px;
            color: #777;
            cursor: pointer;
            position: relative;
        }

        .user-profile {
            display: flex;
            align-items: center;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        .user-profile span {
            font-weight: 500;
        }

        /* Dashboard Content */
        .dashboard-content {
            padding: 25px;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .card-inner {
            padding: 20px;
            display: flex;
            align-items: center;
        }

        .card i {
            font-size: 30px;
            margin-right: 20px;
            color: var(--secondary-color);
        }

        .card-info h3 {
            font-size: 14px;
            color: #777;
            margin-bottom: 5px;
        }

        .card-info h2 {
            font-size: 24px;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .card-info p {
            font-size: 12px;
            color: var(--success-color);
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .recent-activities, .schedule-list {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        .recent-activities h2, .schedule-list h2 {
            font-size: 18px;
            margin-bottom: 20px;
            color: var(--dark-color);
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .activity-list {
            list-style: none;
        }

        .activity-item {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background-color: rgba(52, 152, 219, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .activity-icon i {
            color: var(--secondary-color);
        }

        .activity-details h4 {
            font-size: 15px;
            margin-bottom: 3px;
            color: var(--dark-color);
        }

        .activity-details p {
            font-size: 13px;
            color: #777;
            margin-bottom: 3px;
        }

        .activity-details small {
            font-size: 11px;
            color: #999;
        }

        /* Schedule Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            color: #555;
            font-weight: 600;
            font-size: 13px;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status.active {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        .status.pending {
            background-color: rgba(241, 196, 15, 0.1);
            color: var(--warning-color);
        }

        .status.canceled {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
        }

        .action-btn {
            padding: 6px 12px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
        }

        .action-btn:hover {
            background-color: #2980b9;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
                overflow: hidden;
            }
            
            .logo span, .nav-links li a span {
                display: none;
            }
            
            .logo i, .nav-links li a i {
                margin-right: 0;
                font-size: 20px;
            }
            
            .nav-links li a {
                justify-content: center;
                padding: 15px 0;
            }
            
            .main-content {
                margin-left: 80px;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
            
            .sidebar {
                left: -250px;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .search-bar {
                width: 200px;
            }
        }

        @media (max-width: 576px) {
            .stats-cards {
                grid-template-columns: 1fr;
            }
            
            .user-profile span {
                display: none;
            }
            
            .search-bar {
                width: 150px;
            }
        }

        
/* Management Content */
.management-content {
    padding: 25px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-title {
    font-size: 24px;
    color: var(--dark-color);
}

.add-btn {
    background-color: var(--secondary-color);
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
}

.add-btn i {
    margin-right: 8px;
}

/* Tabs */
.tabs {
    display: flex;
    border-bottom: 1px solid #ddd;
    margin-bottom: 20px;
}

.tab-btn {
    padding: 10px 20px;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    cursor: pointer;
    font-weight: 500;
    color: #555;
}

.tab-btn.active {
    border-bottom-color: var(--secondary-color);
    color: var(--secondary-color);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Data Table */
.data-table {
    width: 100%;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    background-color: #f8f9fa;
    color: #555;
    font-weight: 600;
    font-size: 13px;
}

tr:hover {
    background-color: #f8f9fa;
}

.action-btns {
    display: flex;
    gap: 8px;
}

.edit-btn, .delete-btn {
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    display: flex;
    align-items: center;
}

.edit-btn {
    background-color: var(--warning-color);
    color: white;
}

.delete-btn {
    background-color: var(--accent-color);
    color: white;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: white;
    padding: 25px;
    border-radius: 8px;
    width: 500px;
    max-width: 90%;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.modal-title {
    font-size: 18px;
    font-weight: 600;
}

.close-btn {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-group input, .form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-group select {
    background-color: white;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.cancel-btn, .save-btn {
    padding: 8px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.cancel-btn {
    background-color: #ddd;
}

.save-btn {
    background-color: var(--success-color);
    color: white;
}

/* Responsive Styles */
@media (max-width: 992px) {
    .sidebar {
        width: 80px;
        overflow: hidden;
    }
    
    .logo span, .nav-links li a span {
        display: none;
    }
    
    .logo i, .nav-links li a i {
        margin-right: 0;
        font-size: 20px;
    }
    
    .nav-links li a {
        justify-content: center;
        padding: 15px 0;
    }
    
    .main-content {
        margin-left: 80px;
    }
    
    .search-bar {
        width: 200px;
    }
}

@media (max-width: 768px) {
    .menu-toggle {
        display: block;
    }
    
    .sidebar {
        left: -250px;
    }
    
    .sidebar.active {
        left: 0;
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .tabs {
        overflow-x: auto;
    }
}

@media (max-width: 576px) {
    .action-btns {
        flex-direction: column;
        gap: 5px;
    }
    
    .edit-btn, .delete-btn {
        width: 100%;
        justify-content: center;
    }
    
    th, td {
        padding: 8px 10px;
    }
}


        
    </style>
</head>
<body>
    
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="logo">
                <i class="fas fa-university"></i>
                <span>ISC Horaires</span>
            </div>
            <ul class="nav-links">
                <li class="active">
                    <a href="Dashboard.php">
                        <i class="fas fa-home"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>
                <li>
                    <a href="Horaire.php">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Gestion Horaire</span>
                    </a>
                </li>
                <li>
                    <a href="departement.php">
                        <i class="fas fa-door-open"></i>
                        <span>Gestion Sections</span>
                    </a>
                </li>
                <li>
                    <a href="Enseignants.php">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span> Gestion Professeurs</span>
                    </a>
                </li>
                <li>
                    <a href="Cours.php">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Gestion Cours</span>
                    </a>
                </li>
                <li>
                    <a href="#parametres">
                        <i class="fas fa-cog"></i>
                        <span>Paramètres</span>
                    </a>
                </li>
            </ul>
        </nav>
        <main class="main-content">
    <header>
        <div class="header-content">
            <button class="menu-toggle"><i class="fas fa-bars"></i></button>
            <div class="search-bar">
                <input type="text" placeholder="Rechercher un département..." />
                <i class="fas fa-search"></i>
            </div>
           
        </div>
    </header>