<?php
include('db.php');
include('header.php');

$message = "";

if (isset($_POST['oublie'])) {
    $email = trim($_POST['email']);
    
    // Vérifier si l'email existe dans la base
    $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        // Générer un jeton unique et une date d'expiration (+1 heure)
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));
        
        $stmt_update = $conn->prepare("UPDATE utilisateurs SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $stmt_update->bind_param("sss", $token, $expires, $email);
        $stmt_update->execute();
        
        // Construire le lien de réinitialisation
        $reset_link = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/reset_password.php?token=" . $token;
        
        // Affichage du lien (Simulation de l'email pour InfinityFree)
        $message = "
        <div style='background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: left; font-size: 0.9rem;'>
            <strong>Simulation d'email envoyé :</strong><br>
            Cliquez sur ce lien pour réinitialiser votre mot de passe (valable 1 heure) :<br><br>
            <a href='$reset_link' style='color: var(--primary); font-weight: 600; word-break: break-all;'>$reset_link</a>
        </div>";
    } else {
        $message = "<div style='background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #f87171;'>Aucun compte n'est associé à cette adresse e-mail.</div>";
    }
}
?>

<div class="container auth-wrapper">
    <form method="POST">
        <h2>Mot de passe oublié</h2>
        <p style="text-align: center; color: var(--text-muted); font-size: 0.9rem; margin-bottom: 20px;">
            Entrez votre adresse e-mail et nous vous fournirons un lien pour réinitialiser votre mot de passe.
        </p>
        
        <?php echo $message; ?>

        <label for="email">Adresse E-mail</label>
        <input type="email" id="email" name="email" placeholder="votre@email.com" required>

        <button type="submit" name="oublie" class="btn" style="width:100%;">Générer le lien</button>
        
        <p style="text-align:center; margin-top:24px; font-size: 0.9rem;">
            <a href="login.php" style="color: var(--text-muted); font-weight: 500; text-decoration: none;">&larr; Retour à la connexion</a>
        </p>
    </form>
</div>

<?php include('footer.php'); ?>
