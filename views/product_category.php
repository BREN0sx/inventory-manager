<!DOCTYPE html>
<html lang="en">
<?php require '../includes/_db.php'?>
<?php require '../includes/_header.php'?>
<?php require 'product_add.php'?>
<?php require 'product_edit.php'?>
<?php require 'product_remove.php'?>
<body>

<?php
    $categoria = $_GET['categoria'];
    $session_query = "SELECT name_session FROM sessions WHERE session_id = $categoria";
    $session_result = mysqli_query($db, $session_query);
    $session_name = mysqli_fetch_assoc($session_result)['name_session'];
?>

<div class="main-container">
    <h1><?php echo ucfirst(strtolower($session_name))?></h1>
    <a class="main-btn _modal_add_open" href="#"><span class="material-icons">add</span>Adicionar</a>
</div>

<div class="table-container">
    <table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Nome</th>
                <th>#</th>
				<th>Quantidade</th>
				<th>Validade</th>
				<th>Adicionado por</th>
                <th>Ação</th>
			</tr>
		</thead>
        <tbody>
            <?php
                extract($_POST);
                $sql = "SELECT * FROM products WHERE session_id = $categoria";
                $productos = mysqli_query($db, $sql);
                if($productos -> num_rows > 0){
                    foreach($productos as $key => $row ){
                        $session_id = $row['session_id'];
                        $sessionQuery = "SELECT s.name_session FROM sessions s INNER JOIN products p ON s.session_id = p.session_id WHERE p.session_id = $session_id";
                        $sessionData = mysqli_query($db, $sessionQuery);
                        $sessionName = ($sessionData->num_rows > 0) ? $sessionData->fetch_assoc()['name_session'] : '';

                        $validity_date = $row["validity_product"];
                        $today_date = date("Y-m-d");

                        $today_time = time();
                        $validity_time = strtotime($validity_date);
                        $variation_time = ceil(($today_time - $validity_time) / (60 * 60 * 24));

                        $status_type = ($validity_date >= $today_date) ? ($variation_time >= -1 ? "warning" : "success") : "danger";

                        $resp_id = $row['resp_id'];
                        $respQuery = "SELECT r.name_resp FROM products p INNER JOIN resp r ON p.resp_id = r.resp_id WHERE p.resp_id = $resp_id";
                        $respData = mysqli_query($db, $respQuery);
                        $respName = ($respData->num_rows > 0) ? $respData->fetch_assoc()['name_resp'] : '';
            ?>

            <tr>
            <td><?php echo $row['product_id'];?></td>
            <td><div class="product-item"><img width="100" src="data:image;base64,<?php echo base64_encode($row['image_product']); ?>" alt="Imagem"> <?php echo $row['name_product'];?></div></td>
            <td><?php echo $row['ci_product']; ?></td>
            <td><?php echo $row['amount_product']; ?></td>
            <td>
                <?php
                if ($validity_date != "0000-00-00")  {
                ?>
                <div class="validity-status <?php echo $status_type;?>">
                    <?php echo date("d/m/y", strtotime($validity_date))?>
                </div>
                <?php
                }
                ?>
            </td>
            <td><?php echo $respName; ?></td>
            <td><a class="_modal_edit_open" id="<?php echo $row['product_id']?>" href="product_category.php?categoria=<?php echo $categoria?>&eid=<?php echo $row['product_id']?>"><span class="material-icons">mode_edit</span></a>
            <a class="_modal_delete_open" id="<?php echo $row['product_id']?>" href="product_category.php?categoria=<?php echo $categoria?>&did=<?php echo $row['product_id']?>"> <span class="material-icons">delete</span></a></td>
            
            <?php
                }
                } else {
            ?>
        </tbody>
    </table>
</div>
<div class="empty-product">Sem registros</div>
<?php
    }
?>


<?php require '../includes/_footer.php' ?>
<script src="../js/modal.js"></script>

</html>