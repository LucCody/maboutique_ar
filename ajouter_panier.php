<?php
session_start();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!isset($_SESSION['panier'])) { $_SESSION['panier'] = array(); }
    if (isset($_SESSION['panier'][$id])) { $_SESSION['panier'][$id]++; } 
    else { $_SESSION['panier'][$id] = 1; }
}
header("Location: boutique.php");
exit();
?>
