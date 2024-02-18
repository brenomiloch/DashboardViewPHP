<!DOCTYPE html>
<html>
<head>
    <title>Distribuição de Qualidade</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<!-- Container for the Column chart -->
<div id="container_col8" style="width:50%; height:400px;"></div>

<script>
// Function to create chart
function criarGrafico(containerId, tipoGrafico, titulo, subtitulo, categorias, dados, eixoYTitulo, tooltipSuffix) {
    Highcharts.chart(containerId, {
        chart: {
            type: tipoGrafico,
        },
        title: {
            text: titulo
        },
        subtitle: {
            text: subtitulo
        },
        xAxis: {
            categories: categorias,
            labels: {
                style: {
                    fontSize: '16px'
                }
            }
        },
        yAxis: {
            title: {
                text: eixoYTitulo,
                margin: 20
            }
        },
        tooltip: {
            valueSuffix: tooltipSuffix
        },
        series: [{
            name: 'Bom',
            data: dados.bom
        }, {
            name: 'Ruim',
            data: dados.ruim
        }]
    });
}

// Function to update charts with new data
function atualizarGraficos() {
    // Make AJAX request to backend.php to fetch data
    $.ajax({
        url: 'backend.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Update data for Column chart
            criarGrafico('container_col8', 'column', 'Distribuição de Qualidade - Coluna 8', '', data.categorias_col8, { bom: data.dados_col8_bom, ruim: data.dados_col8_ruim }, 'Contagem', '');

 },
        error: function(error) {
            console.error('Error fetching data:', error);
        }
    });
}

// Initial call to update charts
atualizarGraficos();

// Set interval to update charts every 5 seconds (adjust as needed)
setInterval(atualizarGraficos, 500000);
</script>

</body>
</html>
