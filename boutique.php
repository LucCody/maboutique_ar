<?php
// 1. Connexion et Header (Inclusion des fichiers refactorisés)
include('db.php');
include('header.php');

// 2. Sécurité : Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Calcul du nombre d'articles pour le petit badge (déjà fait dans header, mais utile ici)
$nb_articles = isset($_SESSION['panier']) ? array_sum($_SESSION['panier']) : 0;
?>

<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
        <h1 style="margin:0;">🚀 Catalogue Premium</h1>
        <p style="color:#94a3b8;">Bienvenue, <strong><?php echo $_SESSION['pseudo']; ?></strong></p>
    </div>

    <div class="product-grid">
        <?php
        // 3. Récupération des produits depuis la base de données
        $res = $conn->query("SELECT * FROM produits ORDER BY id DESC");

        if ($res->num_rows > 0) {
            while($p = $res->fetch_assoc()) {
                // Gestion de l'image par défaut si vide
                $img_path = !empty($p) ? "images/".$p : "images/default.jpg";
                $stock = $p['stock'];
                
                echo "<div class='card'>";
                    // Image du produit
                    echo "<img src='$img_path' alt='Photo produit'>";
                    
                    echo "<div class='card-content'>";
                        echo "<h3 style='margin:0 0 10px 0;'>" . htmlspecialchars($p['nom']) . "</h3>";
                        echo "<p style='color:#94a3b8; font-size:0.85rem; height:40px; overflow:hidden;'>" . htmlspecialchars($p['description']) . "</p>";
                        
                        // Affichage du prix en Ariary (Orange)
                        echo "<div class='price'>" . number_format($p['prix'], 0, '.', ' ') . " Ar</div>";
                        
                        // 4. Gestion du Stock et Boutons
                        if ($stock > 0) {
                            echo "<p style='color:#27ae60; font-size:0.8rem; margin-bottom:15px;'>✅ En stock : <strong>$stock</strong></p>";
                            echo "<a href='ajouter_panier.php?id=" . $p['id'] . "' class='btn'>🛒 Ajouter au panier</a>";
                        } else {
                            echo "<p style='color:#e74c3c; font-size:0.8rem; margin-bottom:15px;'>❌ Rupture de stock</p>";
                            echo "<button class='btn' style='background:#334155; cursor:not-allowed; opacity:0.6;' disabled>Épuisé</button>";
                        }
                    echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div style='grid-column: 1/-1; text-align:center; padding:50px; background:rgba(255,255,255,0.05); border-radius:15px;'>";
            echo "<p>Aucun produit disponible pour le moment. Revenez bientôt !</p>";
            echo "</div>";
        }
        ?>
    </div>
</div>

<?php 
// 5. Bas de page
include('footer.php'); 
?>
