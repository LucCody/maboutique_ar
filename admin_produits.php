<?php 
include('db.php'); 
include('header.php'); 
if ($_SESSION['role'] !== 'admin') { header("Location: login.php"); exit(); }

if (isset($_POST['ajouter'])) {
    $img = $_FILES['photo']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'], "images/".$img);
    $stmt = $conn->prepare("INSERT INTO produits (nom, description, prix, stock, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdis", $_POST['nom'], $_POST['desc'], $_POST['prix'], $_POST['stock'], $img);
    $stmt->execute();
}
?>
<h1>📦 Gestion des Produits</h1>
<form method="POST" enctype="multipart/form-data" style="max-width:500px; margin-bottom:30px;">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="number" name="prix" placeholder="Prix" required>
    <input type="number" name="stock" placeholder="Stock" required>
    <input type="file" name="photo" required>
    <button type="submit" name="ajouter" class="btn">Ajouter</button>
</form>
<table>
    <tr><th>Image</th><th>Nom</th><th>Prix</th><th>Stock</th><th>Action</th></tr>
    <?php
    $res = $conn->query("SELECT * FROM produits ORDER BY id DESC");
    while($p = $res->fetch_assoc()){
        echo "<tr>";
        echo "<td><img src='images/".$p['image']."' style='width:50px;'></td>";
        echo "<td>".$p['nom']."</td>";
        echo "<td>".$p['prix']." Ar</td>";
        echo "<td>".$p['stock']."</td>";
        echo "<td><a href='modifier.php?id=".$p['id']."'>Modifier</a></td>";
        echo "</tr>";
    }
    ?>
</table>
<?php include('footer.php'); ?>
