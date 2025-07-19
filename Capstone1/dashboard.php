<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - Friendship Zone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #5e35b1;
            --primary-light: #7e57c2;
            --primary-dark: #4527a0;
            --accent: #ffb300;
            --accent-light: #ffca28;
            --accent2: #ff6f00;
            --accent2-light: #ff9100;
            --bg: #f8fafc;
            --text: #222;
            --text-light: #555;
            --light: #fff;
            --dark: #111;
            --card-bg: rgba(255, 255, 255, 0.95);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.15);
            --border: #e0e7ef;
            --border-light: #eef2f7;
            --success: #4caf50;
            --success-light: #66bb6a;
            --error: #f44336;
            --error-light: #ef5350;
            --transition: all 0.3s ease;
            --radius: 12px;
            --radius-sm: 8px;
        }

        [data-theme="dark"] {
            --primary: #7e57c2;
            --primary-light: #9575cd;
            --primary-dark: #5e35b1;
            --accent: #ffd166;
            --accent-light: #ffe082;
            --accent2: #ff8800;
            --accent2-light: #ff9e3d;
            --bg: #121212;
            --text: #f3f3f3;
            --text-light: #bbb;
            --light: #232b39;
            --dark: #f3f3f3;
            --card-bg: rgba(35, 43, 57, 0.95);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.5);
            --border: #2d3749;
            --border-light: #3a4558;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, rgba(94, 53, 177, 0.08) 0%, rgba(255, 179, 0, 0.08) 100%), var(--bg);
            color: var(--text);
            min-height: 100vh;
            transition: background 0.5s, color 0.5s;
            line-height: 1.6;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }

        /* Navigation */
        .top-nav {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 15px 0;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
            animation: fadeIn 0.8s ease-out;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            height: 60px;
            width: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--accent);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            transition: var(--transition);
        }

        .logo:hover {
            transform: rotate(10deg);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .nav-title {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .nav-links {
            display: flex;
            gap: 25px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            padding: 8px 12px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 7px;
            position: relative;
        }

        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.15);
            color: var(--accent-light);
            transform: translateY(-2px);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: var(--accent);
            border-radius: 2px;
            transition: var(--transition);
        }

        .nav-links a:hover::after {
            width: 80%;
        }

        .nav-links a.active {
            background: var(--accent2);
            color: white;
            box-shadow: 0 4px 10px rgba(255, 111, 0, 0.3);
        }

        .nav-links a.active:hover {
            background: var(--accent2-light);
        }

        .menu-icon {
            display: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .menu-icon:hover {
            color: var(--accent);
            transform: scale(1.1);
        }

        /* Theme toggle */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .theme-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .theme-toggle label {
            color: white;
            font-weight: 500;
        }

        .theme-toggle select {
            padding: 8px 15px;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            outline: none;
        }

        .theme-toggle select:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: var(--accent);
        }

        .theme-toggle select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(255, 179, 0, 0.3);
        }

        /* Account Content */
        .account-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            animation: fadeIn 0.6s ease-out 0.2s both;
            flex: 1;
        }

        .account-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px;
            border-radius: var(--radius);
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
        }

        .account-header h1 {
            font-size: 2.8rem;
            color: var(--primary);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent2) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .account-header h1::after {
            content: "";
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--accent2));
            border-radius: 5px;
        }

        .account-header p {
            max-width: 600px;
            margin: 30px auto 0;
            font-size: 1.2rem;
            color: var(--text-light);
            line-height: 1.8;
        }

        .welcome-message {
            text-align: center;
            margin: 20px 0;
            font-size: 1.5rem;
            color: var(--accent2);
            font-weight: 600;
        }

        /* Account Content Layout */
        .account-content {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
        }

        /* Account Sidebar */
        .account-sidebar {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 25px;
            box-shadow: var(--shadow);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .user-profile {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid var(--border);
            margin-bottom: 20px;
        }

        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--accent);
            margin: 0 auto 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .user-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--primary);
        }

        .user-email {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .account-menu {
            list-style: none;
        }

        .account-menu li {
            margin-bottom: 10px;
        }

        .account-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: var(--radius-sm);
            color: var(--text);
            text-decoration: none;
            transition: var(--transition);
            gap: 12px;
            font-weight: 500;
        }

        .account-menu a:hover, .account-menu a.active {
            background: rgba(94, 53, 177, 0.1);
            color: var(--primary);
            transform: translateX(5px);
        }

        .account-menu a i {
            width: 24px;
            text-align: center;
            color: var(--accent2);
        }

        /* Account Main */
        .account-main {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 30px;
            box-shadow: var(--shadow);
        }

        .section-title {
            font-size: 1.8rem;
            margin-bottom: 25px;
            color: var(--primary);
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border);
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 80px;
            height: 2px;
            background: var(--accent2);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .info-card {
            background: rgba(94, 53, 177, 0.05);
            border-radius: var(--radius-sm);
            padding: 20px;
            transition: var(--transition);
            border-left: 4px solid var(--accent2);
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            background: rgba(94, 53, 177, 0.1);
        }

        .info-title {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-title i {
            color: var(--accent2);
        }

        .info-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary);
        }

        .friends-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .friend-card {
            background: var(--card-bg);
            border-radius: var(--radius-sm);
            padding: 15px;
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .friend-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .friend-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 10px;
            border: 2px solid var(--accent);
        }

        .friend-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .friend-location {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .friend-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .friend-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            color: white;
            font-size: 1rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .friend-btn:hover {
            background: var(--primary-dark);
            transform: scale(1.1);
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 60px 0 30px;
            margin-top: 60px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 50px;
        }

        .footer-column h3 {
            font-size: 1.6rem;
            margin-bottom: 25px;
            color: var(--accent);
            position: relative;
            padding-bottom: 15px;
        }

        .footer-column h3::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--accent2);
            border-radius: 2px;
        }

        .footer-column p {
            margin-top: 15px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.85);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 15px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 5px 0;
        }

        .footer-links a:hover {
            color: var(--accent);
            transform: translateX(5px);
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 18px;
        }

        .contact-item i {
            color: var(--accent2);
            font-size: 1.3rem;
            min-width: 24px;
        }

        .copyright {
            text-align: center;
            padding-top: 40px;
            margin-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
        }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            background: var(--error-light);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 20px;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: var(--error);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(244, 67, 54, 0.3);
        }

        /* Responsive */
        @media (max-width: 900px) {
            .account-content {
                grid-template-columns: 1fr;
            }
            
            .account-sidebar {
                position: relative;
                top: 0;
            }
            
            .account-header h1 {
                font-size: 2.4rem;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
                position: absolute;
                top: 80px;
                left: 0;
                right: 0;
                background: var(--primary);
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
                z-index: 1000;
                animation: fadeIn 0.4s ease-out;
            }
            
            .nav-links.active {
                display: flex;
            }
            
            .menu-icon {
                display: block;
            }
            
            .account-header h1 {
                font-size: 2.2rem;
            }
            
            .account-header p {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            .account-header h1 {
                font-size: 2rem;
            }
            
            .friends-list {
                grid-template-columns: 1fr;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--border-light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-light);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="top-nav">
        <div class="nav-container">
            <div class="logo-container">
                <img src="https://via.placeholder.com/60" alt="FriendshipZone Logo" class="logo">
                <div class="nav-title">Friendship Zone</div>
            </div>
            <div class="nav-links">
                <a href="../index.html"><i class="fas fa-project-diagram"></i> Landing Page</a>
                <a href="FriendList.html" class="active"><i class="fas fa-users"></i> Friends</a>
                <a href="Homepage.html"><i class="fas fa-home"></i> Home</a>
                <a href="../Capstone2/home.html"><i class="fas fa-laptop-code"></i> Capstone2</a>
            </div>
            <div class="nav-right">
                <div class="theme-toggle">
                    <label for="theme-select">Theme:</label>
                    <select id="theme-select">
                        <option value="auto">Auto</option>
                        <option value="light">Light</option>
                        <option value="dark">Dark</option>
                    </select>
                </div>
                <div class="menu-icon">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Account Content -->
    <div class="account-container">
        <div class="account-header">
            <h1>Mon Compte</h1>
            <p>Gérez vos informations personnelles, vos amis et vos paramètres de compte</p>
        </div>
        
        <div class="welcome-message">
            <i class="fas fa-smile"></i> Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?> !
        </div>
        
        <div class="account-content">
            <div class="account-sidebar">
                <div class="user-profile">
                    <img src="https://via.placeholder.com/120" alt="Profile Picture" class="profile-pic">
                    <div class="user-name"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                    <div class="user-email">user@friendshipzone.com</div>
                </div>
                
                <ul class="account-menu">
                    <li><a href="#" class="active"><i class="fas fa-user-circle"></i> Profil</a></li>
                    <li><a href="#"><i class="fas fa-users"></i> Amis</a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> Paramètres</a></li>
                    <li><a href="#"><i class="fas fa-shield-alt"></i> Sécurité</a></li>
                    <li><a href="#"><i class="fas fa-bell"></i> Notifications</a></li>
                    <li><a href="#"><i class="fas fa-heart"></i> Intérêts</a></li>
                    <li><a href="#"><i class="fas fa-history"></i> Activité</a></li>
                </ul>
                
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Se déconnecter
                </a>
            </div>
            
            <div class="account-main">
                <h2 class="section-title">Profil</h2>
                
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-title"><i class="fas fa-user"></i> Nom complet</div>
                        <div class="info-value"><?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-title"><i class="fas fa-envelope"></i> Email</div>
                        <div class="info-value">user@friendshipzone.com</div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-title"><i class="fas fa-globe-africa"></i> Pays</div>
                        <div class="info-value">Sénégal</div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-title"><i class="fas fa-calendar"></i> Membre depuis</div>
                        <div class="info-value">15 Juin 2024</div>
                    </div>
                </div>
                
                <h2 class="section-title">Statistiques</h2>
                
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-title"><i class="fas fa-users"></i> Amis</div>
                        <div class="info-value">87</div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-title"><i class="fas fa-comments"></i> Messages</div>
                        <div class="info-value">1,243</div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-title"><i class="fas fa-heart"></i> Intérêts</div>
                        <div class="info-value">12</div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-title"><i class="fas fa-star"></i> Popularité</div>
                        <div class="info-value">92%</div>
                    </div>
                </div>
                
                <h2 class="section-title">Amis récents</h2>
                
                <div class="friends-list">
                    <div class="friend-card">
                        <img src="https://via.placeholder.com/80" alt="Friend" class="friend-avatar">
                        <div class="friend-name">Aïssatou Diop</div>
                        <div class="friend-location">Dakar, Sénégal</div>
                        <div class="friend-actions">
                            <a href="#" class="friend-btn"><i class="fas fa-comment"></i></a>
                            <a href="#" class="friend-btn"><i class="fas fa-user-plus"></i></a>
                        </div>
                    </div>
                    
                    <div class="friend-card">
                        <img src="https://via.placeholder.com/80" alt="Friend" class="friend-avatar">
                        <div class="friend-name">Mohamed Keita</div>
                        <div class="friend-location">Bamako, Mali</div>
                        <div class="friend-actions">
                            <a href="#" class="friend-btn"><i class="fas fa-comment"></i></a>
                            <a href="#" class="friend-btn"><i class="fas fa-user-plus"></i></a>
                        </div>
                    </div>
                    
                    <div class="friend-card">
                        <img src="https://via.placeholder.com/80" alt="Friend" class="friend-avatar">
                        <div class="friend-name">Fatoumata Bâ</div>
                        <div class="friend-location">Conakry, Guinée</div>
                        <div class="friend-actions">
                            <a href="#" class="friend-btn"><i class="fas fa-comment"></i></a>
                            <a href="#" class="friend-btn"><i class="fas fa-user-plus"></i></a>
                        </div>
                    </div>
                    
                    <div class="friend-card">
                        <img src="https://via.placeholder.com/80" alt="Friend" class="friend-avatar">
                        <div class="friend-name">Kwame Nkrumah</div>
                        <div class="friend-location">Accra, Ghana</div>
                        <div class="friend-actions">
                            <a href="#" class="friend-btn"><i class="fas fa-comment"></i></a>
                            <a href="#" class="friend-btn"><i class="fas fa-user-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <h3>Friendship Zone</h3>
                <p>La communauté qui connecte l'Afrique à travers des amitiés authentiques et durables. Rejoignez-nous pour construire des relations significatives à travers le continent.</p>
            </div>
            
            <div class="footer-column">
                <h3>Liens rapides</h3>
                <ul class="footer-links">
                    <li><a href="index.html"><i class="fas fa-home"></i> Accueil</a></li>
                    <li><a href="FriendList.html"><i class="fas fa-users"></i> Amis</a></li>
                    <li><a href="RegistrationForm.html"><i class="fas fa-user-plus"></i> Inscription</a></li>
                    <li><a href="#"><i class="fas fa-info-circle"></i> À propos</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Contactez-nous</h3>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>support@friendshipzone.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Dakar, Sénégal<br>Afrique de l'Ouest</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+221 33 123 4567</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="copyright">
            <p>&copy; 2025 Friendship Zone. Tous droits réservés. | Construire des liens à travers l'Afrique</p>
        </div>
    </footer>

    <script>
        // Theme management
        const themeSelect = document.getElementById('theme-select');
        const root = document.documentElement;

        function applyTheme(theme) {
            if (theme === 'auto') {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    root.setAttribute('data-theme', 'dark');
                } else {
                    root.removeAttribute('data-theme');
                }
            } else {
                root.setAttribute('data-theme', theme);
            }
        }

        // Apply saved or auto theme
        const savedTheme = localStorage.getItem('theme') || 'auto';
        themeSelect.value = savedTheme;
        applyTheme(savedTheme);

        themeSelect.addEventListener('change', () => {
            localStorage.setItem('theme', themeSelect.value);
            applyTheme(themeSelect.value);
        });

        // Menu mobile toggle
        const menuIcon = document.querySelector('.menu-icon');
        const navLinks = document.querySelector('.nav-links');
        
        menuIcon.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            const icon = menuIcon.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.replace('fa-bars', 'fa-times');
            } else {
                icon.classList.replace('fa-times', 'fa-bars');
            }
        });

        // Simulate loading user data
        document.addEventListener('DOMContentLoaded', () => {
            // This would be replaced with actual data from your backend
            console.log("Account page loaded for user: <?php echo htmlspecialchars($_SESSION['user_name']); ?>");
            
            // Add hover effect to all cards
            const cards = document.querySelectorAll('.info-card, .friend-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-5px)';
                    card.style.boxShadow = 'var(--shadow-hover)';
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.transform = '';
                    card.style.boxShadow = '';
                });
            });
        });
    </script>
</body>
</html>