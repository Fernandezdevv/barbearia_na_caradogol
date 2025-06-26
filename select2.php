<?php
session_start();
require_once('conexao.php');

if (!isset($_SESSION['nivel']) || $_SESSION['nivel'] !== 'adm') {
    header('Location: cadastro/login.php');
    exit();
}

// Obtenção dos filtros da URL
$diaFiltro = $_GET['dia'] ?? '';
$search = $_GET['search'] ?? '';
$barbeiro = $_GET['barbeiro'] ?? '';
$status = $_GET['status'] ?? '';
// Filtros
$condicoes = [];
$params = [];
$tipos = '';

// Filtro de cliente e barbeiro
if (!empty($search)) {
    $condicoes[] = "(usuarios.nome LIKE ? OR agendamentos.barbeiro LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $tipos .= 'ss';
}

if (!empty($barbeiro)) {
    $condicoes[] = "agendamentos.barbeiro = ?";
    $params[] = $barbeiro;
    $tipos .= 's';
}

if (!empty($status)) {
    $condicoes[] = "agendamentos.status = ?";
    $params[] = $status;
    $tipos .= 's';
}

// Filtro de data corretamente comparado com o campo 'dia'
if (!empty($diaFiltro)) {
    $condicoes[] = "agendamentos.dia = ?";
    $params[] = $diaFiltro;
    $tipos .= 's';
}

// WHERE final
$where = '';
if (!empty($condicoes)) {
    $where = 'WHERE ' . implode(' AND ', $condicoes);
}

// Consulta principal
$sql = "SELECT 
            agendamentos.id,
            usuarios.nome AS cliente,
            agendamentos.barbeiro,
            agendamentos.dia,
            agendamentos.horario,
            agendamentos.status,
            GROUP_CONCAT(servicos.nome SEPARATOR ', ') AS servicos
        FROM agendamentos
        JOIN usuarios ON agendamentos.usuario_id = usuarios.id
        LEFT JOIN agendamento_servicos ON agendamentos.id = agendamento_servicos.agendamento_id
        LEFT JOIN servicos ON agendamento_servicos.servico_id = servicos.id
        $where
        GROUP BY agendamentos.id
        ORDER BY agendamentos.dia DESC, agendamentos.horario DESC";

$stmt = $conexao->prepare($sql);
if (!$stmt) {
    die("Erro na preparação da query: " . $conexao->error);
}

