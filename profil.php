<?php
include('db.php');
include('header.php');

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$u_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM utilisateurs WHERE id = $u_id")->fetch_assoc();
?>

<div class="container" style="text-align:center; padding-top: 50px;">
    <h1 style="color: var(--primary);">💳 Mon Portefeuille</h1>
    
    <!-- Carte du Solde -->
    <div class="card" style="max-width: 450px; margin: 30px auto; padding: 40px; border-top: 5px solid var(--primary); background: var(--card);">
        <p style="color: #94a3b8; font-size: 1.1em; margin-bottom: 10px;">Solde disponible :</p>
        <h2 style="font-size: 3.5em; margin: 0; color: #fff;">
            <?php echo number_format($user['solde'], 0, '.', ' '); ?> <span style="font-size: 0.4em; color: var(--primary);">Ar</span>
        </h2>
    </div>

    <!-- Instructions de Recharge -->
    <div style="background: rgba(243, 156, 18, 0.05); border: 1px dashed var(--primary); padding: 30px; border-radius: 15px; max-width: 600px; margin: 40px auto; text-align: left;">
        <h3 style="color: var(--primary); margin-top: 0;">⚡ Comment recharger mon compte ?</h3>
        <p>Pour ajouter du crédit à votre compte, veuillez effectuer un dépôt via mobile money :</p>
        
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 15px;">🟠 <strong>Orange Money :</strong> 032 XX XX XX XX</li>
            <li style="margin-bottom: 15px;">🟢 <strong>MVola :</strong> 034 XX XX XX XX</li>
        </ul>

        <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px; font-size: 0.9em; border-left: 4px solid var(--primary);">
            ⚠️ <strong>IMPORTANT :</strong> Dans le motif du transfert, indiquez impérativement votre pseudo : <strong style="color:var(--primary);"><?php echo $_SESSION['pseudo']; ?></strong>. 
            Votre solde sera crédité manuellement par l'administrateur dans les 15 minutes.
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
