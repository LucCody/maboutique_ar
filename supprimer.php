<?php
include('db.php');

// Ajout d'une vérification de session/admin recommandée ici
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    die("Accès refusé."); 
}

if (isset($_GET['id'])) {
    $id_a_supprimer = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $stmt->bind_param("i", $id_a_supprimer);

    if ($stmt->execute()) {
        header("Location: liste.php");
    } else {
        echo "Erreur : " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
