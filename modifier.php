<?php
 
include('db.php');
include('header.php');

session_start();
if ($_SESSION['role'] !== 'admin') { header("Location: login.php"); exit(); }

if (isset($_GET['id'])) {
    $res = $conn->query("SELECT * FROM produits WHERE id = " . $_GET['id']);
    $p = $res->fetch_assoc();
}

if (isset($_POST['modifier'])) {
    if (!empty($_FILES['photo']['name'])) {
        move_uploaded_file($_FILES['photo']['tmp_name'], "images/" . $_FILES['photo']['name']);
        $stmt = $conn->prepare("UPDATE produits SET nom=?, description=?, prix=?, stock=?, image=? WHERE id=?");
        $stmt->bind_param("ssdisi", $_POST['nom'], $_POST['desc'], $_POST['prix'], $_POST['stock'], $_FILES['photo']['name'], $_POST['id']);
    } else {
        $stmt = $conn->prepare("UPDATE produits SET nom=?, description=?, prix=?, stock=? WHERE id=?");
        $stmt->bind_param("ssdi i", $_POST['nom'], $_POST['desc'], $_POST['prix'], $_POST['stock'], $_POST['id']);
    }
    $stmt->execute();
    header("Location: admin_produits.php");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Produit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">🛠 Panel Admin</div>
        <nav><a href="admin_dashboard.php">📊 Dashboard</a><a href="admin_produits.php">📦 Retour</a></nav>
    </header>

    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <h2>Modifier : <?php echo $p['nom']; ?></h2>
            <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
            <input type="text" name="nom" value="<?php echo $p['nom']; ?>" required>
            <textarea name="desc"><?php echo $p['description']; ?></textarea>
            <input type="number" name="prix" value="<?php echo $p['prix']; ?>" required>
            <input type="number" name="stock" value="<?php echo $p['stock']; ?>" required>
            <input type="file" name="photo">
            <button type="submit" name="modifier" class="btn" style="width:100%;">Enregistrer</button>
        </form>
    </div>
</body>
</html>
