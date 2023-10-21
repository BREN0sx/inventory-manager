<!DOCTYPE html>
<html lang="pt-BR">
<?php require '../includes/_db.php'?>
<?php require '../includes/_header.php'?>
<?php
$session_group_query = "SELECT COUNT(*) AS amount_session FROM sessions";
$session_group_result = mysqli_query($db, $session_group_query);
$session_group_total = $session_group_result->fetch_assoc()['amount_session'];

$resp_group_query = "SELECT COUNT(*) AS amount_resp FROM resp";
$resp_group_result = mysqli_query($db, $resp_group_query);
$resp_group_total = $resp_group_result->fetch_assoc()['amount_resp'];

$products_group_query = "SELECT session_id, SUM(amount_product) AS amount FROM products GROUP BY session_id";
$products_group_result = mysqli_query($db, $products_group_query);

$products_group_data = array();

$products_group_total = 0;
while ($products_group_row = $products_group_result->fetch_assoc()) {
    $products_group_total += $products_group_row['amount'];
    $products_group_data[] = $products_group_row;
}

$json_data = array();

foreach ($products_group_data as $key => &$item_group) {
    $session_id = $item_group['session_id'];
    $sessionQuery = "SELECT * FROM sessions WHERE session_id = $session_id";
    $sessionData = mysqli_query($db, $sessionQuery);
    $sessionName = ($sessionData->num_rows > 0) ? $sessionData->fetch_assoc()['name_session'] : '';

    $colors = ["#00984E", "#00E567", "#63F298"];
    $color = isset($colors[$key]) ? $colors[$key] : "#000000";

    $item_group['percentage'] = ($item_group['amount'] / $products_group_total) * 100;

    $rounded_percentage = round(($item_group['amount'] / $products_group_total) * 100);
    $item_group['rounded_percentage'] = $rounded_percentage;

    $json_data[] = array(
        'category' => $sessionName,
        'percentage' => $item_group['percentage'],
        'rounded_percentage' => $item_group['rounded_percentage'],
        'amount' => $item_group['amount'],
        'color' => $color,
    );
}
?>

<div class="main-container">
    <h1>Dashboard</h1>
</div>

<div class="primary-info"> 
    <div class="info-container">
        <div class="info-section">
            <div class="label-box">
            <span class="material-symbols-outlined">deployed_code</span>
                Total<br>Cadastrados
            </div>
        </div>
        <div class="info-box">
            <?php echo $products_group_total;?>
        </div>
    </div>
    <div class="info-container">
        <div class="info-section">
            <div class="label-box">
            <span class="material-symbols-outlined">dehaze</span>
                Total<br>Categorias
            </div>
        </div>
        <div class="info-box">
            <?php echo $session_group_total;?>
        </div>
    </div>
    <div class="info-container">
        <div class="info-section">
            <div class="label-box">
            <span class="material-icons">person</span>
                Total<br>Gerenciadores
            </div>
        </div>
        <div class="info-box">
            <?php echo $resp_group_total;?>
        </div>
    </div>
</div>

<script>
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
        ['Category', 'Percentage', {type: 'string', role: 'style'}],
        <?php
            foreach ($json_data as $item) {
                echo "['" . $item['category'] . "', " . $item['rounded_percentage'] . ", '" . $item['color'] . "'],";
            }
        ?>
    ]);

        var options = {
          pieHole: 0.6,
          pieSliceText: 'none',
          tooltip: { trigger: 'none' },
          legend: 'none',
          colors: ["#00984E", "#00E567", "#63F298"],
          backgroundColor: 'none',
          pieSliceBorderColor: 'none',
          chartArea:{width:'90%',height:'90%'},
          sliceVisibilityThreshold: 0
        };

        var chart = new google.visualization.PieChart(document.getElementById('donut_single'));
        chart.draw(data, options);
      }
</script>

