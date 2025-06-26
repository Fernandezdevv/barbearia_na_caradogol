<?php
session_start();
include_once("./conexao.php");

// Verifica se o nível de acesso do usuário é "adm"
if ($_SESSION['nivel'] !== 'adm') {
    header('Location: cadastro/login.php');
    exit();
}

// Lógica de busca
if (!empty($_GET['search'])) {
    $data = $_GET['search'];
    $sql = "SELECT * FROM usuarios WHERE id LIKE '%$data%' or nome LIKE '%$data%' or email LIKE '%$data%' or nivel LIKE '%$data%' or numero LIKE '%$data%' ORDER BY id ASC";
} else {
    $sql = "SELECT * FROM usuarios ORDER BY id ASC";
}
$result = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
body {
  background: linear-gradient(145deg, #1a0e2a, #10061b);
  color: #f5f5f5;
  font-family: 'Poppins', sans-serif;
  margin: 0;
  padding: 0;
  min-height: 100vh;
}

/* NAVBAR */
nav.navbar {
  background-color: #140824;
  padding: 12px 25px;
  border-bottom: 2px solid #ffb300;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6);
}
.navbar-brand {
  color: #ffb300;
  font-size: 1.4rem;
  font-weight: 700;
  text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.4);
}
.navbar .btn {
  border-radius: 25px;
  font-weight: 600;
  padding: 10px 20px;
  font-size: 14px;
  box-shadow: 0 0 10px rgba(255, 179, 0, 0.3);
}
.btn-warning {
  background: linear-gradient(45deg, #ffb300, #d38700);
  color: #fff;
  border: none;
}
.btn-danger {
  background-color: #ff4b5c;
  color: #fff;
  border: none;
}

/* TÍTULO */
h1 {
  font-size: 2rem;
  margin: 30px 0 15px;
  text-align: center;
  color: #ffffff;
}

/* SEARCH BOX */
.box-search {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 30px;
  gap: 10px;
}
.box-search input {
  border-radius: 30px;
  padding: 12px 20px;
  border: none;
  width: 320px;
  font-size: 16px;
  background-color: #2a1248;
  color: #fff;
}
.box-search input::placeholder {
  color: #cccccc;
}
.box-search button {
  border-radius: 50%;
  background-color: #ffb300;
  color: #fff;
  width: 45px;
  height: 45px;
  border: none;
  font-size: 20px;
  box-shadow: 0 0 10px rgba(255, 179, 0, 0.4);
}

/* TABELA */
.table-bg {
  background-color: #1e0d33;
  border-radius: 12px;
  padding: 10px;
  overflow-x: auto;
}
.table thead {
  background-color: #2d1a48;
}
.table thead th {
  color: #ffb300;
  font-size: 14px;
  text-transform: uppercase;
  border-bottom: 1px solid #444;
}
.table td {
  background-color: transparent;
  color: #fff;
  font-size: 14px;
  vertical-align: middle;
  padding: 10px;
  border-bottom: 1px solid #292036;
}
.table tbody tr:hover {
  background-color: rgba(255, 179, 0, 0.07);
}

/* DROPDOWN DE NÍVEL */
select.nivel-dropdown {
  background-color: #2a1248;
  border: 2px solid #ffb300;
  border-radius: 25px;
  padding: 5px 12px;
  color: #fff;
  font-weight: bold;
  font-size: 0.85rem;
}
select.nivel-dropdown:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(255, 179, 0, 0.2);
}

/* BOTÃO EXCLUIR */
.btn-sm.btn-danger {
  padding: 6px 10px;
  border-radius: 10px;
  background-color: #ff4b5c;
  border: none;
}
.btn-sm.btn-danger:hover {
  background-color: #e03547;
}

/* RESPONSIVO */
@media (max-width: 576px) {
  h1 {
    font-size: 1.4rem;
  }
  .box-search input {
    width: 220px;
    font-size: 14px;
  }
  .box-search button {
    width: 40px;
    height: 40px;
    font-size: 18px;
  }
  .table th, .table td {
    font-size: 12px;
    padding: 6px;
  }
  .btn-sm.btn-danger {
    padding: 5px 8px;
  }
}

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Página do Administrador</a>
        <div class="d-flex ms-auto">
            <a href="./select2.php" class="btn btn-warning me-3">Agendamentos</a>
            <a href="sair.php" class="btn btn-danger">Sair</a>
        </div>
    </div>
</nav>

<h1>Bem-vindo, Administrador!</h1>

<div class="container">
    <div class="box-search">
        <input type="search" id="pesquisar" placeholder="Pesquisar usuário...">
        <button onclick="searchData()" class="btn btn-warning">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
        </button>
    </div>

    <div class="table-responsive">
        <table class="table text-white table-bg">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nível</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Senha</th>
                    <th>CPF</th>
                    <th>Número</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user_data = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $user_data['id'] ?></td>
                        <td>
                            <select class="nivel-dropdown" onchange="atualizarNivel(<?= $user_data['id'] ?>, this.value)">
                                <option value="cliente" <?= ($user_data['nivel'] == 'cliente' ? "selected" : "") ?>>Cliente</option>
                                <option value="adm" <?= ($user_data['nivel'] == 'adm' ? "selected" : "") ?>>Administrador</option>
                            </select>
                        </td>
                        <td><?= $user_data['nome'] ?></td>
                        <td><?= $user_data['email'] ?></td>
                        <td><?= $user_data['senha'] ?></td>
                        <td><?= $user_data['cpf'] ?></td>
                        <td><?= $user_data['numero'] ?></td>
                        <td>
                            <a class="btn btn-sm btn-danger" href="delete.php?id=<?= $user_data['id'] ?>" title="Deletar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var search = document.getElementById('pesquisar');
    search.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            searchData();
        }
    });
    function searchData() {
        window.location = 'select.php?search=' + search.value;
    }
    function atualizarNivel(userId, novoNivel) {
        $.ajax({
            url: 'atualizar_nivel.php',
            type: 'POST',
            data: { id: userId, nivel: novoNivel },
            success: function(response) {
                alert('Nível atualizado com sucesso!');
            },
            error: function() {
                alert('Erro ao atualizar nível.');
            }
        });
    }
</script>

</body>
</html>
