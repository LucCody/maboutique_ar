<?php
    
include('db.php');
include('header.php');

session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Membres</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">👥 Communauté</div>
        <nav>
            <a href="boutique.php">Boutique</a>
            <?php if($_SESSION['role'] === 'admin'): ?>
                <a href="admin_dashboard.php" style="color:#f1c40f; font-weight:bold;">📊 Dashboard Admin</a>
            <?php endif; ?>
            <a href="logout.php" class="btn-red">Quitter</a>
        </nav>
    </header>

    <div class="container">
        <h1>Liste des membres</h1>
        <table>
            <tr><th>Pseudo</th><th>Email</th><th>Rôle</th></tr>
            <?php
            $res = $conn->query("SELECT pseudo, email, role FROM utilisateurs");
            while($u = $res->fetch_assoc()) {
                echo "<tr><td>".$u['pseudo']."</td><td>".$u['email']."</td><td>".$u['role']."</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