<section class="info">
<div class="info-hidden">
</div>
<div class="info-container">

    <h4>Próximos vencimentos</h4>
    <div class="validity-card">
    <?php
    $validity_query = "SELECT * FROM products WHERE validity_product <> '0000-00-00' ORDER BY validity_product ASC LIMIT 3";
    $validity_result = mysqli_query($db, $validity_query);
    $count_while = 3;
    if (mysqli_num_rows($validity_result) > 0) {
    while ($row = mysqli_fetch_assoc($validity_result)) {
        $count_while--;
        $product_name = $row["name_product"];
        $validity_date = $row["validity_product"];
        $today_date = date("Y-m-d");

        $today_time = time();
        $validity_time = strtotime($validity_date);
        $variation_time = ceil(($today_time - $validity_time) / (60 * 60 * 24));

        $status_type = ($validity_date >= $today_date) ? ($variation_time >= -1 ? "warning" : "success") : "danger";
        
        $session_id = $row['session_id'];
        $sessionQuery = "SELECT * FROM sessions WHERE session_id = $session_id";
        $sessionData = mysqli_fetch_assoc(mysqli_query($db, $sessionQuery));
        ?>

        
            <div class="product-item">
                <div class="validity-label">
                    <div class="image-container <?php echo $status_type;?>">
                        <img width="100" src="data:image;base64,<?php echo base64_encode($row['image_product']); ?>" alt="Imagem"> 
                    </div>
                    <span>
                        <?php echo ucfirst(strtolower($row['name_product']));?>
                        <p><?php echo ucfirst(strtolower($sessionData['name_session']))?></p>
                    </span>
                </div>
                <div class="validity-status <?php echo $status_type;?>"><?php echo date("d/m/y", strtotime($validity_date))?></div>
            </div>
        

        <?php
    }
}
    for ($i = 0; $i < $count_while; $i++) {
        ?>

        <div class="product-item hidden-empty">
            <div class="validity-label hidden-empty">
                <div class="image-container hidden-empty">
                    <div class="img hidden-empty"></div>
                </div>
                <span class="hidden-empty">
                    <p class="hidden-empty"></p>
                    <p class="hidden-empty"></p>
                </span>
            </div>
            <div class="validity-status hidden-empty"></div>
        </div>
        <?php
    }
?>
</div>
</div>
</div>

<div class="charts-container">
    <h4>Registros</h4>
    <div class="charts">
            <?php
            if (empty($json_data)) {
            ?>
            <div class="chart-01 chart-empty">
            
            <div class="bar chart-empty">
                <div class="label chart-empty"></div>
                <div class="bar-item chart-empty">
                    <div class="bar-fill chart-empty" style="width: 9rem"></div>
                </div>
            </div>
            <div class="bar chart-empty">
                <div class="label chart-empty"></div>
                <div class="bar-item chart-empty">
                    <div class="bar-fill chart-empty" style="width: 15rem"></div>
                </div>
            </div>
            <div class="bar chart-empty">
                <div class="label chart-empty"></div>
                <div class="bar-item chart-empty">
                    <div class="bar-fill chart-empty" style="width: 6rem"></div>
                </div>
            </div>

            <?php
            } else {
                ?>
                <div class="chart-01">
                <?php
                foreach ($json_data as $key => $item) {
                    $max_percentage = max(array_column($json_data, 'percentage'));
                    $colors = ["#00984E", "#00E567", "#63F298"];

                    $color = isset($colors[$key]) ? $colors[$key] : "#000000";
                    $label = $item["category"];
                    $percentage = $item["percentage"];

                    if ($percentage >= 1) {
                        $formattedPercentage = number_format($percentage, 0);
                    } elseif ($percentage >= 0.1) {
                        $formattedPercentage = number_format($percentage, 1);
                    } else {
                        $formattedPercentage = number_format($percentage, 2);
                    }
                    $amount = $item["amount"];

                    $width_percentage = ($percentage / $max_percentage) * 100;

                    echo '<div class="bar">
                            <div class="label">' . ucfirst(strtolower($label)) . '</div>
                            <div class="bar-item">
                                <div class="bar-fill" style="width: ' . $width_percentage . '%; background-color: ' . $color . ';"></div>
                                <div class="label">' . $formattedPercentage . '% </div>
                            </div>
                        </div>';  
                }
            }
            ?>
            </div>
            
            <?php
                if (empty($json_data)) {
            ?>
            <div class="chart-02 chart-empty">
            </div>
            <?php
                } else { 
            ?>
            <div class="chart-02">
                <div id="donut_single" style="width: 190px; height: 190px"></div>
            </div>
            <?php
                }
            ?>
            
        </div>
    </div>
</section>
</html>