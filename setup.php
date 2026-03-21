<?php
// On affiche toutes les erreurs pour voir ce qui se passe
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('db.php');

echo "Connexion à la base de données réussie !<br><br>";

$email = "admin@expertmodel.com"; 
$pseudo = "Admin";
$mdp = password_hash("Admin123!", PASSWORD_DEFAULT);

// 1. On vérifie si la table existe et on s'assure qu'elle a les bonnes colonnes
$check_table = $conn->query("SHOW TABLES LIKE 'utilisateurs'");
if ($check_table->num_rows == 0) {
    die("<b style='color:red;'>Erreur : La table 'utilisateurs' n'existe pas dans ta base de données ! Tu dois d'abord importer ton fichier SQL dans phpMyAdmin.</b>");
}

// 2. On vérifie si ce compte existe déjà
$stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    // Le compte existe, on le met à jour
    $update = $conn->prepare("UPDATE utilisateurs SET role = 'admin', password = ? WHERE email = ?");
    $update->bind_param("ss", $mdp, $email);
    if ($update->execute()) {
        echo "<h3 style='color:green;'>✅ Compte existant mis à jour ! Tu es maintenant Administrateur.</h3>";
    } else {
        echo "<b style='color:red;'>Erreur lors de la mise à jour : " . $conn->error . "</b>";
    }
} else {
    // On crée le compte admin
    $insert = $conn->prepare("INSERT INTO utilisateurs (pseudo, email, password, role) VALUES (?, ?, ?, 'admin')");
    $insert->bind_param("sss", $pseudo, $email, $mdp);
    if ($insert->execute()) {
        echo "<h3 style='color:green;'>✅ Nouveau compte administrateur créé avec succès !</h3>";
    } else {
         echo "<b style='color:red;'>Erreur lors de la création : " . $conn->error . "</b>";
    }
}

echo "<br><br><a href='login.php' style='padding: 10px 20px; background: blue; color: white; text-decoration: none; border-radius: 5px;'>Aller à la page de connexion</a>";
?>
