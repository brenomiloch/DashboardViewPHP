<?php
$servername = "62.72.62.1";
$username = "u749227288_qualidade_root";
$password = "Mogiforte@1";
$dbname = "u749227288_qualidade_db";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta SQL para obter a contagem de "Bom" e "Ruim" para cada valor único de col8
$sqlContagem = "SELECT col8, COUNT(CASE WHEN col8 = 'Bom' THEN 1 END) AS contagemBom, COUNT(CASE WHEN col8 = 'Ruim' THEN 1 END) AS contagemRuim FROM qualidade_db GROUP BY col8";
$resultContagem = $conn->query($sqlContagem);

if (!$resultContagem) {
    die("Query for count failed: " . $conn->error);
}

$data = array();

while ($rowContagem = $resultContagem->fetch_assoc()) {
    $data['categorias_col8'][] = $rowContagem['col8'];
    $data['dados_col8_bom'][] = (int)$rowContagem['contagemBom'];
    $data['dados_col8_ruim'][] = (int)$rowContagem['contagemRuim'];
}

// Converter o array de dados para JSON
$jsonData = json_encode($data);

// Fechar conexão
$conn->close();

// Enviar dados para o frontend
header('Content-Type: application/json');
echo $jsonData;
?>
