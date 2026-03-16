<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('db.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universal Move Shop</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://googleapis.com" rel="stylesheet">
</head>
<body>
<header>
    <div class="logo">Universal Shop</div>
    <nav>
        <?php if(isset($_SESSION['user_id'])): 
            $u_id = $_SESSION['user_id'];
            $u_data = $conn->query("SELECT solde FROM utilisateurs WHERE id = $u_id")->fetch_assoc();
        ?>
            <a href="boutique.php">Boutique</a>
            <a href="mes_commandes.php">Mes Achats</a>
            
            <!-- Affichage du solde stylisé -->
            <a href="profil.php" style="background: rgba(230, 126, 34, 0.1); color: #e67e22; padding: 8px 15px; border-radius: 30px; border: 1px solid #e67e22;">
                💰 <?php echo number_format($u_data['solde'], 0, '.', ' '); ?> Ar
            </a>

            <?php if($_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php" style="color:#f1c40f;">📊 Admin</a>
            <?php endif; ?>
            
            <a href="logout.php" style="color: #e74c3c;">Quitter</a>
        <?php else: ?>
            <a href="login.php">Connexion</a>
            <a href="register.php" class="btn" style="display:inline; padding: 8px 15px;">S'inscrire</a>
        <?php endif; ?>
    </nav>
</header>
<div class="container">
