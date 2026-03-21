<?php
// 1. TRAITEMENT PHP (AVANT TOUT LE RESTE)
require_once('db.php');

if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Sécurité : Seul l'admin peut accéder à cette page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// 2. INCLUSION DU HEADER (Contient le CSS, le Dark Mode et le sélecteur de langue)
include('header.php');

// Message de succès si on revient de modifier.php
$success_msg = "";
if (isset($_GET['success'])) {
    $success_msg = "<div style='background: rgba(16, 185, 129, 0.15); color: #10b981; padding: 15px; border-radius: 8px; margin-bottom: 25px; border: 1px solid rgba(16, 185, 129, 0.3); font-weight: 500;'>✅ Le produit a été mis à jour avec succès !</div>";
}
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px; flex-wrap: wrap; gap: 20px;">
        <h1 style="font-size: 1.8rem; font-weight: 700;">Gestion de l'Inventaire</h1>
        <a href="ajouter.php" class="btn" style="background: var(--success); padding: 12px 20px;">+ Nouveau Produit</a>
    </div>

    <?php echo $success_msg; ?>

    <div style="background: var(--card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: background-color 0.3s ease, border-color 0.3s ease;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 600px;">
                <thead>
                    <tr style="background: var(--lang-hover-bg); border-bottom: 2px solid var(--border);">
                        <th style="padding: 18px 15px; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Produit</th>
                        <th style="padding: 18px 15px; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Prix Unit.</th>
                        <th style="padding: 18px 15px; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Disponibilité</th>
                        <th style="padding: 18px 15px; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = $conn->query("SELECT * FROM produits ORDER BY id DESC");
                    while ($p = $res->fetch_assoc()):
                    ?>
                    <tr style="border-bottom: 1px solid var(--border); transition: background 0.2s;" onmouseover="this.style.background='var(--lang-hover-bg)'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <img src="images/<?php echo htmlspecialchars($p['image']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; background: white;" onerror="this.src='images/default.jpg'">
                                <div>
                                    <div style="font-weight: 600; color: var(--text); font-size: 0.95rem;"><?php echo htmlspecialchars($p['nom']); ?></div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?php echo htmlspecialchars($p['description']); ?>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td style="padding: 15px; font-weight: 700; color: var(--text);">
                            <?php echo number_format($p['prix'], 0, '.', ' '); ?> <span style="font-size: 0.8rem; color: var(--text-muted);">Ar</span>
                        </td>

                        <td style="padding: 15px;">
                            <?php if($p['stock'] > 0): ?>
                                <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; background: rgba(16, 185, 129, 0.15); color: #10b981; font-size: 0.8rem; font-weight: 600;">
                                    <?php echo $p['stock']; ?> en stock
                                </span>
                            <?php else: ?>
                                <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; background: rgba(239, 68, 68, 0.15); color: #ef4444; font-size: 0.8rem; font-weight: 600;">
                                    Rupture
                                </span>
                            <?php endif; ?>
                        </td>

                        <td style="padding: 15px; text-align: center;">
                            <a href="modifier.php?id=<?php echo $p['id']; ?>" class="btn" style="padding: 8px 16px; font-size: 0.85rem; background: var(--primary); display: inline-flex; align-items: center; gap: 5px;">
                                <span>✏️</span> Modifier
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
