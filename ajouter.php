<?php
include('db.php');

$pseudo = $_POST['pseudo'];
$email = $_POST['email'];

// Remplacement par une requête préparée
$stmt = $conn->prepare("INSERT INTO utilisateurs (pseudo, email) VALUES (?, ?)");
$stmt->bind_param("ss", $pseudo, $email);

if ($stmt->execute()) {
    echo "Succès ! " . htmlspecialchars($pseudo) . " a été ajouté à la base de données.";
    echo "<br><a href='formulaire.html'>Retour au formulaire</a>";
} else {
    echo "Erreur : " . $conn->error;
}

$stmt->close();
$conn->close();
?>
