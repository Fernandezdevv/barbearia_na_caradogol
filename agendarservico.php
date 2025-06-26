<?php
session_start();
include("conexao.php");

// Verifica se o cliente está logado
if (!isset($_SESSION['id'])) {
    header("Location: cadastro/login.php");
    exit;
}

// Recebendo dados do formulário
$barbeiro = $_POST['barbeiro'];
$servicos = $_POST['servico'];        // array de serviços
$quantidades = $_POST['quantidade'];  // array de quantidades
$dia = $_POST['dia'];
$horario_inicial = $_POST['horario'];
$usuario_id = $_SESSION['id'];

// Parâmetro de duração do atendimento
$duracao_corte = 30; // em minutos

// Validação básica de segurança (simples)
if (empty($barbeiro) || empty($servicos) || empty($dia) || empty($horario_inicial)) {
    die("Dados inválidos.");
}

// Converte o horário inicial para DateTime
$horario_atual = new DateTime($horario_inicial);

// Função: verificar disponibilidade de horários consecutivos
function verificar_disponibilidade($conexao, $barbeiro, $dia, $horario_atual, $quantidade, $duracao_corte) {
    for ($i = 0; $i < $quantidade; $i++) {
        $horario_str = $horario_atual->format('H:i:s');
        $stmt = $conexao->prepare("SELECT COUNT(*) FROM agendamentos WHERE barbeiro = ? AND dia = ? AND horario = ?");
        $stmt->bind_param("sss", $barbeiro, $dia, $horario_str);
        $stmt->execute();
        $stmt->bind_result($existe);
        $stmt->fetch();
        $stmt->close();

        if ($existe > 0) {
            return false;
        }
        $horario_atual->modify("+{$duracao_corte} minutes");
    }
    return true;
}

// Função: inserir o agendamento e serviços
function adicionar_agendamentos($conexao, $usuario_id, $barbeiro, $dia, $horario_atual, $quantidade, $duracao_corte, $servicos, $quantidades) {
    for ($i = 0; $i < $quantidade; $i++) {
        $horario_str = $horario_atual->format('H:i:s');
        
        // Inserir o agendamento principal
        $stmt = $conexao->prepare("INSERT INTO agendamentos (usuario_id, barbeiro, dia, horario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $usuario_id, $barbeiro, $dia, $horario_str);
        if (!$stmt->execute()) {
            echo "Erro ao inserir agendamento: " . $stmt->error;
            return false;
        }
        $agendamento_id = $stmt->insert_id;
        $stmt->close();

        // Inserir todos os serviços vinculados
        for ($j = 0; $j < count($servicos); $j++) {
            $servico_id = (int) $servicos[$j];
            $qtd = (int) $quantidades[$j];

            $stmt_servico = $conexao->prepare("INSERT INTO agendamento_servicos (agendamento_id, servico_id, quantidade) VALUES (?, ?, ?)");
            $stmt_servico->bind_param("iii", $agendamento_id, $servico_id, $qtd);
            if (!$stmt_servico->execute()) {
                echo "Erro ao inserir serviço: " . $stmt_servico->error;
                return false;
            }
            $stmt_servico->close();
        }

        // Avança horário para o próximo bloco
        $horario_atual->modify("+{$duracao_corte} minutes");
    }
    return true;
}

// Execução principal
if (verificar_disponibilidade($conexao, $barbeiro, $dia, clone $horario_atual, count($quantidades), $duracao_corte)) {
    if (adicionar_agendamentos($conexao, $usuario_id, $barbeiro, $dia, $horario_atual, count($quantidades), $duracao_corte, $servicos, $quantidades)) {
        header("Location: sucesso.php");
        exit;
    }
}  else {
    header("Location: erro_agendamento.php");
    exit;
}


$conexao->close();
?>
