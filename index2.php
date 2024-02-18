<?php
$servername = "62.72.62.1";
$username = "u749227288_qualidade_root";
$password = "Mogiforte@1";
$dbname = "u749227288_qualidade_db";

// Criar conexão
$connection = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Função para recuperar dados de uma coluna específica
function getDataForColumn($columnName) {
    global $connection;
    $sql = "SELECT $columnName FROM qualidade_db";
    $result = $connection->query($sql);

    if (!$result) {
        die("Query failed: " . $connection->error);
    }

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row[$columnName];
    }

    return $data;
}

// Recuperar dados para coluna 6 e coluna 7
$data_col6 = getDataForColumn('col6');
$data_col8 = getDataForColumn('col8');

// Contar o número de ocorrências para coluna 6
$counts_col6 = array_count_values($data_col6);
$chart_data_col6 = array();
foreach ($counts_col6 as $label => $value) {
    $chart_data_col6[] = array("name" => $label, "y" => $value);
}

// Contar o número de ocorrências para coluna 7
$counts_col8 = array_count_values($data_col8);
$chart_data_col8 = array();
foreach ($counts_col8 as $label => $value) {
    $chart_data_col8[] = array("name" => $label, "y" => $value);
}

// Converter dados para formato JSON
$data_json_col6 = json_encode($chart_data_col6);
$data_json_col8 = json_encode($chart_data_col8);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Distribuição de Qualidade</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>

<!-- Gráfico de Pizza para coluna 6 -->
<div id="container_col6" style="width:50%; height:400px;"></div>

<script>
var data_col6 = <?php echo $data_json_col6; ?>;

Highcharts.chart('container_col6', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Distribuição de Qualidade - Coluna 6'
    },
    series: [{
        name: 'Qualidade',
        data: data_col6
    }]
});
</script>

<!-- Gráfico de Barra para coluna 7 -->
<div id="container_col8" style="width:50%; height:400px;"></div>

<script>
var data_col8 = <?php echo $data_json_col8; ?>;

Highcharts.chart('container_col8', {
    chart: {
        type: 'column',
        options3d: {
            enabled: true,
            alpha: 10,
            beta: 25,
            depth: 70
        }
    },
    title: {
        text: 'External trade in goods by country, Norway 2021',
        align: 'left'
    },
    subtitle: {
        text: 'Source: ' +
            '<a href="https://www.ssb.no/en/statbank/table/08804/"' +
            'target="_blank">SSB</a>',
        align: 'left'
    },
    plotOptions: {
        column: {
            depth: 25
        }
    },
    xAxis: {
        categories: <?php echo json_encode($data_col8); ?>,
        labels: {
            skew3d: true,
            style: {
                fontSize: '16px'
            }
        }
    },
    yAxis: {
        title: {
            text: 'NOK (million)',
            margin: 20
        }
    },
    tooltip: {
        valueSuffix: ' MNOK'
    },
    series: [{
        name: 'Qualidade',
        data: <?php echo json_encode($chart_data_col8); ?>
    }]
});


</script>

</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$connection->close();
?>
