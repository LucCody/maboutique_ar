<?php
// On s'assure que la session est démarrée
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Inclusion de la connexion à la base de données (nécessaire pour le solde)
include('db.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universal Shop - Premium Services</title>
    <!-- Google Fonts pour un look moderne -->
    <link href="https://googleapis.com" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">Universal Shop</div>
    <nav>
        <?php if(isset($_SESSION['user_id'])): 
            // RÉCUPÉRATION DU SOLDE EN TEMPS RÉEL
            $u_id = $_SESSION['user_id'];
            $stmt_u = $conn->prepare("SELECT solde FROM utilisateurs WHERE id = ?");
            $stmt_u->bind_param("i", $u_id);
            $stmt_u->execute();
            $u_data = $stmt_u->get_result()->fetch_assoc();
            $mon_solde = number_format($u_data['solde'], 0, '.', ' ');
        ?>
            <!-- Menu Client Connecté -->
            <a href="index.php">Accueil</a>
            <a href="boutique.php">Boutique</a>
            <a href="mes_commandes.php">Mes Achats</a>
            
            <!-- Badge de Solde (Lien vers Profil) -->
            <a href="profil.php" style="background: rgba(243, 156, 18, 0.15); color: #f39c12; padding: 8px 15px; border-radius: 30px; border: 1px solid #f39c12; font-weight: bold; margin-left: 10px;">
                💰 <?php echo $mon_solde; ?> Ar
            </a>

            <!-- Accès Admin si autorisé -->
            <?php if($_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php" style="color:#f1c40f; font-weight:bold; margin-left:15px;">📊 Admin</a>
            <?php endif; ?>
            
            <a href="logout.php" style="color: #ff4757; margin-left:15px; font-weight:600;">Quitter</a>

        <?php else: ?>
            <!-- Menu Visiteur (Non connecté) -->
            <a href="index.php">Accueil</a>
            <a href="login.php">Connexion</a>
            <a href="register.php" class="btn" style="display:inline-block; padding: 8px 20px; font-size: 0.9em; margin-left:15px;">S'inscrire</a>
        <?php endif; ?>
    </nav>
</header>

<!-- Début du contenu principal -->
<div class="container">
