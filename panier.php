<?php
session_start();
require_once("bdd/classPanierBDD.php");
$db_handle = new DBController();
if(!empty($_GET["action"])) {

    switch($_GET["action"]) {

        case "add":
            if(!empty($_POST["quantity"])) {
                $productByCode = $db_handle->runQuery("SELECT nProduit AS code, nomProduit AS name, qteStock AS quantity, prixProduit AS price, descProduit AS image FROM Produit WHERE nProduit='" . $_GET["code"] . "'");
                $itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));

                if(!empty($_SESSION["cart_item"])) {
                    if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
                        var_dump(array_keys($_SESSION["cart_item"]));
                        var_dump($productByCode[0]["code"]);
                        foreach($_SESSION["cart_item"] as $k => $v) {
                            if($productByCode[0]["code"] == $k) {
                                if(empty($_SESSION["cart_item"][$k]["quantity"])) {
                                    $_SESSION["cart_item"][$k]["quantity"] = 0;
                                }
                                $_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
                            }

                        }
                    } else {
                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
                    }
                } else {
                    $_SESSION["cart_item"] = $itemArray;
                }
            }
            break;
        case "remove":
            if(!empty($_SESSION["cart_item"])) {
                foreach($_SESSION["cart_item"] as $k => $v) {
                    if($_GET["code"] == $k)
                        unset($_SESSION["cart_item"][$k]);
                    if(empty($_SESSION["cart_item"]))
                        unset($_SESSION["cart_item"]);
                }
            }
            break;
        case "empty":
            unset($_SESSION["cart_item"]);
            break;
    }
}
?>
<HTML>
<HEAD>
    <?php include "mutualisation/bootstrapStyle&meta.php";?>
    <TITLE>Achat</TITLE>
    <link href="style/panierStyle.css" type="text/css" rel="stylesheet" />
</HEAD>
<BODY>
<?php include ('mutualisation/navigateur.php')?>


<!--/****************************   Panier d'achat  **************************/-->
<div id="shopping-cart">
    <div class="txt-heading"><h2>Achat</h2></div>

    <a id="btnEmpty" href="panier.php?action=empty">Vider le panier</a>
    <?php
    if(isset($_SESSION["cart_item"])){
        $total_quantity = 0;
        $total_price = 0;
        ?>
        <table class="tbl-cart" cellpadding="10" cellspacing="1">
            <tbody>
            <tr>
                <th style="text-align:left;">Nom</th>
                <th style="text-align:left;">Code</th>
                <th style="text-align:right;" width="5%">Quantité</th>
                <th style="text-align:right;" width="10%">Prix Unitaire</th>
                <th style="text-align:right;" width="10%">Total Prix</th>
                <th style="text-align:center;" width="5%">Supprimer</th>
            </tr>
            <?php
            foreach ($_SESSION["cart_item"] as $item){
                $item_price = $item["quantity"]*$item["price"];
                ?>
                <tr>
                    <td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
                    <td><?php echo $item["code"]; ?></td>
                    <td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
                    <td  style="text-align:right;"><?php echo $item["price"]."€"; ?></td>
                    <td  style="text-align:right;"><?php echo number_format($item_price,2)."€"; ?></td>
                    <td style="text-align:center;"><a href="panier.php?action=remove" class="btnRemoveAction"><img src="image/icon-delete.png" alt="Remove Item" /></a></td>

                </tr>
                <?php
                $total_quantity += $item["quantity"];
                $total_price += ($item["price"]*$item["quantity"]);
            }
            ?>

            <tr>
                <td colspan="2" align="right">Total:</td>
                <td align="right"><?php echo $total_quantity; ?></td>
                <td align="right" colspan="2"><strong><?php echo number_format($total_price, 2)."€"; ?></strong></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        <?php
    } else {
        ?>
        <div class="no-records">Votre panier est vide</div>
        <?php
    }
    ?>
</div>

<div id="product-grid">
    <div class="txt-heading">Produits</div>
    <?php
    $product_array = $db_handle->runQuery("SELECT nProduit AS code, nomProduit AS name, qteStock AS quantity, prixProduit AS price, descProduit AS image FROM Produit ");
    if (!empty($product_array)) {
        foreach($product_array as $key=>$value){
            ?>
            <div class="product-item">
                <form method="post" action="panier.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
                    <div class="product-image"><img src="<?php echo $product_array[$key]["image"]; ?>"></div>
                    <div class="product-tile-footer">
                        <div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
                        <div class="product-price"><?php echo $product_array[$key]["price"].'€'; ?></div>
                        <div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="2" /><input type="submit" value="Add to Cart" class="btnAddAction" /></div>
                    </div>
                </form>
            </div>
            <?php
        }
    }
    ?>
</div>
<?php include ('mutualisation/script.php');?>
</BODY>
</HTML>