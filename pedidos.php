<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Agendamentos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --dourado: #ffb300;
            --roxo-escuro: #2b134b;
            --roxo-medio: #3b1e65;
            --fundo-card: #381953;
            --branco: #ffffff;
            --cinza-texto: #cccccc;
            --vermelho: #ff3b3b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--roxo-escuro);
            color: var(--cinza-texto);
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .container {
            background: var(--fundo-card);
            width: 100%;
            max-width: 600px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            padding: 40px 30px 20px;
            position: relative;
            animation: fadeIn 0.8s ease forwards;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        h1 {
            font-size: 28px;
            color: var(--dourado);
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: 1px;
        }

        .appointment-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s;
        }

        .appointment-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 15px rgba(0,0,0,0.4);
        }

        .appointment-item strong {
            color: var(--branco);
        }

        .delete-button {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--vermelho);
            border: none;
            color: white;
            padding: 10px 18px;
            border-radius: 30px;
            font-size: 13px;
            cursor: pointer;
            transition: 0.3s;
        }

        .delete-button:hover {
            transform: scale(1.1);
            background: #ff0000;
        }

        .btn-voltar {
            position: fixed;
            top: 30px;
            left: 30px;
            background: var(--dourado);
            color: var(--roxo-escuro);
            padding: 16px 30px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 6px 15px rgba(0,0,0,0.5);
            transition: transform 0.3s;
            z-index: 999;
        }

        .btn-voltar:hover {
            transform: translateY(-3px);
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px 20px;
            }
            .btn-voltar {
                top: 20px;
                left: 20px;
                padding: 12px 20px;
                font-size: 14px;
            }
            .appointment-item {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<a href="./inicio.php" class="btn-voltar">Voltar</a>

<div class="container">
    <h1>Meus Agendamentos</h1>

    <?php
    include("conexao.php");
    session_start();
    if (!isset($_SESSION['id'])) {
        header("Location: cadastro/login.php");
        exit;
    }
    $usuario_id = $_SESSION['id'];

    if (isset($_POST['cancelar_agendamento'])) {
        $agendamento_id = $_POST['agendamento_id'];
        $sql_delete = "DELETE FROM agendamentos WHERE id = '$agendamento_id' AND usuario_id = '$usuario_id'";
        if ($conexao->query($sql_delete) === TRUE) {
            echo "<p style='color: #00ff99;'>Agendamento cancelado com sucesso.</p>";
        } else {
            echo "<p style='color: red;'>Erro ao cancelar: " . $conexao->error . "</p>";
        }
    }

    $sql = "SELECT id, barbeiro, dia, horario, quantidade, status 
            FROM agendamentos 
            WHERE usuario_id = '$usuario_id' AND status != 'Concluído'";
    $result = $conexao->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='appointment-item'>";
            echo "<strong>Barbeiro:</strong> {$row['barbeiro']}<br>";
            echo "<strong>Dia:</strong> {$row['dia']}<br>";
            echo "<strong>Horário:</strong> {$row['horario']}<br>";
            echo "<strong>Status:</strong> {$row['status']}<br>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='agendamento_id' value='{$row['id']}'>";
            echo "<button type='submit' name='cancelar_agendamento' class='delete-button'>Cancelar</button>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<br><br><p style='color: var(--branco); text-align: center;'>Você não tem agendamentos no momento.</p>";
    }
    $conexao->close();
    ?>
</div>

</body>
</html>
