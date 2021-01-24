<?php

try {

$produit = (string) filter_input(INPUT_GET, 'rechercheProduit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Code HTML contenant la liste des pays trouvés
$liste_produit = "";

// Inclusion des informations de connexion à la base de données
require_once 'mutualisation/connexion.php';

$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



// Requête de recherche des pays
$sql = "SELECT nomProduit, descProduit, prixProduit, equitable FROM Produit WHERE nomProduit LIKE '%$produit%'";

// Résultat de la requête de recherche
$resultat = $dbh->query($sql);

// Parcours de tous les résultats de la requête
// https://www.php.net/manual/fr/pdostatement.fetch.php
while ( ($un_produit = $resultat->fetch(PDO::FETCH_ASSOC)) != FALSE) {
// Traitement de chaque résultat qui est contenu dans la variable $un_pays

$liste_produit.= '<div style="border:solid;width: 20% ">' . '<p>' . $un_produit['nomProduit'] . '</p>'. '<p>' . $un_produit['descProduit'] . '</p>'. '<p>' . $un_produit['prixProduit'] . '€' . '</p>' . '</div>';



}

$dbh = null;

if ($liste_produit == "") {
$liste_produit = '<p>Aucun produit trouvé</p>';
} else {
// Finalisation de la liste des pays avec une balise <ul>
    $liste_produit = '<div class="afficheProduit" >' . $liste_produit. '</div>';
    }

    } catch (Exception $e) {
    // Gestion de l'exception $e
    echo '<!DOCTYPE html>';
    echo '<html lang="fr"><head>';
        echo '<meta charset="utf-8">';
        echo '<title>Problème rencontré</title>';
        echo '</head><body>';

    // https://www.php.net/manual/fr/function.mb-convert-encoding.php
    echo '<p>' . mb_convert_encoding($e->getMessage(), 'UTF-8', 'Windows-1252') . '</p>';

    // https://www.php.net/manual/fr/pdo.errorinfo.php
    // https://en.wikipedia.org/wiki/SQLSTATE
    if (isset($dbh) && $dbh->errorInfo()[0] == "42000") {
    echo '<p>Erreur de syntaxe dans la requête SQL :</p>';
    echo '<pre>' . $sql . '</pre>';
    }

    echo '</body></html>';

    // Arrêt de l'exécution du script
    die;
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <?php include "mutualisation/bootstrapStyle&meta.php";?>
    <title>Résultat de la recherche des produit</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<?php include ('mutualisation/navigateur.php');?>
<h1>
   Liste des Produits
    <?php echo $produit; ?>
</h1>

<?php echo $liste_produit; ?>


<?php include ('mutualisation/script.php');?>


</body>
</html>
