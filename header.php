<?php
// On s'assure que la session est démarrée
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Inclusion de la connexion à la base de données
require_once('db.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Model - Premium Services</title>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        /* --- VARIABLES THEME CLAIR --- */
        :root {
            --bg: #f9fafb;
            --card: #ffffff;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --text: #111827;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --success: #10b981;
            --danger: #ef4444;
            --header-bg: #ffffff;
            --badge-bg: #eff6ff;
            --input-bg: #ffffff;
            --lang-hover-bg: #f3f4f6;
        }

        /* --- VARIABLES THEME SOMBRE --- */
        body.dark-theme {
            --bg: #0f172a;
            --card: #1e293b;
            --primary: #3b82f6; 
            --primary-hover: #60a5fa;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.1);
            --header-bg: rgba(15, 23, 42, 0.95);
            --badge-bg: rgba(59, 130, 246, 0.15);
            --input-bg: rgba(0, 0, 0, 0.2);
            --lang-hover-bg: rgba(255, 255, 255, 0.05);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        /* ANTI GOOGLE TRANSLATE BANNER - RÈGLE ABSOLUE */
        body {
            top: 0 !important;
            position: static !important;
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            line-height: 1.5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease; 
        }

        .VIpgJd-ZVi9od-ORHb-OEVmcd, 
        .skiptranslate,
        iframe.goog-te-banner-frame {
            display: none !important;
            visibility: hidden !important;
            height: 0px !important;
            min-height: 0px !important;
            padding: 0px !important;
            margin: 0px !important;
        }

        #google_translate_element { display: none !important; }
        .goog-tooltip { display: none !important; }

        .container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px;
            flex-grow: 1;
        }

        /* --- HEADER & NAV --- */
        header {
            background: var(--header-bg);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            backdrop-filter: blur(10px); 
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .logo a {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text);
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .header-controls {
            display: flex;
            align-items: center;
            gap: 15px; 
        }

        /* --- CUSTOM LANGUAGE SELECTOR DESIGN --- */
        .custom-lang-selector {
            background-color: transparent; 
            color: var(--text-muted);
            border: 1px solid var(--border);
            border-radius: 20px; 
            padding: 6px 14px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            outline: none;
            transition: all 0.2s ease;
            appearance: none; 
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%236b7280%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat, repeat;
            background-position: right .7em top 50%, 0 0;
            background-size: .65em auto, 100%;
            padding-right: 2em; 
        }

        .custom-lang-selector:hover {
            color: var(--text);
            border-color: var(--text-muted);
            background-color: var(--lang-hover-bg);
        }

        .custom-lang-selector:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }
        
        .custom-lang-selector option {
            background-color: var(--card); 
            color: var(--text);
            font-weight: 500;
        }

        /* --- BOUTON THEME TOGGLE --- */
        .theme-toggle-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .theme-toggle-btn:hover { color: var(--text); background: rgba(128, 128, 128, 0.1); }
        .icon-sun { display: none; }
        body.dark-theme .icon-moon { display: none; }
        body.dark-theme .icon-sun { display: block; color: #fbbf24; }

        .mobile-menu-btn { display: none; background: none; border: none; color: var(--text); cursor: pointer; padding: 5px; }

        nav { display: flex; align-items: center; gap: 20px; }
        nav a { color: var(--text-muted); text-decoration: none; font-weight: 500; font-size: 0.95rem; transition: color 0.2s; }
        nav a:hover { color: var(--text); }

        .badge-solde {
            background: var(--badge-bg);
            color: var(--primary) !important;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            border: 1px solid var(--border);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        /* --- BUTTONS --- */
        .btn {
            display: inline-block; padding: 10px 20px; border-radius: 6px; text-decoration: none;
            text-align: center; font-weight: 500; font-size: 0.95rem; transition: all 0.2s; border: none;
            cursor: pointer; background: var(--primary); color: #fff; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .btn:hover { background: var(--primary-hover); }

        /* --- FORMS & AUTH CARDS --- */
        .auth-wrapper { display: flex; justify-content: center; align-items: center; min-height: 70vh; }
        form {
            background: var(--card); padding: 40px; border-radius: 12px; border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        form h2 { margin-bottom: 24px; text-align: center; font-size: 1.5rem; color: var(--text); }
        label { display: block; margin-bottom: 6px; font-weight: 500; font-size: 0.9rem; color: var(--text); }
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], textarea {
            width: 100%; padding: 10px 12px; margin-bottom: 20px; border: 1px solid var(--border); border-radius: 6px;
            font-size: 1rem; transition: border-color 0.2s; background: var(--input-bg); color: var(--text);
        }
        input:focus, textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15); }

        /* --- PRODUCT CARDS (For Boutique.php) --- */
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 24px; }
        .card { background: var(--card); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; display: flex; flex-direction: column; height: 100%; transition: transform 0.2s, box-shadow 0.2s, background-color 0.3s ease, border-color 0.3s ease; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .card-img-container { width: 100%; height: 180px; background: var(--card); border-bottom: 1px solid var(--border); display:flex; align-items:center; justify-content:center;}
        body.dark-theme .card-img-container img { background-color: white; border-radius: 8px;}
        .card img { max-width: 100%; max-height: 100%; object-fit: contain; padding: 15px; }
        .card-content { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
        .card-content h3 { font-size: 1.1rem; font-weight: 600; margin-bottom: 8px; color: var(--text); }
        .price { font-size: 1.25rem; font-weight: 700; color: var(--text); margin-bottom: 16px; }

        /* --- MOBILE RESPONSIVE --- */
        @media (max-width: 768px) {
            header { flex-wrap: wrap; }
            .header-controls { gap: 10px; }
            .mobile-menu-btn { display: block; }
            nav { display: none; width: 100%; flex-direction: column; margin-top: 15px; padding-top: 15px; border-top: 1px solid var(--border); }
            nav.active { display: flex; }
            nav a { width: 100%; text-align: center; padding: 10px; background: var(--input-bg); border-radius: 6px; }
            form { padding: 30px 20px; }
        }
    </style>
    
    <script>
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            document.documentElement.classList.add('dark-theme'); 
        }
    </script>
</head>
<body class="">
    <script>
        if (document.documentElement.classList.contains('dark-theme')) {
            document.body.classList.add('dark-theme');
        }
    </script>

<div id="google_translate_element"></div>

<header>
    <div class="logo">
        <a href="index.php" class="notranslate">Expert Model</a>
    </div>
    
    <div class="header-controls">
        
        <select class="custom-lang-selector notranslate" id="customLangSelector" aria-label="Choisir la langue">
            <option value="fr">FR</option>
            <option value="en">EN</option>
            <option value="mg">MG</option>
        </select>

        <button class="theme-toggle-btn" id="themeToggleBtn" aria-label="Changer le thème">
            <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
            <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
        </button>

        <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Ouvrir le menu">
            <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        </button>
    </div>

    <nav id="mainNav">
        <?php 
        if(isset($_SESSION['user_id'])): 
            $mon_solde = "0"; 
            if (isset($conn) && $conn instanceof mysqli) { 
                $u_id = $_SESSION['user_id'];
                $stmt_u = $conn->prepare("SELECT solde FROM utilisateurs WHERE id = ?");
                if ($stmt_u) {
                    $stmt_u->bind_param("i", $u_id);
                    $stmt_u->execute();
                    $res_u = $stmt_u->get_result();
                    if ($u_data = $res_u->fetch_assoc()) {
                        $mon_solde = number_format($u_data['solde'], 0, '.', ' ');
                    }
                }
            }
        ?>
            <a href="index.php">Accueil</a>
            <a href="boutique.php">Boutique</a>
            <a href="mes_commandes.php">Mes Achats</a>
            <a href="profil.php" class="badge-solde notranslate">💰 <?php echo $mon_solde; ?> Ar</a>

            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php" style="color: #ea580c; font-weight: 600;">📊 Admin</a>
            <?php endif; ?>
            <a href="logout.php" style="color: var(--danger); font-weight: 600;">Quitter</a>
        <?php else: ?>
            <a href="index.php">Accueil</a>
            <a href="login.php">Connexion</a>
            <a href="register.php" class="btn" style="color: white;">S'inscrire</a>
        <?php endif; ?>
    </nav>
</header>

<script type="text/javascript">
function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'fr', 
        includedLanguages: 'fr,en,mg', 
        autoDisplay: false
    }, 'google_translate_element');
}
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- TRANSLATE LOGIC ---
        const customSelector = document.getElementById('customLangSelector');
        
        customSelector.addEventListener('change', function() {
            let targetLang = this.value;
            let googleSelect = document.querySelector('.goog-te-combo');
            
            if (googleSelect) {
                googleSelect.value = targetLang;
                googleSelect.dispatchEvent(new Event('change', { 'bubbles': true }));
            }
        });

        // --- Menu Mobile ---
        const btnMenu = document.getElementById('mobileMenuBtn');
        const nav = document.getElementById('mainNav');
        if (btnMenu && nav) {
            btnMenu.addEventListener('click', () => {
                nav.classList.toggle('active');
            });
        }

        // --- Theme Toggle ---
        const themeBtn = document.getElementById('themeToggleBtn');
        if (themeBtn) {
            themeBtn.addEventListener('click', () => {
                document.body.classList.toggle('dark-theme');
                document.documentElement.classList.toggle('dark-theme');
                if (document.body.classList.contains('dark-theme')) {
                    localStorage.setItem('theme', 'dark');
                } else {
                    localStorage.setItem('theme', 'light');
                }
            });
        }
    });
</script>

<div class="container">
