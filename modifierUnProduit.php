<?php
try {

    /**--------------------------La liste des categories à afficher dans le select      ----------------**/


    $options_categorie = "";// $options_categorie contiendra toutes les catégories


    /*-----Inclusion des informations de connexion à la base de données--*/

    require_once 'mutualisation/connexion.php';

    $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    /*----Requête  qui affiche toutes les catégories-- --*/
    $sql = "SELECT idCategorie, nomCategorie FROM Categorie ORDER BY nomCategorie";


    /*-----Résultat de la requête----*/
    $resultat = $dbh->query($sql);




    while ( ($une_categorie = $resultat->fetch(PDO::FETCH_ASSOC)) != FALSE) {

        // Traitement de chaque résultat qui est contenu dans la variable $une_catégorie
        $options_categorie .= '<option value="' . $une_categorie['idCategorie'] . '">' . $une_categorie['nomCategorie'] . '</option>';

    }
    /*-----------------------------------------------------------------------------------------------------------*/




    /**-----------------------------     liste des codes de promotion à mettre dans le select     ----------------**/


    $options_promotion = ""; // $options_promotion contiendra toutes les promos existantes


    /*------Requête qui affiche toutes les promotions--------- */
    $sql = "SELECT codePromo FROM Promotion WHERE dateFin>= current_date ORDER BY codePromo ";


    /*----Résultat de la requête-----*/
    $resultat = $dbh->query($sql);



    while ( ($une_promo = $resultat->fetch(PDO::FETCH_ASSOC)) != FALSE) {

        // Traitement de chaque résultat qui est contenu dans la variable $une_promo
        $options_promotion .= '<option value="' . $une_promo['codePromo'] . '">' . $une_promo['codePromo'] . '</option>';
    }




    /**--------------------------------- liste option du produit------------------------------------------**/


    $options_produit = "";// variable qui contiendra la listes des produits


    /*----Requête de recherche des continents-----*/
    $sql = "SELECT nProduit, nomProduit FROM Produit ORDER BY nomProduit";

    /*----Résultat de la requête----*/
    $resultat = $dbh->query($sql);



    while ( ($un_produit = $resultat->fetch(PDO::FETCH_ASSOC)) != FALSE) {

        // Traitement de chaque résultat qui est contenu dans la variable $un_produit
        $options_produit .= '<option value="' . $un_produit['nProduit'] . '">' . $un_produit['nomProduit'] . '</option>';
    }
    /*--------------------------------------------------------------------------------------------------- */



    /*---------------------------  Gestion des buttons du formulaire ---------------------------------- */


    if ($_POST['afficher']) { /*-------- Lorsque on clique sur le button afficher ------*/


        $produit = (string)filter_input(INPUT_POST, 'nProduitListe', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


        $liste_produit = "";


        /*--Requête qui affiches tous les attributs du produit séléctionné dans le formulaire-- */
        $sql = "SELECT nProduit, nomProduit, descProduit, prixProduit, equitable, qteStock, codePromo, nomCategorie FROM Produit, Categorie WHERE Produit.idCategorie=Categorie.idCategorie AND nProduit='$produit'";
        $resultat = $dbh->query($sql);





        while (($un_produit = $resultat->fetch(PDO::FETCH_ASSOC)) != FALSE) {

            $nProd = $un_produit['nProduit'];
            $nom = $un_produit['nomProduit'];
            $desc = $un_produit['descProduit'];
            $prix = $un_produit['prixProduit'];
            $promo = $un_produit['codePromo'];
            $categorie = $un_produit['nomCategorie'];
            $quantite= $un_produit['qteStock'];
            $equitable = ($un_produit['equitable'] == 1) ? 'oui' : 'non';



        }
        /*-------------------------------------------------------------------------------------------- */

    }else if ($_POST['enregistrer']){ /*-------- Lorsque on clique sur le button enregistrer ------*/


        $produit= $_POST['nProduit'];

        if ($_POST['codePromo']=="") { /*------La requête à excuter si le code promo n'est pas reseigné--------  */

            $requete = $dbh->prepare("UPDATE Produit SET nomProduit = :nomProduit, prixProduit= :prixProduit, qteStock= :qteStock, equitable= :equitable,descProduit=:descProduit WHERE nProduit=$produit   ");

        }else{ /*------La requête à excuter si le code promo est  reseigné--------  */

            $requete = $dbh->prepare("UPDATE Produit SET nomProduit = :nomProduit, prixProduit= :prixProduit, qteStock= :qteStock, equitable= :equitable,descProduit=:descProduit, codePromo= :codePromo WHERE nProduit=$produit   ");
            $requete->bindParam(':codePromo', $_POST['codePromo']);
        }


        $equitable= ($_POST['equitable']=='oui')? 1 : 0;
        $requete->bindParam(':nomProduit', $_POST['nomProduit']);
        $requete->bindParam(':prixProduit', $_POST['prixProduit']);
        $requete->bindParam(':qteStock', $_POST['qteStock']);
        $requete->bindParam(':equitable', $equitable);
        $requete->bindParam(':descProduit', $_POST['descProduit']);


        $requete->execute();

        echo "Le produit a été bien ajouté.";

    }
} catch (Exception $e) {
    // Gestion de l'exception $e
    echo '<!DOCTYPE html>';
    echo '<html lang="fr"><head>';
    echo '<meta charset="utf-8">';
    echo '<title>Problème rencontré</title>';
    echo '</head><body>';

    echo '<p>' . mb_convert_encoding($e->getMessage(), 'UTF-8', 'Windows-1252') . '</p>';

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

    <title>Modifier un produit</title>
</head>
<body>
<?php include ('mutualisation/navigateur.php')?>

<div class="container">

    <form action="modifierUnProduit.php" method="post">

        <div class="afficherCon">
        <!---------------------------------- nom du produit  -------------------->
        <label for="nProduit">Nom du produit:</label>
        <select name="nProduitListe" id="nProduit">
            <option value="">--Selectionner le produit--</option>
            <?php echo $options_produit; ?>
        </select>
        </div>

        <!-------------------------------------------------------------------------->


        <!---------------  Button pour  affichager le  produit -------------------->
        <div>
            <input type="submit" value="Afficher" name="afficher"><br>


            <!----------------------    Code du produit     --------------------------->
            <label for="nProduit">Code du produit:</label>
            <input type="text" name="nProduit" placeholder="Code du produit" id="nProduit" readonly value="<?php echo $nProd ?>" ><br>
            <!-------------------------------------------------------------------------->

            <!----------------------    Nom du produit     ------------------------------>
            <label for="nomProduit">Nom du produit:</label>
            <input type="text" name="nomProduit" placeholder="Nom du produit"  id="nomProduit" value="<?php echo $nom; ?>"><br>
            <!------------------------------------------------------------------------>


            <!----------------------    Prix du produit     ---------------------------->
            <label for="prixProduit">Prix du produit:</label>
            <input type="text" name="prixProduit" placeholder="Prix du produit"  id="prixProduit" value="<?php echo $prix; ?>"><br>
            <!------------------------------------------------------------------------->


            <!-------------------------    Quantité du produit     -------------------->
            <label for="qteStock">Quantité du produit:</label>
            <input type="number" name="qteStock" placeholder="Quantité du produit"  id="qteStock" value="<?php echo $quantite; ?>"><br>

            <!------------------------------------------------------------------------->


            <!---------------------------    Radio button equitable -------------------->
            <label for="equitable">Equitable:</label>
            <input type="radio" name="equitable" value="oui" <?php if($equitable=='oui') echo 'checked';?>  id="equitable">
            <label for="oui">Oui</label>
            <input type="radio" name="equitable" value="non" <?php if($equitable=='non') echo 'checked' ;?> id="equitable">
            <label for="non">Non</label><br>
            <!-------------------------------------------------------------------------->



            <!-------------------------------Code Promo -------------------------------->
            <label for="codePromo">Code promotion</label>
            <select name="codePromo" id="codePromo">
                <option value=""><?php echo $options_promotion; ?></option>

            </select><br>
            <!-------------------------------------------------------------------------->



            <!-------------------------------- Categorie ---------------------------->
            <label for="idCategorie">Categorie du produit:</label>
            <select name="idCategorie" id="idCategorie">
                <option value=""><?php echo $categorie; ?></option>
                <?php echo $options_categorie; ?>
            </select><br><br>

            <!----------------------------------------------------------------------->


            <!----------------------    Description du produit -------------------->
            <p>Description du produit:</p>
            <textarea rows="10" cols="30" name="descProduit" placeholder="Description de produit"  id="descProduit" value="gcjcjkc"><?php echo $desc; ?></textarea><br>

            <!-------------------------------------------------------------------------->

            <!----------------------   Button pour  enregistrer le  produit -------------------->
            <input type="submit" value="Enregistrer" name="enregistrer">
        </div>
    </form>
</div>

<?php include ('mutualisation/script.php');?>

</body>
</html>
