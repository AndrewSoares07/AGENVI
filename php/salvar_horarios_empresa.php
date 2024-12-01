<?php
include("protect.php");
include("includes/logar-sistema.php");
include("includes/informaçoes_empresa.php");

$idempresa = $_SESSION['idempresa'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dia_semana'])) {

    $dia_semana = $_POST['dia_semana'];
    $horario_ini = $_POST['horario_ini'];
    $horario_fim = $_POST['horario_fim'];


    $deletar = "DELETE FROM horarios_empresa WHERE idempresa = $idempresa";
    if (!$mysqli->query($deletar)) {
        die("Erro ao deletar horários antigos: " . $mysqli->error);
    }


    $sql = $mysqli->prepare("INSERT INTO horarios_empresa (idempresa, dias_semana, horario_ini, horario_fim) VALUES (?, ?, ?, ?)");
    $sql->bind_param("isss", $idempresa, $dia, $ini, $fim);

    foreach ($dia_semana as $key => $dia) {
        $ini = $horario_ini[$dia];
        $fim = $horario_fim[$dia];
        if (!$sql->execute()) {
            die("Erro ao inserir horário para $dia: " . $stmt->error);
        }
    }

 
    header("Location: horario_empresa.php");
    exit();
}
?>
