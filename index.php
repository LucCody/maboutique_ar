<?php
include('db.php');
include('header.php');
?>
<div style="text-align: center; padding: 50px 20px; background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    <h1>Bienvenue sur Expert Model Madagascar</h1>
    <p>Le meilleur des services numériques au prix juste.</p>
    <?php if(!isset($_SESSION['user_id'])): ?>
        <a href="login.php" class="btn" style="margin-top:20px;">🚀 Commencer maintenant</a>
    <?php else: ?>
        <a href="boutique.php" class="btn" style="margin-top:20px;">🛒 Accéder à la Boutique</a>
    <?php endif; ?>
</div>
<?php include('footer.php'); ?>
