<?php
include('db.php');
include('header.php');

if (isset($_POST['connexion'])) {
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE pseudo = ?");
    $stmt->bind_param("s", $_POST['pseudo']);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($user = $res->fetch_assoc()) {
        if (password_verify($_POST['mdp'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['pseudo'] = $user['pseudo'];
            $_SESSION['role'] = $user['role'];
            header("Location: " . ($user['role'] === 'admin' ? "admin_dashboard.php" : "boutique.php"));
            exit();
        }
    }
    $err = "Identifiants incorrects.";
}
?>
<form method="POST" style="margin: 50px auto;">
    <h2>Connexion</h2>
    <?php if(isset($err)) echo "<p style='color:red;'>$err</p>"; ?>
    <input type="text" name="pseudo" placeholder="Pseudo" required>
    <input type="password" name="mdp" placeholder="Mot de passe" required>
    <button type="submit" name="connexion" class="btn" style="width:100%;">Se connecter</button>
</form>
<?php include('footer.php'); ?>
