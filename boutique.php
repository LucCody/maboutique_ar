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
            echo "<img src='$img' style='width:100%; height:180px; object-fit:cover; border-radius:8px 8px 0 0;'>";
            echo "<h3>" . htmlspecialchars($p['nom']) . "</h3>";
            echo "<span class='price'>" . number_format($p['prix'], 0, '.', ' ') . " Ar</span>";
            if ($p['stock'] > 0) {
                echo "<a href='ajouter_panier.php?id=".$p['id']."' class='btn' style='width:100%; box-sizing:border-box;'>🛒 Ajouter au panier</a>";
            } else {
                echo "<button class='btn' style='background:gray; width:100%;' disabled>Indisponible</button>";
            }
        echo "</div>";
    }
    ?>
</div>
<?php include('footer.php'); ?>
