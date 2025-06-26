<?php
include_once('conexao.php'); // Conexão com banco

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id']; // ID do agendamento
    $novoStatus = $_POST['status']; // Novo status

    // Atualiza o status
    $sql = "UPDATE agendamentos SET status = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('si', $novoStatus, $id);

    if ($stmt->execute()) {
        echo "Status atualizado com sucesso!<br>";

        // Se status for 'Concluído', vincula automaticamente um serviço
        if (strtolower(trim($novoStatus)) === 'concluído') {
            // Verifica se já existe serviço vinculado
            $check = $conexao->prepare("SELECT id FROM agendamento_servicos WHERE agendamento_id = ?");
            $check->bind_param("i", $id);
            $check->execute();
            $check->store_result();

            if ($check->num_rows === 0) {
                // Insere serviço padrão: serviço_id = 1 (Máquina), quantidade = 1
                $servico_padrao = 1;
                $quantidade = 1;
                $insert = $conexao->prepare("INSERT INTO agendamento_servicos (agendamento_id, servico_id, quantidade) VALUES (?, ?, ?)");
                $insert->bind_param("iii", $id, $servico_padrao, $quantidade);
                if ($insert->execute()) {
                    echo "Serviço padrão vinculado com sucesso!";
                } else {
                    echo "Erro ao vincular serviço: " . $insert->error;
                }
                $insert->close();
            } else {
                echo "Agendamento já possui serviço vinculado.";
            }
            $check->close();
        }
    } else {
        echo "Erro ao atualizar o status: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Dados inválidos.";
}
?>
