<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$conn = new mysqli("localhost", "root", "", "ma_boutique");

// 1. On récupère l'ID du produit depuis l'URL (ex: id_produit=2)
if (isset($_GET['id_produit'])) {
    $id_p = $_GET['id_produit'];
    $user_id = $_SESSION['user_id'];

    // 2. On va chercher le NOM et le PRIX RÉEL du produit dans le catalogue
    $stmt_prix = $conn->prepare("SELECT nom, prix FROM produits WHERE id = ?");
    $stmt_prix->bind_param("i", $id_p);
    $stmt_prix->execute();
    $res = $stmt_prix->get_result();
    $produit_info = $res->fetch_assoc();

    if ($produit_info) {
        $nom_produit = $produit_info['nom'];
        $prix_reel = $produit_info['prix'];

        // 3. On ENREGISTRE l'achat dans la table 'commandes'
        $stmt_achat = $conn->prepare("INSERT INTO commandes (produit, prix, user_id) VALUES (?, ?, ?)");
        $stmt_achat->bind_param("sdi", $nom_produit, $prix_reel, $user_id);

        if ($stmt_achat->execute()) {
            echo "<h2 style='color:green;'>Achat validé !</h2>";
            echo "<p>Félicitations <strong>" . $_SESSION['pseudo'] . "</strong>, vous avez acheté : <strong>$nom_produit</strong></p>";
            echo "<p>Montant débité : <strong>" . number_format($prix_reel, 0, '.', ' ') . " Ar</strong></p>";
            echo "<hr>";
            echo "<a href='boutique.php'>Retour au catalogue</a> | <a href='mes_commandes.php'>Voir mes achats</a>";
        }
    } else {
        echo "Produit introuvable.";
    }
} else {
    header("Location: boutique.php");
}

$conn->close();
?>
