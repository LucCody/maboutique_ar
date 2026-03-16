<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Boutique Ar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">🛒 Ma Boutique Ar</div>
    <nav>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="index.php">Accueil</a>
            <a href="boutique.php">Boutique</a>
            <a href="panier.php" style="background:#e67e22; padding:5px 10px; border-radius:5px;">
                🛍️ Panier (<?php echo isset($_SESSION['panier']) ? array_sum($_SESSION['panier']) : 0; ?>)
            </a>
            <a href="mes_commandes.php">Mes Achats</a>
            <?php if($_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php" style="color:#f1c40f; font-weight:bold;">📊 Admin</a>
            <?php endif; ?>
            <a href="logout.php" class="btn-red" style="padding:5px 10px; border-radius:5px; margin-left:10px;">Quitter</a>
        <?php else: ?>
            <a href="login.php">Connexion</a>
            <a href="register.php">Inscription</a>
        <?php endif; ?>
    </nav>
</header>
<div class="container">
