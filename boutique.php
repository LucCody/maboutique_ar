<?php 
include('db.php'); 
include('header.php'); 
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
?>
<h1>Nos Produits</h1>
<div class="product-grid">
    <?php
    $res = $conn->query("SELECT * FROM produits ORDER BY id DESC");
    while($p = $res->fetch_assoc()) {
        $img = !empty($p['image']) ? "images/".$p['image'] : "images/default.jpg";
        echo "<div class='card'>";
    echo "<img src='$img' alt='Produit'>";
    echo "<div class='card-content'>";
        echo "<h3>" . htmlspecialchars($p['nom']) . "</h3>";
        echo "<p style='color:#94a3b8; font-size:0.9rem;'>" . htmlspecialchars($p['description']) . "</p>";
        echo "<div class='price'>" . number_format($p['prix'], 0, '.', ' ') . " Ar</div>";
        if ($p['stock'] > 0) {
            echo "<a href='ajouter_panier.php?id=".$p['id']."' class='btn'>Ajouter au panier</a>";
        } else {
            echo "<button class='btn' style='background:#334155; cursor:not-allowed;' disabled>Sold Out</button>";
        }
    echo "</div>";
echo "</div>";
    }
    ?>
</div>
<?php include('footer.php'); ?>
