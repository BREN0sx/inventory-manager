<?php
error_reporting(0);
session_start();
?>
<head>
    <title>Lab Stock</title>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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

        <style>
            .video-mask:before {
                position: absolute;
                content: '';
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                background: linear-gradient(to bottom, rgb(255 255 255 / 0%) 80%, rgb(34 35 37));
                z-index: -1;
            }
            #video-background {
                position: absolute;
                width: 100%;
                height: 100%;
                object-fit: cover;
                z-index: -2;
                top: 0;
                left: 0;
                filter: brightness(0.2) grayscale(1);
                opacity: .2;
            }
        </style>
        <div class="video-mask">
        <video id="video-background" autoplay muted loop>
				<source src="../img/background.mp4" type="video/mp4"/>
			</video>
        </div>