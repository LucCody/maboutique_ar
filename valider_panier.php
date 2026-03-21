<?php
include('db.php');
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id']) || empty($_SESSION['panier'])) {
    header("Location: boutique.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Start a database transaction
    $conn->begin_transaction();

    // 1. Calculate total cost of the cart
    $total_cost = 0;
    $items_to_process = [];

    foreach ($_SESSION['panier'] as $id_p => $qte) {
        $stmt = $conn->prepare("SELECT nom, prix, stock FROM produits WHERE id = ? FOR UPDATE");
        $stmt->bind_param("i", $id_p);
        $stmt->execute();
        $res = $stmt->get_result();
        $p = $res->fetch_assoc();

        if (!$p) throw new Exception("Produit introuvable.");
        if ($p['stock'] < $qte) throw new Exception("Stock insuffisant pour " . $p['nom']);

        $total_cost += ($p['prix'] * $qte);
        $items_to_process[] = ['id' => $id_p, 'nom' => $p['nom'], 'prix' => $p['prix'], 'qte' => $qte];
    }

    // 2. Check user's balance (solde)
    $stmt_user = $conn->prepare("SELECT solde FROM utilisateurs WHERE id = ? FOR UPDATE");
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $user = $stmt_user->get_result()->fetch_assoc();

    if ($user['solde'] < $total_cost) {
        throw new Exception("Solde insuffisant pour cette commande.");
    }

    // 3. Deduct balance from user
    $new_solde = $user['solde'] - $total_cost;
    $stmt_update_solde = $conn->prepare("UPDATE utilisateurs SET solde = ? WHERE id = ?");
    $stmt_update_solde->bind_param("di", $new_solde, $user_id);
    $stmt_update_solde->execute();

    // 4. Process orders and update stock
    foreach ($items_to_process as $item) {
        for ($i = 0; $i < $item['qte']; $i++) {
            $stmt_cmd = $conn->prepare("INSERT INTO commandes (produit, prix, user_id, statut) VALUES (?, ?, ?, 'en attente')");
            $stmt_cmd->bind_param("sdi", $item['nom'], $item['prix'], $user_id);
            $stmt_cmd->execute();
        }
        
        $stmt_stock = $conn->prepare("UPDATE produits SET stock = stock - ? WHERE id = ?");
        $stmt_stock->bind_param("ii", $item['qte'], $item['id']);
        $stmt_stock->execute();
    }

    // Commit transaction
    $conn->commit();
    unset($_SESSION['panier']);
    header("Location: mes_commandes.php?success=1");
    exit();

} catch (Exception $e) {
    // If anything fails, rollback the database to its previous state
    $conn->rollback();
    die("Erreur lors de la commande : " . $e->getMessage());
}
?>
