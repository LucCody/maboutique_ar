<?php
// 1. On démarre la session tout en haut AVANT tout code HTML
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// 2. On inclut uniquement la base de données
include('db.php');

$err = ""; // On initialise la variable d'erreur

// 3. On traite le formulaire de connexion
if (isset($_POST['connexion'])) {
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $_POST['email']);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($user = $res->fetch_assoc()) {
        if (password_verify($_POST['mdp'], $user['password'])) {
            // Création des variables de session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['pseudo'] = $user['pseudo'];
            $_SESSION['role'] = $user['role'];
            
            // 4. LA REDIRECTION MARCHE ICI car aucun HTML n'a encore été affiché
            header("Location: " . ($user['role'] === 'admin' ? "admin_dashboard.php" : "boutique.php"));
            exit(); // On arrête le script immédiatement après la redirection
        }
    }
    $err = "Identifiants incorrects. Veuillez réessayer.";
}

// 5. SEULEMENT MAINTENANT on inclut le design (qui génère du HTML)
include('header.php');
?>

<div class="container auth-wrapper">
    <form method="POST">
        <h2>Connexion</h2>
        
        <?php if(!empty($err)): ?>
            <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #f87171;">
                <?php echo $err; ?>
            </div>
        <?php endif; ?>

        <label for="email">Adresse E-mail</label>
        <input type="email" id="email" name="email" placeholder="votre@email.com" required>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
            <label for="mdp" style="margin-bottom: 0;">Mot de passe</label>
            <a href="forgot_password.php" style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 500;">Oublié ?</a>
        </div>
        <input type="password" id="mdp" name="mdp" placeholder="••••••••" required>

        <button type="submit" name="connexion" class="btn" style="width:100%; margin-top: 10px;">Se connecter</button>
        
        <p style="text-align: center; margin-top: 24px; font-size: 0.9rem; color: var(--text-muted);">
            Pas encore de compte ? <a href="register.php" style="color: var(--primary); font-weight: 500; text-decoration: none;">Inscrivez-vous</a>
        </p>
    </form>
</div>

<?php include('footer.php'); ?>
