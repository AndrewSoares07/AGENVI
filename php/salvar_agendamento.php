<?php
include('includes/logar-sistema.php');
include('protect_cliente.php');

// Captura dos dados enviados
$idcliente = $_SESSION['idcliente'];
$semana = $_POST['data'] ?? null;
$idservico = $_POST['idservico'] ?? null;
$idempresa = $_POST['idempresa'] ?? null;
$inicio = $_POST['inicio'] ?? null;
$fim = $_POST['fim'] ?? null;
$idfuncionario = $_POST['idfuncionario'] ?? null;
$dtsemana = $_POST['diaDaSemana'] ?? null;

// Verifica se há campos vazios
if (empty($idcliente) || empty($semana) || empty($idservico) || empty($idempresa) || empty($inicio) || empty($fim) || empty($idfuncionario) || empty($dtsemana)) {
    echo "Campos vazios";
    exit;
}

$preco_serv_sql = "SELECT preco_serv FROM servicos WHERE idservico = ?";
$stmt_servico = $mysqli->prepare($preco_serv_sql);
$stmt_servico->bind_param("i", $idservico);
$stmt_servico->execute();
$result_servico = $stmt_servico->get_result();

if ($result_servico->num_rows === 1) {
    $servico = $result_servico->fetch_assoc();
    $preco_agend = $servico['preco_serv'];

    $sql = "INSERT INTO `agendamento` (`idcliente`, `idempresa`, `idservico`, `idfuncionario`, `status`, `dt_agendamento`, `horario_ini`, `horario_fim`, `dtsemana`, `preco_ad`) 
            VALUES (?, ?, ?, ?, 'em andamento', ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iiiisssss", $idcliente, $idempresa, $idservico, $idfuncionario, $semana, $inicio, $fim, $dtsemana, $preco_agend);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redireciona em caso de sucesso
        header('Location: teste.php');
        exit;
    } else {
        // Mostra erro em caso de falha
        echo "<script>alert('Erro ao concluir o agendamento.');</script>";
    }

    $stmt->close();
} else {
    echo "Serviço sem valor definido ou não encontrado.";
}

// Fecha a conexão com o banco de dados
$stmt_servico->close();
$mysqli->close();
?>
