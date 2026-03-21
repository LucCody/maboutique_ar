<?php
// 1. TRAITEMENT PHP
require_once('db.php');

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Sécurité : Vérifier si l'utilisateur est admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// 2. INCLUSION DU DESIGN
include('header.php');

// Petit message si on revient d'une modification réussie
$success_msg = "";
if (isset($_GET['success'])) {
    $success_msg = "<div style='background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #10b981;'>✅ Produit mis à jour avec succès !</div>";
}
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>Gestion des Produits</h1>
        <a href="ajouter_produit.php" class="btn" style="background: var(--success);">+ Ajouter un produit</a>
    </div>

    <?php echo $success_msg; ?>

    <div style="background: var(--card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: var(--lang-hover-bg); border-bottom: 2px solid var(--border);">
                    <th style="padding: 15px; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase;">Image</th>
                    <th style="padding: 15px; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase;">Nom</th>
                    <th style="padding: 15px; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase;">Prix</th>
                    <th style="padding: 15px; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase;">Stock</th>
                    <th style="padding: 15px; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = $conn->query("SELECT * FROM produits ORDER BY id DESC");
                while ($p = $res->fetch_assoc()):
                ?>
                <tr style="border-bottom: 1px solid var(--border); transition: background 0.2s;" onmouseover="this.style.background='var(--lang-hover-bg)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 15px;">
                        <img src="images/<?php echo htmlspecialchars($p['image']); ?>" style="width: 45px; height: 45px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);" onerror="this.src='images/default.jpg'">
                    </td>
                    <td style="padding: 15px;">
                        <div style="font-weight: 600; color: var(--text);"><?php echo htmlspecialchars($p['nom']); ?></div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?php echo htmlspecialchars($p['description']); ?>
                        </div>
                    </td>
                    <td style="padding: 15px; font-weight: 700; color: var(--primary);">
                        <?php echo number_format($p['prix'], 0, '.', ' '); ?> Ar
                    </td>
                    <td style="padding: 15px;">
                        <span style="padding: 4px 8px; border-radius: 6px; background: <?php echo ($p['stock'] > 0) ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)'; ?>; color: <?php echo ($p['stock'] > 0) ? '#10b981' : '#ef4444'; ?>; font-size: 0.85rem; font-weight: 600;">
                            <?php echo $p['stock']; ?> en stock
                        </span>
                    </td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="modifier_produit.php?id=<?php echo $p['id']; ?>" class="btn" style="padding: 6px 12px; font-size: 0.8rem; background: var(--primary);">
                            ✏️ Modifier
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>
