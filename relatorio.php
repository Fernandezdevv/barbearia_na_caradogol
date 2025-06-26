<?php
session_start();
include_once("conexao.php");

if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'adm') {
    header('Location: cadastro/login.php');
    exit();
}

// 💡 Função para converter DD/MM/YYYY para YYYY-MM-DD, ou manter se já estiver em formato MySQL
function formatarData($data) {
    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $data)) {
        $dt = DateTime::createFromFormat('d/m/Y', $data);
        return $dt ? $dt->format('Y-m-d') : null;
    } else {
        return $data;
    }
}

// 🗓️ Pegando datas do filtro ou definindo padrão
$data_inicio = isset($_GET['data_inicio']) ? formatarData($_GET['data_inicio']) : date('Y-m-01');
$data_fim = isset($_GET['data_fim']) ? formatarData($_GET['data_fim']) : date('Y-m-d');

// 🔎 Debug opcional — exibe os parâmetros da busca
// echo "<pre>Data início: $data_inicio\nData fim: $data_fim</pre>";

// Consulta SQL
$sql = "
    SELECT 
        ag.dia AS data_agendamento,
        COUNT(DISTINCT ag.id) AS total_agendamentos,
        SUM(s.preco * COALESCE(asv.quantidade, 1)) AS total_ganhos
    FROM agendamentos ag
    JOIN agendamento_servicos asv ON ag.id = asv.agendamento_id
    JOIN servicos s ON s.id = asv.servico_id
    WHERE TRIM(LOWER(REPLACE(ag.status, 'í', 'i'))) = 'concluido'
      AND ag.dia BETWEEN ? AND ?
    GROUP BY ag.dia
    ORDER BY ag.dia ASC
";

// Preparar e executar
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "ss", $data_inicio, $data_fim);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

// Armazenar dados
$datas = $vendas = $ganhos = [];
$totalGeral = 0;

