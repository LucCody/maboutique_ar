<?php
// On s'assure que la session est démarrée
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Inclusion de la connexion à la base de données
include('db.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Model - Premium Services</title>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        /* --- VARIABLES --- */
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
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            line-height: 1.5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px;
            flex-grow: 1;
        }

        /* --- HEADER & NAV --- */
        header {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .logo a {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text);
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text);
            cursor: pointer;
            padding: 5px;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        nav a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        nav a:hover { color: var(--text); }

        .badge-solde {
            background: #eff6ff;
            color: var(--primary) !important;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            border: 1px solid #bfdbfe;
        }

        /* --- BUTTONS --- */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            text-align: center;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            background: var(--primary);
            color: #fff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .btn:hover { background: var(--primary-hover); }

        /* --- FORMS & AUTH CARDS --- */
        .auth-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
        }

        form {
            background: var(--card);
            padding: 40px;
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        form h2 { margin-bottom: 24px; text-align: center; font-size: 1.5rem; }
        label { display: block; margin-bottom: 6px; font-weight: 500; font-size: 0.9rem; }
        
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], textarea {
            width: 100%; padding: 10px 12px; margin-bottom: 20px;
            border: 1px solid var(--border); border-radius: 6px;
            font-size: 1rem; transition: border-color 0.2s;
        }
        input:focus, textarea:focus {
            outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        /* --- PRODUCT CARDS (For Boutique.php) --- */
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 24px; }
        .card { background: var(--card); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; display: flex; flex-direction: column; height: 100%; }
        .card-img-container { width: 100%; height: 180px; background: #f3f4f6; border-bottom: 1px solid var(--border); }
        .card img { width: 100%; height: 100%; object-fit: contain; padding: 20px; }
        .card-content { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
        .card-content h3 { font-size: 1.1rem; font-weight: 600; margin-bottom: 8px; }
        .price { font-size: 1.25rem; font-weight: 700; color: var(--text); margin-bottom: 16px; }

        /* --- MOBILE RESPONSIVE & JS MENU --- */
        @media (max-width: 768px) {
            header { flex-wrap: wrap; }
            .mobile-menu-btn { display: block; }
            nav {
                display: none; width: 100%; flex-direction: column;
                margin-top: 15px; padding-top: 15px; border-top: 1px solid var(--border);
            }
            nav.active { display: flex; }
            nav a { width: 100%; text-align: center; padding: 10px; background: #f3f4f6; border-radius: 6px; }
            form { padding: 30px 20px; }
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <a href="index.php">Expert Model</a>
    </div>
    
    <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Ouvrir le menu">
        <svg viewBox="0 0 24 24" width="28" height="28" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>

    <nav id="mainNav">
        <?php if(isset($_SESSION['user_id'])): 
            $u_id = $_SESSION['user_id'];
            $stmt_u = $conn->prepare("SELECT solde FROM utilisateurs WHERE id = ?");
            if ($stmt_u) {
                $stmt_u->bind_param("i", $u_id);
                $stmt_u->execute();
                $u_data = $stmt_u->get_result()->fetch_assoc();
                $mon_solde = number_format($u_data['solde'], 0, '.', ' ');
            } else {
                $mon_solde = "0";
            }
        ?>
            <a href="index.php">Accueil</a>
            <a href="boutique.php">Boutique</a>
            <a href="mes_commandes.php">Mes Achats</a>
            
            <a href="profil.php" class="badge-solde">
                💰 <?php echo $mon_solde; ?> Ar
            </a>

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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('mobileMenuBtn');
        const nav = document.getElementById('mainNav');
        if (btn && nav) {
            btn.addEventListener('click', () => {
                nav.classList.toggle('active');
            });
        }
    });
</script>

<div class="container">
