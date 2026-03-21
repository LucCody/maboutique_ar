<?php
include('db.php');
include('header.php');
?>

<div style="text-align: center; padding: 60px 20px; background: var(--card); border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid var(--border); max-width: 800px; margin: 40px auto; transition: background-color 0.3s ease, border-color 0.3s ease;">
    
    <h1 style="color: var(--text); font-size: 2.5rem; margin-bottom: 15px; transition: color 0.3s ease;">
        Bienvenue sur Expert Model
    </h1>
    
    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 30px; transition: color 0.3s ease;">
        Le meilleur des services numériques au prix juste.
    </p>

    <?php if(!isset($_SESSION['user_id'])): ?>
        <a href="login.php" class="btn" style="font-size: 1.1rem; padding: 12px 30px;">🚀 Commencer maintenant</a>
    <?php else: ?>
        <a href="boutique.php" class="btn" style="font-size: 1.1rem; padding: 12px 30px;">🛒 Accéder à la Boutique</a>
    <?php endif; ?>

</div>

<?php include('footer.php'); ?>
