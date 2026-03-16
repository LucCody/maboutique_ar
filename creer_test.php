<?php
$conn = new mysqli("localhost", "root", "", "ma_boutique");

$pseudo = "admin";
$pass_clair = "1234"; // Le mot de passe que tu taperas dans le formulaire
$pass_hache = password_hash($pass_clair, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO utilisateurs (pseudo, email, password) VALUES (?, ?, ?)");
$email = "admin@test.com";
$stmt->bind_param("sss", $pseudo, $email, $pass_hache);

if ($stmt->execute()) {
    echo "Utilisateur de test créé ! Pseudo: admin | MDP: 1234";
}
?>
