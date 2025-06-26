

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Serviço</title>
    <link rel="stylesheet" href="agendamentos.css">
</head>
<body>
<a href="./inicio.php" class="btn-voltar">
    <i class='bx bx-left-arrow-alt'></i> Voltar
</a>

<div class="container">
    <h1>Agendar Corte de Cabelo</h1>
    <form id="agendamentoForm" action="agendarservico.php" method="POST">

        <label for="barbeiro">Selecione o Barbeiro:</label>
        <select name="barbeiro" id="barbeiro" required>
            <option value="">Selecione...</option>
            <option value="André">André</option>
            <option value="Jean">Jean</option>
            <option value="Gabriel">Gabriel</option>
            <option value="Patrick">Patrick</option>
        </select>

        <label for="dia">Selecione o Dia:</label>
        <input type="date" name="dia" id="dia" required>

        <label for="horario">Selecione o Horário:</label>
        <select name="horario" id="horario" required>
            <option value="">Selecione um barbeiro e dia primeiro</option>
        </select>

        <label>Serviços:</label>
        <div id="servicos-container">
            <div class="servico-item">
                <select name="servico[]" class="servico-select" required>
                    <option value="">Selecione...</option>
                    <?php
                    include("conexao.php");
                    $sql_servicos = "SELECT * FROM servicos";
                    $result_servicos = $conexao->query($sql_servicos);
                    if ($result_servicos->num_rows > 0) {
                        while($row = $result_servicos->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "' data-preco='" . $row['preco'] . "'>" . $row['nome'] . " - R$" . $row['preco'] . "</option>";
                        }
                    }
                    ?>
                </select>

                <input type="number" name="quantidade[]" class="quantidade-input" value="1" min="1" max="5" required>
            </div>
        </div>

        <button type="button" class="btn-add-servico" onclick="adicionarServico()">+ Adicionar outro serviço</button>

        <div class="total" id="totalValor">Total: R$0,00</div>

        <input type="submit" value="Agendar">
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function adicionarServico() {
    const container = document.getElementById('servicos-container');
    const item = container.querySelector('.servico-item');
    const novoItem = item.cloneNode(true);

    // Resetar os valores do novo item
    novoItem.querySelector('.servico-select').selectedIndex = 0;
    novoItem.querySelector('.quantidade-input').value = 1;

    container.appendChild(novoItem);
    atualizarValorTotal(); // recalcular o total
}

function atualizarValorTotal() {
    let total = 0;
    document.querySelectorAll('.servico-item').forEach(item => {
        const select = item.querySelector('.servico-select');
        const preco = parseFloat(select.options[select.selectedIndex].dataset.preco) || 0;
        const qtd = parseInt(item.querySelector('.quantidade-input').value) || 1;
        total += preco * qtd;
    });
    document.getElementById('totalValor').textContent = 'Total: R$' + total.toFixed(2);
}

$(document).ready(function() {
    $('#barbeiro, #dia').on('change', function() {
        var barbeiro = $('#barbeiro').val();
        var dia = $('#dia').val();

        if (barbeiro && dia) {
            $.ajax({
                url: 'verificar_horarios.php',
                type: 'POST',
                data: { barbeiro: barbeiro, dia: dia },
                success: function(data) {
                    $('#horario').html(data);
                }
            });
        } else {
            $('#horario').html('<option value="">Selecione um barbeiro e dia primeiro</option>');
        }
    });

    $(document).on('change', '.servico-select, .quantidade-input', atualizarValorTotal);
    document.getElementById("dia").setAttribute("min", new Date().toISOString().split('T')[0]);
});
</script>
</body>
