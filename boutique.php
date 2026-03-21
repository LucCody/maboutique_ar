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
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom: 40px; border-bottom: 1px solid var(--border); padding-bottom: 20px;">
        <div>
            <h1 style="margin:0; font-size: 2rem; color: var(--text); letter-spacing: -0.5px;">Catalogue Premium</h1>
            <p style="color:var(--text-muted); margin: 5px 0 0 0; font-size: 0.95rem;">Les meilleurs produits digitaux au meilleur prix.</p>
        </div>
        <div style="text-align: right;">
            <p style="margin:0; font-size: 0.9rem; color: var(--text-muted);">
                Bienvenue, <strong style="color:var(--text);"><?php echo htmlspecialchars($_SESSION['pseudo']); ?></strong>
            </p>
            <a href="panier.php" style="display: inline-block; margin-top: 8px; font-size: 0.85rem; background: #eff6ff; color: var(--primary); padding: 6px 12px; border-radius: 6px; text-decoration: none; font-weight: 500; border: 1px solid #bfdbfe; transition: 0.2s;">
                🛒 <?php echo $nb_articles; ?> articles au panier
            </a>
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
                        
                        <div class="price"><?php echo $prix; ?> <small style="font-size: 0.8rem; color: var(--text-muted); font-weight: 400;">Ar</small></div>
                        
                        <div style="margin-top: auto;">
                            <?php if ($stock > 0): ?>
                                <div style="color: var(--success); font-size: 0.85rem; font-weight: 500; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                                    <span style="display: inline-block; width: 8px; height: 8px; background: var(--success); border-radius: 50%;"></span>
                                    En stock (<?php echo $stock; ?>)
                                </div>
                                <a href="ajouter_panier.php?id=<?php echo $p['id']; ?>" class="btn" style="width: 100%;">Ajouter au panier</a>
                            <?php else: ?>
                                <div style="color: var(--danger); font-size: 0.85rem; font-weight: 500; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                                    <span style="display: inline-block; width: 8px; height: 8px; background: var(--danger); border-radius: 50%;"></span>
                                    Rupture de stock
                                </div>
                                <button class="btn" style="width: 100%; background: #e5e7eb; color: #9ca3af; cursor: not-allowed; box-shadow: none;" disabled>Épuisé</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php
            }
        } else {
            ?>
            <div style="grid-column: 1/-1; text-align:center; padding: 60px 20px; background: #ffffff; border-radius: 12px; border: 1px dashed var(--border);">
                <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 15px;">Aucun produit n'est disponible pour le moment.</p>
                <a href="index.php" class="btn btn-outline">Retour à l'accueil</a>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<?php 
include('footer.php'); 
?>
