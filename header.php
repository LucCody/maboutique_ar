<?php
// On s'assure que la session est démarrée
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Inclusion de la connexion sécurisée
include('db.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universal Shop - Premium Services</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

<header>
    <div class="logo">
        <a href="index.php" style="color: inherit; text-decoration: none;">Universal Shop</a>
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
            $stmt_u->bind_param("i", $u_id);
            $stmt_u->execute();
            $u_data = $stmt_u->get_result()->fetch_assoc();
            $mon_solde = number_format($u_data['solde'], 0, '.', ' ');
        ?>
            <a href="index.php">Accueil</a>
            <a href="boutique.php">Boutique</a>
            <a href="mes_commandes.php">Mes Achats</a>
            
            <a href="profil.php" class="badge-solde">
                💰 <?php echo $mon_solde; ?> Ar
            </a>

            <?php if($_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php" class="badge-admin">📊 Admin</a>
            <?php endif; ?>
            
            <a href="logout.php" class="text-danger">Quitter</a>

        <?php else: ?>
            <a href="index.php">Accueil</a>
            <a href="login.php">Connexion</a>
            <a href="register.php" class="btn btn-sm">S'inscrire</a>
        <?php endif; ?>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('mobileMenuBtn');
        const nav = document.getElementById('mainNav');
        
        btn.addEventListener('click', () => {
            // Toggles the 'active' class on the nav element
            nav.classList.toggle('active');
        });
    });
</script>

<div class="container">
