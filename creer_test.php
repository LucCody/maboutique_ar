<?php
include('db.php');

$pseudo = "admin";
$pass_clair = "1234"; // Le mot de passe que tu taperas dans le formulaire
$pass_hache = password_hash($pass_clair, PASSWORD_DEFAULT);
$email = "admin@test.com";

$stmt = $conn->prepare("INSERT INTO utilisateurs (pseudo, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $pseudo, $email, $pass_hache);

if ($stmt->execute()) {
    echo "Utilisateur de test créé ! Pseudo: admin | MDP: 1234";
} else {
    echo "Erreur ou utilisateur déjà existant.";
}
?>
