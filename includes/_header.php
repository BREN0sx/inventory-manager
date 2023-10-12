<?php
error_reporting(0);
session_start();
?>
<head>
    <title>Lab Stock</title>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<?php 
    $categoria = $_GET['categoria'];
?>
        <nav class="navbar">
            <div class="nav-logo"><img src="../img/LabStock.png" alt="LabStock"></div>
            <div class="nav-itens">
                <a class="nav-btn <?php echo empty($categoria) ? "nav-active" : ""?>" href="index.php"> Dashboard</a>
                <?php
                $sql = "SELECT * FROM sessions";
                $sessions = mysqli_query($db, $sql);

                foreach ($sessions as $key => $row) {
                    $session_id = $row['session_id'];
                    $name_session = $row['name_session'];
                ?>
                <a class="nav-btn <?php echo ($categoria == $session_id) ? "nav-active" : ""?>" href="product_category.php?categoria=<?php echo $session_id;?>"><?php echo ucfirst(strtolower($name_session))?></a>
                <?php } ?>
            </div>
            <div class="nav-user"> 
                <?php
                    $resp_query = "SELECT * FROM resp WHERE active_resp = 1 LIMIT 1";
                    $resp_result = mysqli_query($db, $resp_query);
                    
                    $resp_data = mysqli_fetch_assoc($resp_result);

                    $resp_name = $resp_data['name_resp'];
                    $resp_profile = $resp_data['profile_resp'];
                ?>
                <span><?php echo $resp_name?></span>
                <img class="user-avatar" src="<?php echo $resp_profile?>"></img>
            </div>
        </nav>