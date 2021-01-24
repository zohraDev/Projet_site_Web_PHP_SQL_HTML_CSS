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
<!doctype html>
<html lang="en">
<head>
    <?php include "mutualisation/bootstrapStyle&meta.php";?>
    <link href="style/panierStyle.css" type="text/css" rel="stylesheet" />
    <title>chercher un produit</title>
</head>
<body >
<?php include ('mutualisation/navigateur.php')?>




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

                    </div>
                </form>
            </div>
            <?php
        }
    }
    ?>
</div>


<?php include ('mutualisation/script.php');?>
</body>
</html>