import json
import mysql.connector
import pandas as pd


# Configuração do banco de dados
DB_HOST = "62.72.62.1"
DB_USER = "u749227288_qualidade_root"
DB_PASSWORD = "Mogiforte@1"
DB_DATABASE = "u749227288_qualidade_db"

def connect_to_database():
    return mysql.connector.connect(
        host=DB_HOST,
        user=DB_USER,
        password=DB_PASSWORD,
        database=DB_DATABASE
    )

# Conectar ao banco de dados MySQL
connection = connect_to_database()

# Consulta SQL para recuperar dados da coluna 6 da tabela qualidade_db
query = "SELECT col8 FROM qualidade_db"
cursor = connection.cursor()
cursor.execute(query)

# Obter os resultados e criar um DataFrame
df = pd.DataFrame(cursor.fetchall(), columns=["col8"])

# Contar o número de ocorrências de 'ruim', 'bom' e 'regular'
contagem = df['col8'].value_counts()

# Criar dados no formato adequado para Highcharts
data = [{'name': label, 'y': value} for label, value in contagem.items()]

# Converter dados para formato JSON
data_json = json.dumps(data)

# Criar o código HTML com Highcharts incorporado
html_content = f"""
<!DOCTYPE html>
<html>
<head>
  <title>Distribuição de Qualidade</title>
  <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>

<div id="container" style="width:50%; height:400px;"></div>

<script>
var data = {data_json};

Highcharts.chart('container', {{
    chart: {{
        type: 'pie'
    }},
    title: {{
        text: 'Distribuição de Qualidade'
    }},
    series: [{{
        name: 'Qualidade',
        data: data
    }}]
}});
</script>

</body>
</html>
"""

# Salvar o código HTML em um arquivo
with open('highcharts_pie_chart.html', 'w') as file:
    file.write(html_content)

# Fechar a conexão com o banco de dados
connection.close()
