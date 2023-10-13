<?php
require_once ("_db.php");

if(isset($_POST['action'])){ 
    switch($_POST['action']){
        case 'product_remove':
            product_remove();
        break;        
        case 'product_edit':
            product_edit();
        break;
        case 'product_add':
            product_add();
        break;  
        case 'change_active_resp':
            change_active_resp();
        break;   
    }
}

function product_add(){
    global $db;
    extract($_POST);

    $file_size = $_FILES['foto']['size'];
    
    $file_up = fopen($_FILES['foto']['tmp_name'], 'r');
    $image_bin = fread($file_up,$file_size);   

    $image_product = mysqli_escape_string($db,$image_bin);

    $category_product = $_POST['category_product'];
                
    $session_query = "SELECT * FROM sessions WHERE session_id = $category_product";
    $session_result = mysqli_query($db, $session_query);
    $session_id = mysqli_fetch_assoc($session_result)['session_id'];

    $resp_query = "SELECT resp_id FROM resp WHERE active_resp = 1 LIMIT 1";
    $resp_result = mysqli_query($db, $resp_query);
    $resp_id = mysqli_fetch_assoc($resp_result)['resp_id'];

    $internal_code_product = isset($_POST['internal_code_product']) ? $_POST['internal_code_product'] : 0;
    $validity_date_product = isset($_POST['validity_date_product']) ? $_POST['validity_date_product'] : '';

    $consulta="INSERT INTO products (name_product, ci_product, amount_product, validity_product, image_product, session_id, resp_id)
    VALUES ('$name_product', '$internal_code_product', $amount_product, '$validity_date_product', '$image_product', $session_id, $resp_id);" ;

    mysqli_query($db, $consulta);
    
    header("Location: ../views/product_category.php?categoria=$session_id");
}

function product_edit(){
    global $db;
    extract($_POST);

    $product_query = "SELECT * FROM products WHERE name_product = '$name_product'";
    $product_result = mysqli_query($db, $product_query);
    $product_row = mysqli_fetch_assoc($product_result);

    if (empty($_FILES['foto']['name'])) {
        $image_product = $product_row['image_product'];
    } else {
        $file_size = $_FILES['foto']['size'];
        $file_up = fopen($_FILES['foto']['tmp_name'], 'r');
        $image_bin = fread($file_up,$file_size); 
        $image_product = mysqli_escape_string($db,$image_bin);  

        $image_send_query="UPDATE products SET image_product = '$image_product' WHERE product_id = $id";
        mysqli_query($db, $image_send_query);
    }

    $consultaSessao = "SELECT session_id FROM sessions WHERE name_session = '$categorias'";
    $resultadoSessao = mysqli_query($db, $consultaSessao);

    $rowSessao = mysqli_fetch_assoc($resultadoSessao);
    $idSessao = $rowSessao['session_id'];

    $internal_code_product = isset($_POST['internal_code_product']) ? $_POST['internal_code_product'] : '';
    $validity_date_product = isset($_POST['validity_date_product']) ? $_POST['validity_date_product'] : '';

    $consulta="UPDATE products SET name_product = '$name_product', amount_product = '$amount_product', ci_product = '$internal_code_product', validity_product = '$validity_date_product', session_id = '$idSessao' WHERE product_id = $id";
    mysqli_query($db, $consulta);
    header("Location: ../views/product_category.php?categoria=$idSessao");
}
function product_remove(){
    global $db;
    extract($_POST);

    $product_id = $_POST['id'];
    $category_id = $_POST['cat-id'];
    $product_query = "DELETE FROM products WHERE product_id = $product_id";
    mysqli_query($db, $product_query);
    header("Location: ../views/product_category.php?categoria=$category_id");
}


function change_active_resp(){
    global $db;
    extract($_POST);

    $active_resp_id = $_POST['rrid'];
    $inactive_resp_id = $_POST['srid'];

    $unset_resp_query = "UPDATE resp SET active_resp = 0 WHERE resp_id = $active_resp_id";
    mysqli_query($db, $unset_resp_query);

    $set_resp_query = "UPDATE resp SET active_resp = 1 WHERE resp_id = $inactive_resp_id";
    mysqli_query($db, $set_resp_query);

    header("Location: ../views/index.php");
}
?>