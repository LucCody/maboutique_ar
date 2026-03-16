<?php
session_start();

// Sécurité : Si l'utilisateur n'est pas connecté, retour au login
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Connexion à "ma_boutique"
$conn = new mysqli("localhost", "root", "", "ma_boutique");

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if (isset($_POST['commander'])) {
    $produit = $_POST['produit'];
    $prix = $_POST['prix'];
    $user_id = $_SESSION['user_id']; // On récupère l'ID de la personne connectée

    // On prépare l'insertion dans la table "commandes"
    $stmt = $conn->prepare("INSERT INTO commandes (produit, prix, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $produit, $prix, $user_id); // s=string, d=decimal, i=integer

    if ($stmt->execute()) {
        echo "<h3 style='color:green'>Commande réussie !</h3>";
        echo "<p>Produit : <strong>$produit</strong> | Prix : <strong>$prix €</strong></p>";
        echo "<a href='liste.php'>Retour à la liste</a> | <a href='mes_commandes.php'>Voir toutes les commandes</a>";
    } else {
        echo "Erreur lors de l'achat : " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Passer une commande</title>
</head>
<body>
    <h2>Passer une commande</h2>
    <p>Connecté en tant que : <strong><?php echo $_SESSION['pseudo']; ?></strong> (ID: <?php echo $_SESSION['user_id']; ?>)</p>

    <form method="POST">
        <label>Nom du produit :</label><br>
        <input type="text" name="produit" placeholder="Ex: Pizza, Clavier..." required><br><br>
        
        <label>Prix (€) :</label><br>
        <input type="number" step="0.01" name="prix" placeholder="12.50" required><br><br>
        
        <button type="submit" name="commander">Valider l'achat</button>
    </form>
</body>
</html>
