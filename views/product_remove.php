<?php
$categoria = $_GET['categoria'];

$id = $_GET['did'];
require_once ("../includes/_db.php");
$product_query = "SELECT * FROM products WHERE product_id = '$id'";
$product_result = mysqli_query($db, $product_query);
$products = mysqli_fetch_assoc($product_result);
?>

<div class="add-modal" id="modal_delete" style="display: <?php echo $id == "" ? "none": "flex";?>;">
<form action="../includes/_functions.php" method="POST"  enctype="multipart/form-data">

<div class="add-section">
    <div class="add-container">
        <div class="add-label">
            <h1>Confirmar ação - <?php echo $products['name_product']?></h1>
            <span class="material-symbols-rounded _modal_delete_close">close</span>
        </div>
        <input type="hidden" name="action" value="product_remove">
        <input type="hidden" name="id" value="<?php echo $_GET['did'];?>">
        <input type="hidden" name="cat-id" value="<?php echo $_GET['categoria'];?>">
        <button class="main-btn trash pDel" type="submit" class="btn btn-success"><span class="material-symbols-rounded">check</span>Deletar</button>
    </div>
</div>
</form>
</div>