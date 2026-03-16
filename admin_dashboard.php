<?php
include('db.php');
include('header.php');
if ($_SESSION['role'] !== 'admin') { header("Location: login.php"); exit(); }

$total = $conn->query("SELECT SUM(prix) as t FROM commandes WHERE statut='livré'")->fetch_assoc()['t'];
$nb_attente = $conn->query("SELECT COUNT(*) as n FROM commandes WHERE statut='en attente'")->fetch_assoc()['n'];
?>
<h1>📊 Dashboard Admin</h1>
<div style="display:flex; gap:20px; margin-bottom:30px;">
    <div class='card' style='flex:1;'><h3>Ventes totales</h3><p><?php echo number_format($total, 0, '.', ' '); ?> Ar</p></div>
    <div class='card' style='flex:1;'><h3>Commandes à livrer</h3><p style='color:red;'><?php echo $nb_attente; ?></p></div>
</div>
<a href="admin_produits.php" class="btn">📦 Gérer Produits</a>
<a href="admin_livraisons.php" class="btn" style="background:#e67e22;">🚚 Livrer Commandes</a>
<a href="liste.php" class="btn" style="background:#34495e;">👥 Membres</a>
<?php include('footer.php'); ?>
