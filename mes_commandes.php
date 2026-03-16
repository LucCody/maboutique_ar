<?php 
include('db.php'); 
include('header.php'); 
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$user_id = $_SESSION['user_id'];
$res = $conn->query("SELECT * FROM commandes WHERE user_id = $user_id ORDER BY id DESC");
?>
<h1>📦 Mes Achats</h1>
<table>
    <tr><th>Produit</th><th>Prix</th><th>Statut / Accès</th><th>Action</th></tr>
    <?php while($c = $res->fetch_assoc()): ?>
    <tr>
        <td><strong><?php echo $c['produit']; ?></strong></td>
        <td><?php echo number_format($c['prix'], 0, '.', ' '); ?> Ar</td>
        <td>
            <?php if($c['statut'] === 'en attente'): ?>
                <span style="color:#e67e22;">⏳ En attente...</span>
            <?php else: ?>
                <div style="background:#e8f5e9; padding:5px; border-radius:5px; border:1px solid #27ae60;">
                    <code><?php echo nl2br(htmlspecialchars($c['infos_livraison'])); ?></code>
                </div>
            <?php endif; ?>
        </td>
        <td><a href="facture.php?id=<?php echo $c['id']; ?>" target="_blank">📄 Facture</a></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php include('footer.php'); ?>
