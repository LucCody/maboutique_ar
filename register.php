<?php
include('db.php');
include('header.php');

$message = "";

if (isset($_POST['inscription'])) {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $mdp_hache = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

    // On vérifie si le pseudo existe déjà
    $verif = $conn->prepare("SELECT id FROM utilisateurs WHERE pseudo = ?");
    $verif->bind_param("s", $pseudo);
    $verif->execute();
    
    if ($verif->get_result()->num_rows > 0) {
        $message = "<div style='background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #f87171;'>Ce pseudo est déjà utilisé.</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO utilisateurs (pseudo, email, password, role) VALUES (?, ?, ?, 'client')");
        $stmt->bind_param("sss", $pseudo, $email, $mdp_hache);
        
        if ($stmt->execute()) {
            $message = "<div style='background: #d1fae5; color: #065f46; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; border: 1px solid #34d399;'>Compte créé ! <a href='login.php' style='color:#065f46; font-weight:bold; text-decoration:underline;'>Connectez-vous ici</a></div>";
        }
    }
}
?>

<div class="container auth-wrapper">
    <form method="POST">
        <h2>Inscription</h2>
        
        <?php echo $message; ?>

        <label for="pseudo">Pseudo</label>
        <input type="text" id="pseudo" name="pseudo" placeholder="Choisir un pseudo" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="votre@email.com" required>

        <label for="mdp">Mot de passe</label>
        <input type="password" id="mdp" name="mdp" placeholder="Créer un mot de passe" required>

        <button type="submit" name="inscription" class="btn" style="width:100%; margin-top:10px;">S'inscrire</button>
        
        <p style="text-align:center; margin-top:24px; font-size: 0.9rem; color: var(--text-muted);">
            Déjà inscrit ? <a href="login.php" style="color: var(--primary); font-weight: 500; text-decoration: none;">Se connecter</a>
        </p>
    </form>
</div>

<?php include('footer.php'); ?>
