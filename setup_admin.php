<?php
include('db.php');

$email = "admin@expertmodel.com"; // L'email que tu utiliseras pour te connecter
$pseudo = "Admin";
$mdp = password_hash("Admin123!", PASSWORD_DEFAULT); // Ton nouveau mot de passe sécurisé

// On vérifie si ce compte existe déjà
$stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    // Le compte existe, on le force en tant qu'admin et on met à jour le mot de passe
    $update = $conn->prepare("UPDATE utilisateurs SET role = 'admin', password = ? WHERE email = ?");
    $update->bind_param("ss", $mdp, $email);
    $update->execute();
    echo "<h3 style='color:green;'>Compte mis à jour avec succès en tant qu'administrateur !</h3>";
} else {
    // On crée le compte admin de zéro
    $insert = $conn->prepare("INSERT INTO utilisateurs (pseudo, email, password, role) VALUES (?, ?, ?, 'admin')");
    $insert->bind_param("sss", $pseudo, $email, $mdp);
    $insert->execute();
    echo "<h3 style='color:green;'>Compte administrateur créé avec succès !</h3>";
}

echo "<a href='login.php'>Aller à la page de connexion</a>";
?>