while ($row = mysqli_fetch_assoc($resultado)) {
    $datas[] = date('d/m/Y', strtotime($row['data_agendamento']));
    $vendas[] = (int) $row['total_agendamentos'];
    $ganhos[] = (float) $row['total_ganhos'];
    $totalGeral += (float) $row['total_ganhos'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body class="bg-dark text-white">
     <a href="select2.php" class="btn-voltar">
            <i class='bx bx-left-arrow-alt'></i> Voltar
        </a>
<div class="container py-4">
    <h1 class="text-center text-warning mb-4">Relatório de Agendamentos</h1>
    <form class="row g-3 mb-4" method="get" action="relatorio.php">
        <div class="col-md-4">
            <label class="form-label">Data Início</label>
            <input type="text" class="form-control" name="data_inicio" id="data_inicio"
                   value="<?= isset($_GET['data_inicio']) ? $_GET['data_inicio'] : date('d/m/Y', strtotime($data_inicio)) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Data Fim</label>
            <input type="text" class="form-control" name="data_fim" id="data_fim"
                   value="<?= isset($_GET['data_fim']) ? $_GET['data_fim'] : date('d/m/Y', strtotime($data_fim)) ?>">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-success w-100" type="submit">Filtrar</button>
        </div>
    </form>

    <?php if (count($datas) > 0): ?>
        <div class="mb-4">
            <canvas id="vendasChart"></canvas>
        </div>
        <div class="mb-4">
            <canvas id="ganhosChart"></canvas>
        </div>
        <div class="table-responsive">
            <table class="table table-dark table-striped">
                <thead class="table-warning text-dark">
                <tr>
                    <th>Data</th>
                    <th>Agendamentos</th>
                    <th>Ganhos (R$)</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($datas as $i => $data): ?>
                    <tr>
                        <td><?= $data ?></td>
                        <td><?= $vendas[$i] ?></td>
                        <td>R$ <?= number_format($ganhos[$i], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="fw-bold">
                    <td colspan="2" class="text-end">Total Geral</td>
                    <td>R$ <?= number_format($totalGeral, 2, ',', '.') ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            Nenhum dado encontrado para o período selecionado.<br>
            <small>Verifique se existem agendamentos <strong>com status "Concluído"</strong> e com <strong>datas válidas</strong> entre
                <strong><?= date('d/m/Y', strtotime($data_inicio)) ?></strong> e <strong><?= date('d/m/Y', strtotime($data_fim)) ?></strong>.
            </small>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#data_inicio", {dateFormat: "d/m/Y"});
    flatpickr("#data_fim", {dateFormat: "d/m/Y"});
</script>

<?php if (count($datas) > 0): ?>
<script>
    new Chart(document.getElementById('vendasChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($datas) ?>,
            datasets: [{
                label: 'Agendamentos',
                data: <?= json_encode($vendas) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        },
        options: {responsive: true}
    });

    new Chart(document.getElementById('ganhosChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($datas) ?>,
            datasets: [{
                label: 'Ganhos (R$)',
                data: <?= json_encode($ganhos) ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.3)',
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: true,
                borderWidth: 2
            }]
        },
        options: {responsive: true}
    });
</script>
<?php endif; ?>
</body>
</html>



    <style>
        body {
    background-color: #181818;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0 10px 40px;
}

h1 {
    color: #f1c40f;
    text-align: center;
    font-weight: 700;
    margin: 30px 0 40px;
    letter-spacing: 1.2px;
}

.btn-voltar {
    position: fixed;
    top: 20px;
    left: 20px;
    display: flex;
    align-items: center;
    background: #ffb300;
    color: #fff;
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 30px;
    font-size: 16px;
    font-weight: 600;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.25);
    transition: background 0.3s, transform 0.2s;
    z-index: 1000;
}

.btn-voltar:hover {
    background: #d59500;
    transform: translateY(-2px);
}

.btn-voltar i {
    margin-right: 6px;
    font-size: 18px;
}

/* Container do filtro */
.filter-container {
    background-color: #222;
    padding: 20px 25px;
    border-radius: 12px;
    margin-bottom: 40px;
    max-width: 900px;
    margin-left: auto;
    margin-right: auto;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
}

.btn-filter, .btn-print {
    background-color: #f1c40f;
    color: #222;
    border: none;
    border-radius: 6px;
    padding: 12px 24px;
    font-weight: 700;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-filter:hover, .btn-print:hover {
    background-color: #d4ac0d;
}

/* Botão imprimir */
.text-end {
    max-width: 900px;
    margin: 0 auto 20px;
    padding: 0 10px;
    text-align: right;
}

.chart-container {
    background-color: #1f1f1f;
    padding: 20px 25px;
    border-radius: 12px;
    margin: 0 auto 30px;
    max-width: 900px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.7);
}

/* Tabela */
.table-container {
    max-width: 900px;
    margin: 0 auto;
    overflow-x: auto;
}

.table {
    background-color: #222;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.7);
}

.table th, .table td {
    padding: 14px 18px;
    text-align: center;
    vertical-align: middle;
}

.table th {
    background-color: #3a2c66;
    color: #f1c40f;
    font-weight: 700;
    letter-spacing: 0.06em;
}

.table-striped tbody tr:nth-child(odd) {
    background-color: rgba(255, 255, 255, 0.05);
}

.table tbody tr:hover {
    background-color: rgba(241, 196, 15, 0.15);
    cursor: default;
}

/* Mensagem "Nenhum dado" */
.alert {
    max-width: 900px;
    margin: 50px auto;
    padding: 18px 25px;
    border-radius: 12px;
    background-color: #bb8f00;
    color: #222;
    font-weight: 700;
    box-shadow: 0 4px 15px rgba(187, 143, 0, 0.7);
    text-align: center;
}

/* Responsivo */
@media (max-width: 768px) {
    body {
        padding: 0 15px 60px;
    }

    .btn-voltar {
        position: fixed;
        top: 15px;
        left: 15px;
        padding: 8px 12px;
        font-size: 14px;
    }

    h1 {
        font-size: 26px;
        margin: 25px 0 30px;
    }

    .filter-container {
        padding: 15px 20px;
    }

    .filter-container .row > div {
        margin-bottom: 15px;
    }

    .btn-filter, .btn-print {
        width: 100%;
        padding: 12px 0;
        font-size: 16px;
    }

    .text-end {
        text-align: center;
        margin-bottom: 20px;
    }

    .chart-container, .table-container {
        max-width: 100%;
        padding: 15px;
        margin: 0 0 25px;
    }

    .table th, .table td {
        padding: 10px 8px;
        font-size: 14px;
    }

    .table {
        font-size: 14px;
    }

    /* Tabela responsiva estilo "cards" */
    .table thead {
        display: none;
    }

    .table, .table tbody, .table tr, .table td {
        display: block;
        width: 100%;
    }

    .table tr {
        margin-bottom: 15px;
        background-color: #222;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(241, 196, 15, 0.2);
        padding: 15px;
    }

    .table td {
        text-align: right;
        padding-left: 50%;
        position: relative;
        border: none;
        font-weight: 600;
    }

    .table td::before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        width: 45%;
        text-align: left;
        font-weight: 700;
        color: #f1c40f;
        text-transform: uppercase;
    }
}

    </style>
