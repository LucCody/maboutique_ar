<?php
include('db.php');
include('header.php');

$message = "";
$token_valid = false;
$user_id = null;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $now = date("Y-m-d H:i:s");
    
    // Vérifier si le jeton existe et n'a pas expiré
    $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE reset_token = ? AND reset_expires > ?");
    $stmt->bind_param("ss", $token, $now);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        $token_valid = true;
        $user = $res->fetch_assoc();
        $user_id = $user['id'];
        
        // Si le formulaire est soumis pour changer le mot de passe
        if (isset($_POST['reset'])) {
            $new_password = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
            
            // Mettre à jour le mot de passe et supprimer le jeton
            $stmt_update = $conn->prepare("UPDATE utilisateurs SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
            $stmt_update->bind_param("si", $new_password, $user_id);
            
            if ($stmt_update->execute()) {
                $message = "<div style='background: #d1fae5; color: #065f46; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #34d399;'>Votre mot de passe a été modifié avec succès ! <br><br><a href='login.php' style='color:#065f46; font-weight:bold;'>Cliquez ici pour vous connecter</a></div>";
                $token_valid = false; // Cacher le formulaire
            } else {
                $message = "<div style='color: red;'>Erreur lors de la mise à jour.</div>";
            }
        }
    } else {
        $message = "<div style='background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #f87171;'>Ce lien de réinitialisation est invalide ou a expiré.</div>";
    }
} else {
    $message = "<div style='background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #f87171;'>Aucun jeton fourni.</div>";
}
?>

<div class="container auth-wrapper">
    <form method="POST">
        <h2>Nouveau mot de passe</h2>
        
        <?php echo $message; ?>

        <?php if ($token_valid): ?>
            <label for="mdp">Nouveau mot de passe</label>
            <input type="password" id="mdp" name="mdp" placeholder="••••••••" required>
            
            <button type="submit" name="reset" class="btn" style="width:100%;">Confirmer le changement</button>
        <?php endif; ?>
    </form>
</div>

<?php include('footer.php'); ?>