if (!empty($params)) {
    $stmt->bind_param($tipos, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();


// Lista de barbeiros únicos para o filtro
$barbeiros_result = $conexao->query("SELECT DISTINCT barbeiro FROM agendamentos");
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamentos - Barbeiro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
 :root {
  --roxo-fundo: #2b134b;
  --roxo-gradiente: #1c0f2e;
  --dourado: #ffb300;
  --dourado-hover: #e6a700;
  --cinza-claro: #514869;
  --cinza-input: #726b91;
  --branco: #ffffff;
  --branco-brilhante: #f9f9f9;
  --vermelho: #b30000;
  --vermelho-hover: #d10000;
  --verde: #5cb85c;
  --laranja: #f0ad4e;
  --fundo-translucido: rgba(0, 0, 0, 0.3);
  --sombra-leve: 0 4px 20px rgba(0, 0, 0, 0.4);
}

body {
  background: linear-gradient(135deg, var(--roxo-fundo), var(--roxo-gradiente));
  color: var(--branco);
  font-family: 'Segoe UI', sans-serif;
  margin: 0;
  padding: 0;
  animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* NAVBAR */
.navbar {
  background-color: rgba(34, 34, 34, 0.95) !important;
  border-bottom: 2px solid var(--dourado);
  box-shadow: var(--sombra-leve);
  padding: 12px 30px;
}

.navbar-brand {
  color: var(--dourado) !important;
  font-size: 24px;
  font-weight: bold;
  text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.6);
  letter-spacing: 1px;
}

.navbar .btn {
  font-weight: 600;
  font-size: 14px;
  border-radius: 18px;
  padding: 8px 18px;
  margin-left: 10px;
  text-decoration: none;
  white-space: nowrap;
  transition: all 0.3s ease-in-out;
}

.btn-primary {
  background: linear-gradient(90deg, var(--dourado), var(--dourado-hover));
  color: var(--branco);
  border: none;
  box-shadow: var(--sombra-leve);
}

.btn-danger {
  background-color: var(--vermelho);
  color: var(--branco);
  border: none;
}

.btn-primary:hover {
  background: linear-gradient(90deg, var(--dourado-hover), var(--dourado));
  transform: scale(1.05);
  box-shadow: 0 0 12px rgba(255, 179, 0, 0.6);
}

.btn-danger:hover {
  background-color: var(--vermelho-hover);
  transform: scale(1.05);
  box-shadow: 0 0 12px rgba(255, 0, 0, 0.5);
}

/* CONTAINER E TÍTULOS */
.container {
  margin: 40px auto;
  max-width: 1000px;
  padding: 20px;
}

h1 {
  font-size: 32px;
  margin-bottom: 30px;
  color: var(--branco);
  text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.4);
}

/* ÁREA DE FILTROS */
.filter-area {
  background-color: var(--cinza-claro);
  padding: 25px 30px;
  border-radius: 15px;
  box-shadow: var(--sombra-leve);
  margin-bottom: 30px;
  transition: all 0.3s ease-in-out;
}

.filter-area .form-control {
  background-color: var(--cinza-input);
  color: var(--branco-brilhante);
  border: none;
  border-radius: 10px;
  padding: 12px 15px;
  margin-bottom: 15px;
  transition: 0.3s ease;
}
::placeholder {
  color: #ffffff !important;
  opacity: 1; /* garante visibilidade */
}

/* Compatibilidade com navegadores antigos */
::-webkit-input-placeholder {
  color: #ffffff !important;
}
:-moz-placeholder {
  color: #ffffff !important;
}
::-moz-placeholder {
  color: #ffffff !important;
}
:-ms-input-placeholder {
  color: #ffffff !important;
}


.filter-area .form-control:focus {
  outline: none;
  background-color: #8e7fb3;
  box-shadow: 0 0 10px var(--dourado);
}

/* TABELA */
.table-bg {
  background: var(--fundo-translucido);
  border-radius: 12px;
  padding: 15px;
  overflow-x: auto;
}

.table {
  width: 100%;
  border-collapse: collapse;
  animation: fadeIn 0.5s ease-in;
  background-color: #1a0e2a;
}

.table thead {
  background-color: var(--branco);
}

.table th {
  color: var(--dourado);
  text-transform: uppercase;
  font-size: 14px;
  padding: 12px;
  text-align: center;
}

.table td {
  padding: 14px 20px;
  text-align: center;
  font-size: 16px;
  border-bottom: 1px solid var(--cinza-claro);
  color: #ffffff;
}

/* Linhas intercaladas (contraste visível em fundo escuro) */
.table-striped tbody tr:nth-child(odd) {
  background-color: #26143d; /* roxo mais claro */
}

.table-striped tbody tr:nth-child(even) {
  background-color: #1a0e2a; /* roxo escuro base */
}

/* Hover em toda linha */
.table tr:hover {
  background-color: rgba(255, 179, 0, 0.12);
  transition: background-color 0.3s;
}

/* Responsivo opcional para mobile */
@media (max-width: 768px) {
  .table th, .table td {
    padding: 10px 8px;
    font-size: 14px;
  }
}


/* STATUS */
.status-btn {
  border: none;
  padding: 8px 15px;
  border-radius: 6px;
  font-weight: bold;
  color: var(--branco);
  box-shadow: 0 0 6px rgba(0, 0, 0, 0.3);
}

.pendente {
  background-color: var(--laranja);
}

.concluido {
  background-color: var(--verde);
}


/* RESPONSIVO */
@media (max-width: 768px) {
  .container {
    padding: 15px;
  }

  .navbar .btn {
    margin-top: 10px;
  }

  .filter-area {
    padding: 20px;
  }

  .table thead {
    display: none;
  }

  .table,
  .table tbody,
  .table tr,
  .table td {
    display: block;
    width: 100%;
  }

  .table tr {
    margin-bottom: 15px;
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
  }

  .table td {
    text-align: right;
    padding-left: 50%;
    position: relative;
    color: var(--branco);
  }

  .table td::before {
    content: attr(data-label);
    position: absolute;
    left: 15px;
    width: 45%;
    text-align: left;
    font-weight: bold;
    color: var(--dourado);
  }
}


    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Página do Barbeiro</a>
        <div class="d-flex">
           <a href="select.php" class="btn btn-primary me-2">Voltar</a>
            <a href="relatorio.php" class="btn btn-primary me-2">Relatório</a>
            <a href="sair.php" class="btn btn-danger">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <h1 class="text-center">Bem-vindo, Barbeiro!</h1>

    <div class="filter-area">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <input type="search" class="form-control" placeholder="Pesquisar cliente" id="pesquisar">
            </div>
            <div class="col-md-3 col-sm-6">
                <select class="form-control" id="barbeiroSelect" onchange="searchBarbeiro()">
                    <option value="">Todos os Barbeiros</option>
                    <?php while ($barbeiro = mysqli_fetch_assoc($barbeiros_result)) {
                        echo "<option value='".$barbeiro['barbeiro']."'>".$barbeiro['barbeiro']."</option>";
                    } ?>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <select class="form-control" id="statusSelect" onchange="searchStatus()">
                    <option value="">Todos os Status</option>
                    <option value="Pendente">Pendentes</option>
                    <option value="Concluído">Concluídos</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6">
                <input type="text" class="form-control" id="dataSelect" placeholder="Escolher Data">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bg text-white">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Barbeiro</th>
                    <th>Horário</th>
                    <th>Serviço</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Ações</th> 

                </tr>
            </thead>
            <tbody>
            <?php 
while ($row = mysqli_fetch_assoc($result)) {
  $dataFormatada = date('d/m/Y', strtotime($row['dia']));
  $statusClass = ($row['status'] == 'Pendente') ? 'pendente' : 'concluido';

  echo "<tr>";
  echo "<td>{$row['id']}</td>";
  echo "<td>{$row['cliente']}</td>";
  echo "<td>{$row['barbeiro']}</td>";
  echo "<td>{$row['horario']}</td>";
  echo "<td>{$row['servicos']}</td>";
  echo "<td>{$dataFormatada}</td>";

  echo "<td>
          <button class='status-btn $statusClass' onclick=\"alterarStatus({$row['id']}, '{$row['status']}', this)\">
            {$row['status']}
          </button>
        </td>";

  echo "<td>
          <button class='btn btn-sm btn-outline-danger btn-remove' onclick=\"esconderLinha(this)\">
            <i class='fas fa-trash'></i>
          </button>
        </td>";

  echo "</tr>";
}
?>
</tbody>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var search = document.getElementById('pesquisar');
    search.addEventListener("keydown", function(event) {
        if (event.key === "Enter") searchData();
    });

    function searchData() {
        window.location = 'select2.php?search=' + search.value;
    }
    function searchBarbeiro() {
        window.location = 'select2.php?barbeiro=' + document.getElementById('barbeiroSelect').value;
    }
    function searchStatus() {
        window.location = 'select2.php?status=' + document.getElementById('statusSelect').value;
    }

    flatpickr("#dataSelect", {
        dateFormat: "d/m/Y",
        onChange: function(selectedDates) {
            const dateForDB = selectedDates[0].toISOString().split('T')[0];
            window.location = 'select2.php?dia=' + dateForDB;
        }
    });

  function alterarStatus(id, statusAtual, botao) {
    const novoStatus = (statusAtual === 'Pendente') ? 'Concluído' : 'Pendente';

    $.ajax({
        url: 'atualizar_status.php',
        method: 'POST',
        data: {
            id: id,
            status: novoStatus
        },
        success: function(response) {
            console.log(response);

            // Atualiza texto e classe do botão
            botao.textContent = novoStatus;
            if (novoStatus === 'Concluído') {
                botao.classList.remove('pendente');
                botao.classList.add('concluido');
            } else {
                botao.classList.remove('concluido');
                botao.classList.add('pendente');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao atualizar status:', error);
            alert('Erro ao atualizar status!');
        }
    });
}
  function esconderLinha(botao) {
    const linha = botao.closest('tr');
    linha.style.display = 'none';
  }
</script>
</body>
</html>
