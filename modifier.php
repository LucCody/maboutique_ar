<?php
// 1. TRAITEMENT PHP (AVANT TOUT)
require_once('db.php');

if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

// Sécurité Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: login.php"); 
    exit(); 
}

// Récupération du produit via l'ID dans l'URL
$p = null;
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt_get = $conn->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $p = $stmt_get->get_result()->fetch_assoc();
}

// Si le produit n'existe pas, on retourne au dashboard
if (!$p) {
    header("Location: admin_dashboard.php");
    exit();
}

// LOGIQUE DE MISE À JOUR (Quand on clique sur Enregistrer)
if (isset($_POST['modifier'])) {
    $id_post = (int)$_POST['id'];
    $nom = $_POST['nom'];
    $desc = $_POST['desc'];
    $prix = $_POST['prix'];
    $stock = $_POST['stock'];

    if (!empty($_FILES['photo']['name'])) {
        // Avec changement d'image
        move_uploaded_file($_FILES['photo']['tmp_name'], "images/" . $_FILES['photo']['name']);
        $stmt = $conn->prepare("UPDATE produits SET nom=?, description=?, prix=?, stock=?, image=? WHERE id=?");
        $stmt->bind_param("ssdisi", $nom, $desc, $prix, $stock, $_FILES['photo']['name'], $id_post);
    } else {
        // Sans changement d'image
        $stmt = $conn->prepare("UPDATE produits SET nom=?, description=?, prix=?, stock=? WHERE id=?");
        $stmt->bind_param("ssdii", $nom, $desc, $prix, $stock, $id_post);
    }
    
    if($stmt->execute()) {
        header("Location: admin_dashboard.php?success=1");
        exit();
    }
}

// 2. INCLUSION DU HEADER (Gère le CSS et le Thème)
// NE RIEN METTRE (PAS DE HTML) AVANT CETTE LIGNE
include('header.php');
?>

<div class="container auth-wrapper">
    <form method="POST" enctype="multipart/form-data">
        <h2 style="text-align: left; margin-bottom: 20px;">Modifier : <span style="color:var(--primary);"><?php echo htmlspecialchars($p['nom']); ?></span></h2>
        
        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">

        <label>Nom du produit</label>
        <input type="text" name="nom" value="<?php echo htmlspecialchars($p['nom']); ?>" required>

        <label>Description (Accents et apostrophes OK ✅)</label>
        <textarea name="desc" rows="8" style="width:100%; padding:12px; border-radius:8px; border:1px solid var(--border); background:var(--input-bg); color:var(--text); font-family:inherit; margin-bottom:20px; resize: vertical;"><?php echo htmlspecialchars($p['description']); ?></textarea>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div>
                <label>Prix (Ar)</label>
                <input type="number" name="prix" value="<?php echo $p['prix']; ?>" required>
            </div>
            <div>
                <label>Stock</label>
                <input type="number" name="stock" value="<?php echo $p['stock']; ?>" required>
            </div>
        </div>

        <label>Image du produit</label>
        <div style="margin-bottom: 10px; font-size: 0.85rem; color: var(--text-muted);">
            Fichier actuel : <strong><?php echo htmlspecialchars($p['image']); ?></strong>
        </div>
        <input type="file" name="photo" style="border: none; padding: 0;">

        <button type="submit" name="modifier" class="btn" style="width:100%; margin-top: 20px; padding: 12px;">💾 Enregistrer les modifications</button>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="admin_dashboard.php" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">← Retour sans enregistrer</a>
        </p>
    </form>
</div>

<?php include('footer.php'); ?>
