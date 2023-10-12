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
            <input type="text"  id="name_product" name="name_product" class="form-control" value="<?php echo $products ['name_product']; ?>" required>
        </div>
        <div class="add-row">
            <div class="add-field" style="width: 49%;">
                <label for="internal_code_product" class="form-label">Codigo Interno</label>
                <input type="text"  id="internal_code_product" name="internal_code_product" class="form-control" value="<?php echo $products ['ci_product']; ?>">
            </div>
            <div class="add-field" style="width: 49%;">
                <label for="name_product" class="form-label">Quantidade *</label>
                <input type="number"  id="amount_product" name="amount_product" class="form-control" value="<?php echo $products ['amount_product']; ?>" min="0" pattern="[1-9]\d*" required>
            </div>
        </div>
        <div class="add-row">
            <div class="add-field" style="width: 49%;">
                <label for="name_product" class="form-label">Categoria *</label>
                <select name="categorias" id="categorias" class="form-control" required>
                    <?php
                        $session_query = "SELECT * FROM sessions";
                        $session_result = mysqli_query($db, $session_query);
                        
                        $options = array();
                        $product_category = array(); 
                        if ($session_result->num_rows > 0) {
                            while ($row = $session_result->fetch_assoc()) {
                                $category = array(
                                    'id' => $row['session_id'],
                                    'name' => $row['name_session']
                                );
                        
                                if ($row['session_id'] == $products['session_id']) {
                                    array_push($product_category, $category);
                                } else {
                                    $options[] = $category;
                                }
                            }
                            array_unshift($options, ...$product_category);
        
                            foreach ($options as $option) {
                                echo '<option value="' . $option['name'] . '">' . $option['name'] . '</option>';
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="add-field" style="width: 49%;">
                <label for="validity_date_product" class="form-label">Validade</label>
                <input type="date"  id="validity_date_product" name="validity_date_product" class="form-control" value="<?php echo $products ['validity_product']; ?>">
            </div>
        </div>
        <div class="add-field">
            <label for="foto" class="form-label">Nova imagem</label>
            <input type="file" class="form-control-file" name="foto" id="foto" accept=".png, .jpg, .jpeg, .webp">
        </div>
        <input type="hidden" name="action" value="product_edit">
        <input type="hidden" name="id" value="<?php echo $_GET['eid']; ?>">
        <button type="submit" class="main-btn btn btn-success"><span class="material-symbols-rounded">check</span>Salvar</button>
    </div>
</div>
</form>
</div>