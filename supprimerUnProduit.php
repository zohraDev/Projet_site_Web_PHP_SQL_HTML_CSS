
<?php
/**----------------    suppression d'un produit par nProduit      ----------------**/
require_once("mutualisation/connexion.php");
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

$produitSupprimer = $_GET['nProduit'];
$sql = "DELETE FROM Produit WHERE nProduit='$produitSupprimer'";


// Exécution de la requête de suppression
$resultat = $dbh->query($sql);

echo "Le produit" . $produitSupprimer . "a bien été  supprimé.";

$dbh = null;
?>