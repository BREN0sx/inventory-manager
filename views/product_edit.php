<?php
$id = $_GET['eid'];
require_once ("../includes/_db.php");
$product_query = "SELECT * FROM products WHERE product_id = '$id'";
$product_result = mysqli_query($db, $product_query);
$products = mysqli_fetch_assoc($product_result);
?>

<div class="add-modal" id="modal_edit" style="display: <?php echo $id == "" ? "none": "flex";?>;">
<form action="../includes/_functions.php" method="POST"  enctype="multipart/form-data">

<div class="add-section">
    <div class="add-container">
        <div class="add-label">
            <h1>Editar</h1>
            <span class="material-symbols-rounded _modal_edit_close" >close</span>
        </div>
    
        <div class="add-field">
            <label for="name_product" class="form-label">Nome *</label>
            <input type="text"  id="name_product" name="name_product" class="form-control" value="<?php echo $products['name_product']; ?>" required>
        </div>
        <div class="add-row">
            <div class="add-field" style="width: 49%;">
                <label for="internal_code_product" class="form-label">Codigo Interno</label>
                <input type="text"  id="internal_code_product" name="internal_code_product" class="form-control" value="<?php echo $products['ci_product']; ?>">
            </div>
            <div class="add-field" style="width: 49%;">
                <label for="name_product" class="form-label">Quantidade *</label>
                <input type="number"  id="amount_product" name="amount_product" class="form-control" value="<?php echo $products['amount_product']; ?>" min="0" pattern="[1-9]\d*" required>
            </div>
        </div>
        <div class="add-row">
            <div class="add-field" style="width: 49%">
                <?php  
                if (strtolower($session_name) == 'químicos') {
                ?>
                    <label for="label_product" class="form-label">Rótulo de risco *</label>
                    <select name="label_product" id="label_product" class="form-control" required>
                        <?php
                        $label_query = "SELECT * FROM labels";
                        $label_result = mysqli_query($db, $label_query);

                        $options = array();
                        $label_category = array(); 

                        if ($label_result->num_rows > 0) {
                            while ($row = $label_result->fetch_assoc()) {
                                if ($row['label_id'] == '0') continue;
                                $category = array(
                                    'id' => $row['label_id'],
                                    'name' => $row['name_label']
                                );

                                if ($row['label_id'] == $products['label_id']) {
                                    array_push($label_category, $category);
                                } else {
                                    $options[] = $category;
                                }
                            }
                            array_unshift($options, ...$label_category);

                            foreach ($options as $option) {
                                echo '<option value="' . $option['id'] . '">[ ' . $option['id'] . " ] ". $option['name'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                <?php  
                } else {
                ?>
                <input type="hidden" name="label_product" value="0">

                <label for="foto" class="form-label">Nova imagem</label>
                <input type="file" class="form-control-file" name="foto" id="foto" accept=".png, .jpg, .jpeg, .webp">
                <?php
                }
                ?>
            </div>
            
            <div class="add-field" style="width: 49%;">
                <label for="validity_date_product" class="form-label">Validade</label>
                <input type="date"  id="validity_date_product" name="validity_date_product" class="form-control" value="<?php echo $products ['validity_product']; ?>">
            </div>
        </div>
        <?php  
            if (strtolower($session_name) == 'químicos') {
            ?>
        
        <div class="add-row">
            <div class="add-field" style="width: 49%;">
            <label for="foto" class="form-label">Nova imagem</label>
                <input type="file" class="form-control-file" name="foto" id="foto" accept=".png, .jpg, .jpeg, .webp">
            </div>
        </div>
        <?php
            }
        ?>
        <input type="hidden" name="product_session" value="<?php echo $products['session_id']; ?>">
        <input type="hidden" name="action" value="product_edit">
        <input type="hidden" name="id" value="<?php echo $_GET['eid']; ?>">
        <button type="submit" class="main-btn btn btn-success pEdit"><span class="material-symbols-rounded">check</span>Salvar</button>
    </div>
</div>
</form>
</div>