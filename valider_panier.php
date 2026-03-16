<?php
include('db.php');
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id']) || empty($_SESSION['panier'])) {
    header("Location: boutique.php");
    exit();
}

$user_id = $_SESSION['user_id'];

foreach ($_SESSION['panier'] as $id_p => $qte) {
    $res = $conn->query("SELECT nom, prix FROM produits WHERE id = $id_p");
    $p = $res->fetch_assoc();
    
    for ($i = 0; $i < $qte; $i++) {
        $stmt = $conn->prepare("INSERT INTO commandes (produit, prix, user_id, statut) VALUES (?, ?, ?, 'en attente')");
        $stmt->bind_param("sdi", $p['nom'], $p['prix'], $user_id);
        $stmt->execute();
    }
    // Mise à jour du stock
    $conn->query("UPDATE produits SET stock = stock - $qte WHERE id = $id_p");
}

unset($_SESSION['panier']);
header("Location: mes_commandes.php");
exit();
?>
