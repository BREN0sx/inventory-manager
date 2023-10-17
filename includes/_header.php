<?php
error_reporting(0);
session_start();
?>
<head>
    <title>Lab Stock</title>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico">
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
    $categoria = $_GET['category'];
?>
        <nav class="navbar">
            <div class="nav-logo"><img src="../assets/LabStock.gif" alt="LabStock"></div>
            <div class="nav-itens">
                <a class="nav-btn <?php echo empty($categoria) ? "nav-active" : ""?>" href="index.php"> Dashboard</a>
                <?php
                $sql = "SELECT * FROM sessions";
                $sessions = mysqli_query($db, $sql);

                foreach ($sessions as $key => $row) {
                    $session_id = $row['session_id'];
                    $name_session = $row['name_session'];
                ?>
                <a class="nav-btn <?php echo ($categoria == $session_id) ? "nav-active" : ""?>" href="product_category.php?category=<?php echo $session_id;?>"><?php echo ucfirst(strtolower($name_session))?></a>
                <?php } ?>
            </div>
            <div class="nav-user1"> 
            <p id="pdf-creator-btn" class="material-symbols-outlined download-btn">download</p>
                <?php
                    $resp_active_query = "SELECT * FROM resp WHERE active_resp = 1 LIMIT 1";
                    $resp_active_result = mysqli_query($db, $resp_active_query);
                    
                    $resp_active_data = mysqli_fetch_assoc($resp_active_result);

                    $resp_active_id = $resp_active_data['resp_id'];
                    $resp_active_name = $resp_active_data['name_resp'];
                    $resp_active_profile = $resp_active_data['profile_resp'];

                    $resp_inactive_query = "SELECT * FROM resp WHERE active_resp = 0 LIMIT 1";
                    $resp_inactive_result = mysqli_query($db, $resp_inactive_query);
                    
                    $resp_inactive_data = mysqli_fetch_assoc($resp_inactive_result);

                    $resp_inactive_id = $resp_inactive_data['resp_id'];
                    $resp_inactive_name = $resp_inactive_data['name_resp'];
                    $resp_inactive_profile = $resp_inactive_data['profile_resp'];
                ?>

                <form action="../includes/_functions.php" method="POST"  enctype="multipart/form-data" style="display: flex;">

                <input type="hidden" name="action" value="change_active_resp">
                <input type="hidden" name="rrid" value="<?php echo $resp_active_id; ?>">
                <input type="hidden" name="srid" value="<?php echo $resp_inactive_id; ?>">

        
                <?php echo ($resp_active_id == 1) ? '' : '<button type="submit" class="set-resp">'?>
                <div class="display-user-nav1 <?php echo ($resp_active_id == 1) ? 'ativo' : 'inativo'?>">
                    <img class="user-avatar" src="<?php echo ($resp_active_id == 1) ? $resp_active_profile : $resp_inactive_profile?>"></img>
                    <?php echo ($resp_active_id == 1) ? '<span>'.$resp_active_name.'</span>' : ''?>
                    
                    
                </div>
                <?php echo ($resp_active_id == 1) ? '' : '</button>'?>
                <?php echo ($resp_active_id == 2) ? '' : '<button type="submit" class="set-resp">'?>
                <div class="display-user-nav1 <?php echo ($resp_active_id == 2) ? 'ativo' : 'inativo'?>">
                    <?php echo ($resp_active_id == 2) ? '<span>'.$resp_active_name.'</span>' : ''?>
                    <img class="user-avatar" src="<?php echo ($resp_active_id == 2) ? $resp_active_profile : $resp_inactive_profile?>"></img>
                </div>
                <?php echo ($resp_active_id == 2) ? '' : '</button>'?>
                </form>
            </div>
        </nav>
        <script src="../js/changeDisplayUser.js"></script>
        <script src="../js/pdfCreator.js"></script>

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
				<source src="../assets/background.mp4" type="video/mp4"/>
			</video>
        </div>