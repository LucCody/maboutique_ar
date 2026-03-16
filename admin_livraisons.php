<?php

include('db.php');
include('header.php');

session_start();
if ($_SESSION['role'] !== 'admin') { header("Location: login.php"); exit(); }

if (isset($_POST['livrer'])) {
    $stmt = $conn->prepare("UPDATE commandes SET infos_livraison = ?, notes_admin = ?, statut = 'livré' WHERE id = ?");
    $stmt->bind_param("ssi", $_POST['infos'], $_POST['notes'], $_POST['id_c']);
    $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Livraisons</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">🛠 Panel Admin</div>
        <nav>
            <a href="admin_dashboard.php">📊 Dashboard</a>
            <a href="admin_produits.php">📦 Produits</a>
            <a href="admin_livraisons.php" style="background:#34495e; padding:5px 10px; border-radius:5px;">🚚 Livraisons</a>
            <a href="logout.php" class="btn-red">Quitter</a>
        </nav>
    </header>

    <div class="container">
        <h1>Commandes à traiter</h1>
        <?php
        $res = $conn->query("SELECT c.*, u.pseudo FROM commandes c JOIN utilisateurs u ON c.user_id = u.id WHERE c.statut = 'en attente'");
        while($c = $res->fetch_assoc()) {
            echo "<div class='card' style='max-width:100%; text-align:left; margin-bottom:20px;'>";
                echo "<h3>#".$c['id']." - ".$c['produit']." (Client: ".$c['pseudo'].")</h3>";
                echo "<form method='POST'>";
                    echo "<input type='hidden' name='id_c' value='".$c['id']."'>";
                    echo "<textarea name='infos' placeholder='Mail:Mdp ou Clé' required></textarea>";
                    echo "<input type='text' name='notes' placeholder='Notes privées admin'>";
                    echo "<button type='submit' name='livrer' class='btn' style='margin-top:10px;'>🚀 Livrer</button>";
                echo "</form>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
