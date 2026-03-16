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
        $message = "<p style='color:red;'>Ce pseudo est déjà utilisé.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO utilisateurs (pseudo, email, password, role) VALUES (?, ?, ?, 'client')");
        $stmt->bind_param("sss", $pseudo, $email, $mdp_hache);
        
        if ($stmt->execute()) {
            $message = "<p style='color:green;'>Compte créé ! <a href='login.php'>Connectez-vous ici</a></p>";
        }
    }
}
?>

<div style="display:flex; justify-content:center; padding-top: 50px;">
    <form method="POST" style="width:100%; max-width:400px;">
        <h2 style="text-align:center;">Inscription</h2>
        
        <?php echo $message; ?>

        <label>Pseudo</label>
        <input type="text" name="pseudo" placeholder="Choisir un pseudo" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="votre@email.com" required>

        <label>Mot de passe</label>
        <input type="password" name="mdp" placeholder="Créer un mot de passe" required>

        <button type="submit" name="inscription" class="btn" style="width:100%; margin-top:10px;">S'inscrire</button>
        
        <p style="text-align:center; margin-top:20px;">Déjà inscrit ? <a href="login.php">Se connecter</a></p>
    </form>
</div>

<?php include('footer.php'); ?>
