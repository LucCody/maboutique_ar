<?php
// 1. TRAITEMENT PHP (AVANT TOUT LE RESTE)
require_once('db.php');

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Sécurité : Seul l'admin accède ici
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: login.php"); 
    exit(); 
}

// Récupération des infos du produit pour remplir le formulaire
if (isset($_GET['id'])) {
    $stmt_get = $conn->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt_get->bind_param("i", $_GET['id']);
    $stmt_get->execute();
    $p = $stmt_get->get_result()->fetch_assoc();
}

// LOGIQUE DE MISE À JOUR
if (isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $desc = $_POST['desc'];
    $prix = $_POST['prix'];
    $stock = $_POST['stock'];

    if (!empty($_FILES['photo']['name'])) {
        // Si une nouvelle photo est téléchargée
        move_uploaded_file($_FILES['photo']['tmp_name'], "images/" . $_FILES['photo']['name']);
        $stmt = $conn->prepare("UPDATE produits SET nom=?, description=?, prix=?, stock=?, image=? WHERE id=?");
        $stmt->bind_param("ssdisi", $nom, $desc, $prix, $stock, $_FILES['photo']['name'], $id);
    } else {
        // Si on garde l'ancienne photo
        $stmt = $conn->prepare("UPDATE produits SET nom=?, description=?, prix=?, stock=? WHERE id=?");
        $stmt->bind_param("ssdii", $nom, $desc, $prix, $stock, $id);
    }
    
    if($stmt->execute()) {
        header("Location: admin_dashboard.php?success=1"); // On redirige vers le dashboard
        exit();
    }
}

// 2. INCLUSION DU HEADER (Contient le CSS et le Dark Mode)
include('header.php');
?>

<div class="container auth-wrapper">
    <form method="POST" enctype="multipart/form-data">
        <h2 style="font-size: 1.2rem;">Modifier : <span style="color:var(--primary);"><?php echo htmlspecialchars($p['nom']); ?></span></h2>
        
        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">

        <label>Nom du produit</label>
        <input type="text" name="nom" value="<?php echo htmlspecialchars($p['nom']); ?>" required>

        <label>Description (Apostrophes autorisées ✅)</label>
        <textarea name="desc" rows="6" style="width:100%; padding:10px; border-radius:6px; border:1px solid var(--border); background:var(--input-bg); color:var(--text); font-family:inherit; margin-bottom:20px;"><?php echo htmlspecialchars($p['description']); ?></textarea>

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

        <label>Changer l'image (optionnel)</label>
        <div style="margin-bottom: 20px; font-size: 0.8rem; color: var(--text-muted);">
            Image actuelle : <?php echo $p['image']; ?>
        </div>
        <input type="file" name="photo">

        <button type="submit" name="modifier" class="btn" style="width:100%; margin-top: 10px;">💾 Enregistrer les modifications</button>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="admin_dashboard.php" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">❌ Annuler et retourner</a>
        </p>
    </form>
</div>

<?php include('footer.php'); ?>
