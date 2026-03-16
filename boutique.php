<?php
// 1. Connexion et Header
include('db.php');
include('header.php');

// 2. Sécurité : Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$nb_articles = isset($_SESSION['panier']) ? array_sum($_SESSION['panier']) : 0;
?>

<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:40px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 20px;">
        <div>
            <h1 style="margin:0; font-size: 2.2rem;">🚀 Catalogue <span style="color:var(--primary);">Premium</span></h1>
            <p style="color:var(--text-muted); margin: 5px 0 0 0;">Les meilleurs produits digitaux au meilleur prix.</p>
        </div>
        <div style="text-align: right;">
            <p style="margin:0; font-size: 0.9rem;">Bienvenue, <strong style="color:var(--primary);"><?php echo htmlspecialchars($_SESSION['pseudo']); ?></strong></p>
            <span style="font-size: 0.8rem; background: rgba(52, 152, 219, 0.1); color: var(--secondary); padding: 2px 8px; border-radius: 4px;">
                🛒 <?php echo $nb_articles; ?> articles au panier
            </span>
        </div>
    </div>

    <div class="product-grid">
        <?php
        // 3. Récupération des produits
        $res = $conn->query("SELECT * FROM produits ORDER BY id DESC");

        if ($res && $res->num_rows > 0) {
            while($p = $res->fetch_assoc()) {
                // Gestion sécurisée des variables
                $img_name = !empty($p['image']) ? $p['image'] : 'default.jpg';
                $stock = (int)$p['stock'];
                $nom = htmlspecialchars($p['nom']);
                $desc = htmlspecialchars($p['description']);
                $prix = number_format($p['prix'], 0, '.', ' ');
                ?>

                <div class="card">
                    <div class="card-img-container">
                        <img src="images/<?php echo $img_name; ?>" alt="<?php echo $nom; ?>" onerror="this.src='images/default.jpg'">
                    </div>
                    
                    <div class="card-content">
                        <h3><?php echo $nom; ?></h3>
                        <p class="description"><?php echo $desc; ?></p>
                        
                        <div class="price"><?php echo $prix; ?> <small style="font-size: 0.8rem; color: var(--text-muted);">Ar</small></div>
                        
                        <?php if ($stock > 0): ?>
                            <div class="status-stock" style="color: var(--success);">
                                <span>●</span> En stock (<?php echo $stock; ?>)
                            </div>
                            <a href="ajouter_panier.php?id=<?php echo $p['id']; ?>" class="btn btn-add">🛒 Ajouter au panier</a>
                        <?php else: ?>
                            <div class="status-stock" style="color: var(--danger);">
                                <span>○</span> Rupture de stock
                            </div>
                            <button class="btn btn-disabled" disabled>Épuisé</button>
                        <?php endif; ?>
                    </div>
                </div>

                <?php
            }
        } else {
            ?>
            <div style="grid-column: 1/-1; text-align:center; padding:80px; background:rgba(255,255,255,0.02); border-radius:20px; border: 1px dashed rgba(255,255,255,0.1);">
                <p style="font-size: 1.2rem; color: var(--text-muted);">Aucun produit n'est disponible pour le moment.</p>
                <a href="index.php" style="color: var(--primary); text-decoration: none;">Retour à l'accueil</a>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<?php 
include('footer.php'); 
?>