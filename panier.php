<?php
include('db.php');
include('header.php');

session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

if (isset($_GET['action']) && $_GET['action'] == "vider") { unset($_SESSION['panier']); header("Location: panier.php"); }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">🛍️ Panier</div>
        <nav>
            <a href="boutique.php">Boutique</a>
            <?php if($_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php" style="color:#f1c40f; font-weight:bold;">📊 Dashboard Admin</a>
            <?php endif; ?>
            <a href="logout.php" class="btn-red">Quitter</a>
        </nav>
    </header>

    <div class="container">
        <h1>Votre sélection</h1>
        <table>
            <tr><th>Produit</th><th>Quantité</th><th>Total</th></tr>
            <?php
            $total = 0;
            if (!empty($_SESSION['panier'])) {
                // Prepare the statement ONCE outside the loop for better performance and security
                $stmt = $conn->prepare("SELECT * FROM produits WHERE id = ?");
                
                foreach ($_SESSION['panier'] as $id => $qte) {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $p = $stmt->get_result()->fetch_assoc();
                    
                    if ($p) {
                        $sous_total = $p['prix'] * $qte;
                        $total += $sous_total;
                        echo "<tr><td>".htmlspecialchars($p['nom'])."</td><td>$qte</td><td>".number_format($sous_total, 0, '.', ' ')." Ar</td></tr>";
                    }
                }
            }
            ?>
        </table>
        <h2 style="text-align:right;">Total : <?php echo number_format($total, 0, '.', ' '); ?> Ar</h2>
        <div style="text-align:right;">
            <a href="panier.php?action=vider" class="btn btn-red">Vider</a>
            <a href="valider_panier.php" class="btn">💳 Payer maintenant</a>
        </div>
    </div>
</body>
</html>
