<?php
include('db.php');
include('header.php');

session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }


if (isset($_GET['id'])) {
    $id_c = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // On récupère la commande et les infos du client (Jointure)
    $sql = "SELECT c.*, u.pseudo, u.email 
            FROM commandes c 
            JOIN utilisateurs u ON c.user_id = u.id 
            WHERE c.id = ? AND (c.user_id = ? OR ? = 'admin')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $id_c, $user_id, $_SESSION['role']);
    $stmt->execute();
    $res = $stmt->get_result();
    $f = $res->fetch_assoc();

    if (!$f) { die("Facture introuvable ou accès refusé."); }
} else {
    header("Location: mes_commandes.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #<?php echo $f['id']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; padding: 40px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #27ae60; padding-bottom: 20px; }
        .details { margin-top: 30px; display: flex; justify-content: space-between; }
        table { width: 100%; border-collapse: collapse; margin-top: 40px; }
        th { background: #f4f4f4; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .total { text-align: right; font-size: 1.5em; margin-top: 20px; color: #27ae60; }
        .no-print { margin-top: 30px; text-align: center; }
        @media print { .no-print { display: none; } body { padding: 0; } .invoice-box { border: none; box-shadow: none; } }
    </style>
</head>
<body>

<div class="invoice-box">
    <div class="header">
        <div>
            <h1 style="color:#27ae60; margin:0;">FACTURE</h1>
            <p>Réf : #<?php echo $f['id']; ?></p>
        </div>
        <div style="text-align:right;">
            <strong>Expert Model</strong><br>
            Antananarivo, Madagascar<br>
            contact@maboutique.mg
        </div>
    </div>

    <div class="details">
        <div>
            <strong>Facturé à :</strong><br>
            <?php echo htmlspecialchars($f['pseudo']); ?><br>
            <?php echo htmlspecialchars($f['email']); ?>
        </div>
        <div style="text-align:right;">
            <strong>Date :</strong><br>
            <?php echo $f['date_commande']; ?>
        </div>
    </div>

    <table>
        <tr>
            <th>Désignation du produit</th>
            <th style="text-align:right;">Prix Unitaire</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($f['produit']); ?></td>
            <td style="text-align:right;"><?php echo number_format($f['prix'], 0, '.', ' '); ?> Ar</td>
        </tr>
    </table>

    <div class="total">
        <strong>TOTAL : <?php echo number_format($f['prix'], 0, '.', ' '); ?> Ar</strong>
    </div>

    <div style="margin-top:50px; font-size:0.8em; color:gray;">
        <p>Statut : <?php echo strtoupper($f['statut']); ?></p>
        <?php if(!empty($f['infos_livraison'])): ?>
            <p>Codes livrés : <?php echo nl2br(htmlspecialchars($f['infos_livraison'])); ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="no-print">
    <button onclick="window.print();" style="padding:10px 20px; background:#27ae60; color:white; border:none; border-radius:5px; cursor:pointer;">🖨️ Imprimer la facture</button>
    <br><br>
    <a href="mes_commandes.php" style="color:#666;">Retour à mes commandes</a>
</div>

</body>
</html>
