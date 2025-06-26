<?php
include("conexao.php");

$barbeiro = $_POST['barbeiro'];
$dia = $_POST['dia'];

// Consulta os horários já ocupados
$sql = "SELECT horario FROM agendamentos WHERE barbeiro = '$barbeiro' AND dia = '$dia'";
$resultado = $conexao->query($sql);

$horarios_ocupados = [];
if ($resultado->num_rows > 0) {
    while($row = $resultado->fetch_assoc()) {
        $horarios_ocupados[] = $row['horario'];
    }
}


// Gera os horários de meia em meia hora entre 09:00 e 21:00
$horarios_disponiveis = [];
$inicio = new DateTime('09:00');
$fim = new DateTime('23:00');
while ($inicio <= $fim) {
    $horarios_disponiveis[] = $inicio->format('H:i:s');
    $inicio->modify('+30 minutes');
}

// Filtra os horários ocupados
foreach ($horarios_disponiveis as $horario) {
    if (!in_array($horario, $horarios_ocupados)) {
        echo "<option value='$horario'>$horario</option>";
    } else {
        echo "<option value='$horario' disabled>$horario (Ocupado)</option>";
    }
}

$conexao->close();
?>
