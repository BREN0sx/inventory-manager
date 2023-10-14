<?php
    $categoria = $_GET['categoria'];
    $session_query = "SELECT name_session FROM sessions WHERE session_id = $categoria";
    $session_result = mysqli_query($db, $session_query);
    $session_name = mysqli_fetch_assoc($session_result)['name_session'];

?>

<div class="add-modal" id="modal_add" style="display: none;">
<form action="../includes/_functions.php" method="POST"  enctype="multipart/form-data">

<div class="add-section">
    <div class="add-container">
        <div class="add-label">
            <h1>Adicionar - <?php echo ucfirst(strtolower($session_name))?></h1>
            <span class="material-symbols-rounded _modal_add_close" >close</span>
        </div>
    
        <div class="add-field">
            <label for="name_product" class="form-label">Nome *</label>
            <input type="text"  id="name_product" name="name_product" class="form-control" placeholder="Insira o nome" required>
        </div>
        <div class="add-row">
            <div class="add-field" style="width: 49%;">
                <label for="internal_code_product" class="form-label">Codigo Interno</label>
                <input type="text"  id="internal_code_product" name="internal_code_product" class="form-control" placeholder="Insira o código">
            </div>
            <div class="add-field" style="width: 49%;">
                <label for="name_product" class="form-label">Quantidade *</label>
                <input type="number"  id="amount_product" name="amount_product" class="form-control" placeholder="Insira a quantidade" min="0" pattern="[1-9]\d*" required>
            </div>
        </div>
        <div class="add-row">
            <div class="add-field" style="width: 49%;">
                <label for="foto" class="form-label">Imagem *</label>
                <input type="file" class="form-control-file" name="foto" id="foto" accept=".png, .jpg, .jpeg, .webp" required>
            </div>
            <div class="add-field" style="width: 49%;">
                <label for="validity_date_product" class="form-label">Validade</label>
                <input type="date"  id="validity_date_product" name="validity_date_product" class="form-control" placeholder="Insira o nome">
            </div>
        </div>

        <input type="hidden" name="category_product" value="<?php echo $categoria; ?>">

        <input type="hidden" name="action" value="product_add">
        <button class="main-btn pAdd" type="submit" class="btn btn-success"><span class="material-symbols-rounded">check</span>Salvar</button>
    </div>
</div>
</form>
</div>