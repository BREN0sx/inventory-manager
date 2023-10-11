<!DOCTYPE html>
<html lang="en">
<?php require '../includes/_db.php' ?>
<?php require '../includes/_header.php' ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php
$products_group_query = "SELECT session_id, COUNT(*) AS amount FROM products GROUP BY session_id";
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

    $colors = ["#08A85F", "#BA6B0D", "#067DBF", "#BB195F", "#57FF33"];
    $color = isset($colors[$key]) ? $colors[$key] : "#000000";

    $item_group['percentage'] = ($item_group['amount'] / $products_group_total) * 100;

    $json_data[] = array(
        'category' => $sessionName,
        'percentage' => $item_group['percentage'],
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
            192
        </div>

    </div>
</div>

<script>
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var jsonData = <?php echo json_encode($json_data); ?>;

        var data = google.visualization.arrayToDataTable([
        ['Category', 'Percentage', {type: 'string', role: 'style'}],
        <?php
            foreach ($json_data as $item) {
                echo "['" . $item['category'] . "', " . $item['percentage'] . ", '" . $item['color'] . "'],";
            }
        ?>
    ]);

        var options = {
          pieHole: 0.6,
          pieSliceText: 'none',
          tooltip: { trigger: 'none' },
          legend: 'none',
          colors: ["#08A85F", "#BA6B0D", "#067DBF", "#BB195F", "#57FF33"],
          backgroundColor: 'none',
          pieSliceBorderColor: 'none',
          chartArea:{width:'90%',height:'90%'},
          sliceVisibilityThreshold: .1
        };

        var chart = new google.visualization.PieChart(document.getElementById('donut_single'));
        chart.draw(data, options);
      }
</script>

<section class="info">
<div class="info-container">
    <h4>Vencimento próximo</h4>

    <?php
    $validity_query = "SELECT * FROM products WHERE validity_product IS NOT NULL ORDER BY validity_product ASC LIMIT 5";
    $validity_result = mysqli_query($db, $validity_query);
    if (mysqli_num_rows($validity_result) > 0) {
        /* echo "<ul>"; */
    while ($row = mysqli_fetch_assoc($validity_result)) {
        $product_name = $row["name_product"];
        $validity_date = $row["validity_product"];
        $today = date("Y-m-d");
        if ($validity_date !== '0000-00-00') {
        ?>

        <div class="validity-card">
            <div class="product-item">
                <div class="image-container" style="border-color: <?php echo ($validity_date >= $today) ? "#34d65c" : "#d63434" ?>">
                    <img width="100" src="data:image;base64,<?php echo base64_encode($row['image_product']); ?>" alt="Imagem"> 
                </div>
                <?php echo ucfirst(strtolower($row['name_product']));?>
            </div>
        </div>

        <?php
        }
        /* if ($validity_date >= $today) {
            // Produto válido
            echo "<li>$product_name - Validade: $validity_date</li>";
        } else {
            // Produto vencido (em vermelho)
            echo "<li style='color: red;'>$product_name - Validade: $validity_date (VENCIDO)</li>";
        } */
    }
    /* echo "</ul>"; */
} else {
    echo "Nenhum produto próximo da data de validade encontrado.";
}
    ?>
    </div>
</div>

<div class="charts-container">
    <h4>Registros</h4>
    <div class="charts">
        <div class="chart-01">
            <?php
            foreach ($json_data as $key => $item) {
                $max_percentage = max(array_column($json_data, 'percentage'));
                $colors = ["#08A85F", "#BA6B0D", "#067DBF", "#BB195F", "#57FF33"];

                $color = isset($colors[$key]) ? $colors[$key] : "#000000";
                $label = $item["category"];
                $percentage = number_format($item["percentage"], 0);
                $amount = $item["amount"];

                $width_percentage = ($percentage / $max_percentage) * 100;

                echo '<div class="bar">
                        <div class="label">' . ucfirst(strtolower($label)) . '</div>
                        <div class="bar-item">
                            <div class="bar-fill" style="width: ' . $width_percentage . '%; background-color: ' . $color . ';"></div>
                            <div class="label">' . $percentage . '% </div>
                        </div>
                    </div>';  
            }
            ?>
            </div>
            <div class="chart-02">
                <div id="donut_single" style="width: 190px; height: 190px"></div>
            </div>
        </div>
    </div>
</section>
    <?php require '../includes/_footer.php' ?>
</html
