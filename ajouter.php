<?php
// Connexion à la base "ma_boutique" que tu viens de créer
$conn = new mysqli("localhost", "root", "", "ma_boutique");

// On récupère les infos tapées dans le formulaire
$pseudo = $_POST['pseudo'];
$email = $_POST['email'];

// La commande SQL pour insérer les données
$sql = "INSERT INTO utilisateurs (pseudo, email) VALUES ('$pseudo', '$email')";

if ($conn->query($sql) === TRUE) {
    echo "Succès ! $pseudo a été ajouté à la base de données.";
    echo "<br><a href='formulaire.html'>Retour au formulaire</a>";
} else {
    echo "Erreur : " . $conn->error;
}

$conn->close();
?>
