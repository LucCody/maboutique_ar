<?php
$conn = new mysqli("localhost", "root", "", "ma_boutique");

if (isset($_GET['id'])) {
    $id_a_supprimer = $_GET['id'];

    // 1. On prépare la "structure" avec un point d'interrogation (?) à la place de la valeur
    $stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id = ?");

    // 2. On "lie" l'ID au point d'interrogation (le "i" veut dire que c'est un Integer / nombre entier)
    $stmt->bind_param("i", $id_a_supprimer);

    // 3. On exécute la commande
    if ($stmt->execute()) {
        header("Location: liste.php");
    } else {
        echo "Erreur : " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
