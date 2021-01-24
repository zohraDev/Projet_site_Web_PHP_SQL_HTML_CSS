<?php

try {

    /**----------------     liste option categorie      ----------------**/
    // Code HTML contenant les choix de continents
    $options_categorie = "";

    // Inclusion des informations de connexion à la base de données
    require_once 'mutualisation/connexion.php';

    $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête de recherche des continents
    $sql = "SELECT idCategorie, nomCategorie FROM Categorie ORDER BY nomCategorie";

    // Résultat de la requête de recherche
    $resultat = $dbh->query($sql);

    // Parcours de tous les résultats de la requête
    // https://www.php.net/manual/fr/pdostatement.fetch.php
    while ( ($une_categorie = $resultat->fetch(PDO::FETCH_ASSOC)) != FALSE) {
        // Traitement de chaque résultat qui est contenu dans la variable $un_continent
        $options_categorie .= '<option value="' . $une_categorie['idCategorie'] . '">' . $une_categorie['nomCategorie'] . '</option>';
    }


    /**----------------     liste option du produit     ----------------**/
    $options_produit = "";


    // Requête de recherche des continents
    $sql = "SELECT nProduit, nomProduit FROM Produit ORDER BY nomProduit";

    // Résultat de la requête de recherche
    $resultat = $dbh->query($sql);

    // Parcours de tous les résultats de la requête
    // https://www.php.net/manual/fr/pdostatement.fetch.php
    while ( ($un_produit = $resultat->fetch(PDO::FETCH_ASSOC)) != FALSE) {
        // Traitement de chaque résultat qui est contenu dans la variable $un_continent
        $options_produit .= '<option value="' . $un_produit['nProduit'] . '">' . $un_produit['nomProduit'] . '</option>';
    }
/****************************************************************/






    $dbh = null;
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

<!doctype html>
<html lang="en">
<head>
    <?php include "mutualisation/bootstrapStyle&meta.php";?>
    <link rel="stylesheet" href="style/style.css">

    <title>Ajouter un produit</title>
</head>
<body>

<?php include ('mutualisation/navigateur.php')?>


<div class="containerAjSup">
    <form action="resultatAjoute.php" method="post">
        <h3><u>Ajouter un produit</u></h3>

        <div class="ajouteProd">
            <!----------------------    code du produit     -------------------->
            <label for="nProduit">Code du produit:</label>
            <input type="text" name="nProduit" placeholder="Code du produit" required id="nProduit"><br>

            <!----------------------    nom du produit     -------------------->
            <label for="nomProduit">Nom du produit:</label>
            <input type="text" name="nomProduit" placeholder="Nom du produit" required id="nomProduit"><br>

            <!----------------------    prix du produit     -------------------->
            <label for="prixProduit">Prix du produit:</label>
            <input type="text" name="prixProduit" placeholder="Prix du produit" required id="prixProduit"><br>

            <!----------------------    quantité du produit     -------------------->
            <label for="qteStock">Quantité du produit:</label>
            <input type="number" name="qteStock" placeholder="Quantité du produit" required id="qteStock"><br>

            <!----------------------    radio button equitable -------------------->
            <label for="equitable">Equitable:</label>
            <input type="radio" name="equitable" value="oui" required id="equitable">
            <label for="oui">Oui</label>

            <input type="radio" name="equitable" value="non" required id="equitable">
            <label for="non">Non</label><br>

            <label for="codePromo">Code promotion</label>
            <input type="number" name="codePromo"  placeholder="Code promotion"><br>

    </div>


        <!----------------------   categorie    -------------------->

    <div class="supprimerProd">
        <label for="idCategorie"> Categorie du produit:</label>
        <select name="idCategorie" id="idCategorie">
            <option value="">--Selectionner la categorie--</option>
            <?php echo $options_categorie; ?>
        </select><br>

        <!----------------------    description du produit -------------------->
        <p> Description du produit:</p>
        <textarea rows="10" cols="30" name="descProduit" placeholder="Description de produit" required id="descProduit"></textarea><br>

        <!----------------------    description du produit -------------------->
        <input type="submit" value="Ajouter">
    </div>
        <br><br>


    </form>



    <!-------------------------------------------   supprimer le produit    ---------------------------------------------->

    <form action="supprimerUnProduit.php" method="get">
        <h3><u>Supprimer un produit</u></h3>

        <!----------------------    nom du produit     -------------------->
        <label for="nProduit">Nom du produit:</label>
        <select name="nProduit" id="nProduit">
            <option value="">--Selectionner le produit--</option>
            <?php echo $options_produit; ?>
        </select><br><br>

        <input type="submit" value="Supprimer">


    </form>
</div>

<?php include ('mutualisation/script.php');?>
</body>
</html>
